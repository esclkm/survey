<!-- BEGIN: MAIN -->
<h2>{PHP.L.survey}</h2>
<div class="block">
	{FILE "{PHP.cfg.themes_dir}/{PHP.theme}/warnings.tpl"}
	<script type="text/javascript" src="js/jquery.tablednd.min.js"></script>
	<script type="text/javascript">
		var ident={PHP.fval};
		var qid={PHP.qid};
	</script>
	<script src="{PHP.cfg.modules_dir}/survey/js/survey.admin.js" type="text/javascript"></script>

	<!-- BEGIN: SURVEY_GENERALINFO -->
	<form action="{SUR_GI_FORMACTION}" method="post" name="general">
		<table class="cells">
			<tr>
				<td>
					{PHP.L.Title}
				</td>
				<td>
					{SUR_GI_NAME}
				</td>
			</tr>
			<tr>
				<td >
					{PHP.L.SUR15}
				</td>
				<td>
					{SUR_GI_EMAIL}
				</td>
			</tr>
			<tr>
				<td >{PHP.L.SUR12}</td>
				<td>
					{SUR_GI_ONCE}
				</td>
			</tr>
			<tr>
				<td colspan="2">
					{PHP.L.SUR20}
					{SUR_GI_MESSAGE}
				</td>
			</tr>
			<tr>
				<td colspan="2">
					{PHP.L.SUR21}
					{SUR_GI_SUB_MESSAGE}
				</td>
			</tr>
			<tr>
				<td>
					{PHP.L.sur_tester}
				</td>
				<td>
					<span style="display:none" id="tester_selector"><select name='survey_test'>
							<option value='0'>{PHP.L.No}</option>
							<option value='1'  >{PHP.L.Yes}</option></select></span>
					<span id="surveytester"> {PHP.L.Sur_questcount}: 
						<input type='text' name='survey_tester' size='5' maxlength='3' value="{SUR_GI_TESTER}" />
					</span>
				</td>
			</tr>
		</table>
		<br />
		<!-- BEGIN: OPTIONS -->
		<table id="quest" class="cells">
			<tr class="nodrag nodrop">
				<td class="coltop">ID</td>
				<td class="coltop">{PHP.L.SUR22}</td>
				<td class="coltop">{PHP.L.Type}</td>
				<td class="coltop">{PHP.L.adm_urls_parameters}</td>
				<td class="coltop">{PHP.L.sur_right}</td>
				<td class="coltop"></td>
			</tr>
			<!-- BEGIN: OPT -->
			<tr id="quest_{SUR_OPTIONS_FIELDID}">

				<td><div class="qid">{{SUR_OPTIONS_FIELDID}}</div></td>
				<td>
					{SUR_OPTIONS_FIELDTEXT}
				</td>
				<td>
					{SUR_OPTIONS_FIELDTYPE}
					<br />{SUR_OPTIONS_FIELDREQ}
				</td>
				<td>
					{SUR_OPTIONS_FIELDCHOICES}

				</td>
				<td>
					{SUR_OPTIONS_FIELDRIGHT}
				</td>
				<td>
					<input  name='addoption' value='x' onclick='removequest(this)' type='button' />
				</td>
			</tr>
			<!-- END: OPT -->
			<tr id="questbefore" class="nodrag nodrop" style="display:none;text-align:right;"><td colspan='6'>
					<input  name="addoption" value="{PHP.L.Add}" id="addoption" class="special button" type="button" />
				</td></tr>
		</table>
		<!-- END: OPTIONS -->
		<div class="centerall"><input class='button confirm large' type='submit' value='{SUR_GI_SUBTEXT}' name='{SUR_GI_SUBMIT}' /></div>
	</form>
	<!-- END: SURVEY_GENERALINFO -->

	<!-- BEGIN: HELP -->
	<b>{PHP.L.Help}</b>
	<br /><hr />
	<b>&raquo; <u>{PHP.L.SUR22}</u></b>
	{PHP.L.SUR51}
	<br />
	<b>&raquo; <u>{PHP.L.Type}</u></b>
	{PHP.L.SUR54}
	<br />
	<b>&raquo; <u>{PHP.L.adm_urls_parameters}</u></b>
	{PHP.L.SUR55}<br />
	<div id="genhelp">
		<i>{PHP.L.sur_string}</i><span id="helpstring">{PHP.L.sur_string_help}</span>
		<br />
		<i>{PHP.L.sur_textarea}</i><span id="helptextarea">{PHP.L.sur_textarea_help}</span>
		<br />
		<i>{PHP.L.sur_check}</i><span id="helpcheck"> {PHP.L.sur_check_help}</span>
		<br />
		<i>{PHP.L.sur_radio}</i><span id="helpradio"> {PHP.L.sur_radio_help}</span>
		<br />
		<i>{PHP.L.sur_select}</i><span id="helpselect"> {PHP.L.sur_select_help}</span>
		<br />
		<i>{PHP.L.sur_separator}</i><span id="helpseparator"> {PHP.L.sur_separator_help}</span>
		<br />
		<i>{PHP.L.sur_date}</i><span id="helpdate"> {PHP.L.sur_date_help}</span>
		<br />
		<i>{PHP.L.sur_name}</i><span id="helpname"> {PHP.L.sur_name_help}</span>
		<br />
		<i>{PHP.L.sur_math}</i><span id="helpmath"> {PHP.L.sur_math_help}</span>
		<br />
		<i>{PHP.L.sur_email}</i><span id="helpemail"> {PHP.L.sur_email_help}</span>
		<br />
		<i>{PHP.L.sur_number}</i><span id="helpnumber"> {PHP.L.sur_number_help}</span>
		<br />
		<i>{PHP.L.sur_file}</i><span id="helpfile"> {PHP.L.sur_file_help}</span>
	</div>
	<b>&raquo;<u>{PHP.L.sur_right}</u></b> 
	{PHP.L.SUR555}
	<br />
	<!-- END: HELP -->
	<!-- BEGIN: GENERAL -->

	{PHP.L.SUR9}:
	<table class="cells">
		<tr>
			<td class="coltop width5">#</td>
			<td class="coltop">{PHP.L.Name} \ {PHP.L.useed_title}</td>
			<td class="coltop width5">{PHP.L.Votes}</td>
			<td class="coltop width30">{PHP.L.Action}</td>
		</tr>
		<!-- BEGIN: LIST -->
		<tr>
			<td style="text-align:right;">{SUR_ID}</td>
			<td><a href="{SUR_ID|cot_url('admin', 'm=survey&id=$this')}">{SUR_NAME}</a></td>
			<td style="text-align:center;">{SUR_COUNT}</td>
			<td>
				<a href="{SUR_DELETE}" class="button">{PHP.L.Delete}</a>
				<a href="{SUR_RESET}" class="button">{PHP.L.Reset}</a>
				<a href="{SUR_ID|cot_url('survey', 'id=$this')}" class="button">{PHP.L.view_survey}</a>
				<a href="{SUR_ID|cot_url('survey', 'id=$this&a=view')}" class="button special">{PHP.L.View}</a>
			</td>
		</tr>
		<!-- END: LIST -->
		<!-- BEGIN: NO -->
		<tr>
			<td colspan="4">
				<div style='text-align:center;'>{PHP.L.SUR5}</div>
			</td>
		</tr>
		<!-- END: NO -->
		<tr>
			<td colspan="4">
				<div style='text-align:right;'><a href="{SUR_ID|cot_url('admin', 'm=survey&id=new')}" class="confirm large button">{PHP.L.Create}</a></div>
			</td>
		</tr>
	</table>
	<div class="centerall">{SUR_PREV}{SUR_NAV}{SUR_NEXT}</div>

	<!-- END: GENERAL -->
</div>
<!-- END: MAIN -->
