<?php

/**
 * Manages individual caller detail records.
 * 
 * @package	AGCDR.
 * @author	Various, SBF
 * @copyright	2011
 */

/**
 * CdrController.
 */
class CdrController extends BaseController {
	
	/**
	 * Construct via parent.
	 * 
	 * @return void
	 */
	public function __construct() {
		parent::__construct(get_class());	
	}
	
	/**
	 * There is no index for this controller but we have to define the method
	 * because the abstract class demands it.
	 * 
	 * @return void
	 * @access public
	 */
	public function index() { }
	
	/**
	 * View a CDR.
	 * 
	 * @return void
	 * @access public
	 */
	public function view() {

		// retrieve CDR
		$cdr = new cdr();
		$cdr->load_by(array("uniqueid" => $this->get["uid"]));

		// extra information not returned by the standard model
		if ($cdr->uniqueid = $this->get["uid"]) {
			$cdr->duration_formatted = $this->db->GetOne("SELECT SEC_TO_TIME(duration) FROM ".DB_TABLE." WHERE uniqueid='{$this->get["uid"]}'");
			$cdr->billsecs_formatted = $this->db->GetOne("SELECT SEC_TO_TIME(billsec) FROM ".DB_TABLE." WHERE uniqueid='{$this->get["uid"]}'");
		}

		// render page
		$this->template->cdr = $cdr;
		$this->template->show("view");
		
	}

	/**
	 * Generate table of CDRs.
	 * 
	 * @return void
	 * @access public
	 */
	public function table() {
		
		// determine dates
		if (isset($this->get["year"])) {
			
			// calculate overview for year
			$from = "'{$this->get['year']}-01-01 00:00:00'";
			$to = "DATE_ADD('{$this->get['year']}-01-01 00:00:00', INTERVAL 1 YEAR)";
			
		} else if (isset($this->get["month"])) {
			
			// calculate overview for month
			$from = "'{$this->get['month']}-01 00:00:00'";
			$to = "DATE_ADD('{$this->get['month']}-01 00:00:00', INTERVAL 1 MONTH)";
			
		} else {
			
			// no date data passed, just do today
			$today = date("Y-m-d");
			$from = "'{$today} 00:00:00'";
			$to = "DATE_ADD('{$tody} 00:00:00', INTERVAL 1 DAY)";
			
		}

		// retrieve records
		$cdrs = $this->db->GetAssoc("
			SELECT	".DB_TABLE.".uniqueid, ".DB_TABLE.".*,
				SEC_TO_TIME(".DB_TABLE.".duration) AS formatted_duration,
				SEC_TO_TIME(".DB_TABLE.".billsec) AS formatted_billsec
			FROM ".DB_TABLE."
			WHERE calldate >= {$from} AND calldate < {$to}
			ORDER BY calldate ASC;
		");
		
		// assign to template and render page
		$this->template->cdrs = $cdrs;
		$this->template->menuoptions = $this->template->datatablesRecordCountMenu(count($cdrs));
		$this->template->show("table");
		
	}
	
}

?>