{include file='shared/htmlheader.tpl'}

<div id="rightbuttons">
	<button type="button" onclick="window.location='/{$controller}/{$action}/?year={$year-1}';">{icon name="control_rewind_blue"}&nbsp;&nbsp;{$year-1}</button>
	<button type="button" onclick="window.location='/{$controller}/{$action}/?year={$year+2}';">{$year+1}&nbsp;&nbsp;{icon name="control_fastforward_blue"}</button>
</div>

<h2>{$year}</h2>
<div id="tabs">

	<ul>
		<li><a href="#tabs-overview">Overview</a></li>
		<li><a href="#tabs-calls">Calls per month</a></li>
		<li><a href="#tabs-mins">Minutes per month</a></li>
		<li><a href="#tabs-todb">Time of day breakdown</a></li>
	</ul>
	
	<div id="tabs-overview">
	
		<ul id="overviewgrid">
			{foreach from=$boxes item=box}
				<li id="box_{$box}">Loading box {$box} ...</li>
			{/foreach}
		</ul>
	
		<div style="clear: both;"></div>
	
	</div>
	
	<div id="tabs-calls">
	
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="left" valign="top">
					<img src="/images/charts/{$chart_calls}" class="greyborder" alt="Calls per month" width="700" height="350"/>
				</td>
			</tr>
		</table>
	
	</div>
	
	<div id="tabs-mins">
	
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td align="left" valign="top">
					<img src="/images/charts/{$chart_mins}" class="greyborder" alt="Minutes per month" width="700" height="350"/>
				</td>
			</tr>
		</table>
	
	</div>
	
	<div id="tabs-todb">
	
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td align="left" valign="top">
					<img src="/images/charts/{$chart_todb}" class="greyborder" alt="Time of day breakdown" width="700" height="350"/>
				</td>
			</tr>
		</table>
	
	</div>

</div>

{literal}

<script type="text/javascript">
	
	$(function() {

		// create tabbed section
		$("#tabs").tabs();
		
		// create sortable grid
		$("#overviewgrid").sortable();
		$("#overviewgrid").disableSelection();

		// set box list
		var allBoxes = new Array({/literal}{$boxlist}{literal});

		// load content into all boxes
		for (i=0; i<allBoxes.length; i++) {
			$("#box_"+allBoxes[i]).load('/reports/box/?box='+allBoxes[i]+"&year={/literal}{$year}{literal}");
		}
			
	});

</script>
	
{/literal}

{include file='shared/htmlfooter.tpl'}
