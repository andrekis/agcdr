<?php

/**
 * Search engine controller.
 * 
 * @package	AGCDR
 * @author	Stuart Benjamin Ford <stuartford@me.com>
 * @copyright	2011
 */

/**
 * SearchController.
 */
class SearchController extends BaseController {
	
	/**
	 * Construct via parent.
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct(get_class());	
	}
	
	/**
	 * Prepare and render advanced search form.
	 * 
	 * @return void
	 * @access public
	 */
	public function index() {

		// set default dates to today
		$this->template->date_from = strftime("%m/%d/%Y");
		$this->template->date_to = strftime("%m/%d/%Y");
		
		// render page
		$this->template->show("index");
		
	}
	
	/**
	 * Perform search and return results.
	 * 
	 * @return void
	 * @access public
	 */
	public function results() {

		// is this a quick search?
		if (isset($_POST["quicksearch"])) {

			// clean input
			$search = $this->clean_query($_POST["quicksearch"]);
			
			// check that keyword is at least 3 characters in length
			// this is checked by JavaScript, but need to check just in case
			if (strlen($search) < 3) return false;

			// build query
			
			foreach (array_keys(get_object_vars(new cdr())) as $field) {
				if ($field != "id") {
					$where[] = "{$field} LIKE '%{$search}%'";
				}
			}
			
			$sql = "SELECT	".DB_TABLE.".uniqueid, ".DB_TABLE.".*,
					SEC_TO_TIME(".DB_TABLE.".duration) AS formatted_duration,
					SEC_TO_TIME(".DB_TABLE.".billsec) AS formatted_billsec
				FROM ".DB_TABLE."
				WHERE ".implode(" OR ",$where)."
				ORDER BY calldate DESC;
			";

			// run query
			$results = $this->db->GetAssoc($sql);

			// set quick search string back in template
			$this->template->quicksearch = $search;
			
		} else {
			
			// it's a proper search
			
			// retrieve search criteria
			$criteria = array();
			foreach ($_POST as $key => $value) {
				if (substr($key,0,9) == "criteria_" && strlen($value) >= 3) {
					$row = substr($key,-1,1);
					array_push($criteria,array(
						"field"		=> $_POST["field_{$row}"],
						"operator"	=> $_POST["operator_{$row}"],
						"keywords"	=> $this->clean_query($value)
					));
				}
			}

			// process as long as there's one set of criteria
			if (count($criteria) > 0) {
				
				// build query
				$where = array(
					strftime("calldate >= '%Y/%m/%d 00:00:00'",strtotime($_POST["date_from"])),
					strftime("calldate <= '%Y/%m/%d 23:59:59'",strtotime($_POST["date_to"]))
				);
				
				foreach ($criteria as $crit) {
					
					switch ($crit['operator']) {
						case "contains":
							$keywords = "LIKE '%{$crit['keywords']}%'";
							break;
						case "equals":
							$keywords = "= '{$crit['keywords']}'";
							break;
						case "ltet":
							$keywords = "<= '{$crit['keywords']}'";
							break;
						case "gtet":
							$keywords = ">= '{$crit['keywords']}'";
							break;
					}
					
					$where[] = "{$crit['field']} {$keywords}";
					
				}
				
				$sql = "SELECT	".DB_TABLE.".uniqueid, ".DB_TABLE.".*,
						SEC_TO_TIME(".DB_TABLE.".duration) AS formatted_duration,
						SEC_TO_TIME(".DB_TABLE.".billsec) AS formatted_billsec
					FROM ".DB_TABLE."
					WHERE ".implode(" AND ",$where)."
					ORDER BY calldate DESC;
				";

				// run query
				$results = $this->db->GetAssoc($sql);
				
				// set criteria back in template
				$this->template->criteria = $criteria;
				$this->template->date_from = $_POST["date_from"];
				$this->template->date_to = $_POST["date_to"];
				
			}
			
		}
		
		// set results in template
		$this->template->results = $results;
		$this->template->menuoptions = $this->template->datatablesRecordCountMenu(count($results));
		
		// render page
		$this->template->show("results");
		
	}

	/**
	 * Clean query to prevent SQL injection attacks.
	 * 
	 * @param unknown_type $query
	 */
	private function clean_query($query) {

		// prevents duplicate backslashes
		if (get_magic_quotes_gpc()) $query = stripslashes($query);
		
		return mysql_real_escape_string($query);	
		
	}
	
}

?>