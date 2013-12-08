<?php

/* ====================
 * [BEGIN_COT_EXT]
 * Hooks=admin
 * [END_COT_EXT]
  ==================== */

/**
 * Survey service
 *
 * @package Cotonti
 * @version 0.9.0
 * @author esclkm, littledev.ru
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL.');

list($usr['survey_read'], $usr['survey_write'], $usr['survey_admin']) = cot_auth('survey', 'any');

require_once cot_incfile('extrafields');
require_once cot_incfile('survey', 'module');

$adminpath[] = array(cot_url('admin', 'm=extensions'), $L['Extensions']);
$adminpath[] = array(cot_url('admin', 'm=extensions&a=details&mod='.$m), $cot_modules[$m]['title']);
$adminpath[] = array(cot_url('admin', 'm='.$m), $L['Administration']);

$action = '';
$id = cot_import('id', 'G', 'TXT');

if (cot_import('a', 'G', 'TXT') == 'delete')
{
	$action = 'delete';
}
if (cot_import('a', 'G', 'TXT') == 'reset')
{
	$action = 'reset';
}
elseif (cot_import('add', 'P', 'TXT'))
{
	$action = 'add';
}
elseif (cot_import('update', 'P', 'TXT'))
{
	$action = 'update';
}
$fieldtypes = array();
foreach ($survey_types as $value)
{
	$fieldtypes[$value] = $L['sur_'.$value];
}

if ($action == 'delete')
{
	cot_check_xg();

	$sql = $db->delete($db_survey, "survey_id=".$id);
	$sql = $db->delete($db_survey_results, "results_surveyid=".$id);
	$sql = $db->delete($db_survey_questions, "question_surveyid=".$id);
	$sql = $db->delete($db_survey_answers, "answers_surveyid=".$id);
	cot_message('sur_deleted');
	cot_redirect(cot_url('admin', 'm='.$m));
}
if ($action == 'reset')
{
	cot_check_xg();
	$sql = $db->delete($db_survey_results, "results_surveyid=".$id);
	$sql = $db->delete($db_survey_answers, "answers_surveyid=".$id);
	cot_message('sur_reseted');
	cot_redirect(cot_url('admin', 'm='.$m));
}

if ($action == 'add' || $action == 'update')
{

	$survey['survey_name'] = cot_import('survey_name', 'P', 'TXT');
	$survey['survey_once'] = cot_import('survey_once', 'P', 'TXT');
	$survey['survey_mailto'] = cot_import('survey_mailto', 'P', 'TXT');
	$survey['survey_message'] = cot_import('survey_message', 'P', 'TXT');
	$survey['survey_submit_message'] = cot_import('survey_submit_message', 'P', 'TXT');
	$survey['survey_rightcat'] = cot_import('survey_rightcat', 'P', 'TXT');
	$survey['survey_tester'] = cot_import('survey_tester', 'P', 'INT');

	$field_id = cot_import('field_id', 'P', 'ARR');
	$field_number = cot_import('field_number', 'P', 'ARR');
	$field_text = cot_import('field_text', 'P', 'ARR');
	$field_req = cot_import('field_req', 'P', 'ARR');
	$field_type = cot_import('field_type', 'P', 'ARR');
	$field_choices = cot_import('field_choices', 'P', 'ARR');
	$field_right = cot_import('field_right', 'P', 'ARR');

	cot_check(empty($survey['survey_name']), 'SUR4');

	if (!cot_error_found())
	{
		if ($action == 'add')
		{
			$db->insert($db_survey, $survey);
			$id = $db->lastInsertId();
		}

		if ($action == 'update')
		{
			$db->update($db_survey, $survey, "survey_id=$id");
		}

		foreach ($field_number as $key => $val)
		{
			if (is_int($val))
			{
				$max_array[] = $val;
			}
		}
		$max_n = (!empty($max_array)) ? max($max_array) + 1 : 1;

		foreach ($field_id as $key => $val)
		{
			$ins_v = array();
			$ins_v['question_surveyid'] = (int)$id;
			$ins_v['question_text'] = cot_import($field_text[$key], 'D', 'TXT');
			$ins_v['question_parms'] = cot_import($field_choices[$key], 'D', 'TXT');
			$ins_v['question_right'] = cot_import($field_right[$key], 'D', 'TXT');
			$ins_v['question_req'] = cot_import($field_req[$key], 'D', 'INT');
			$ins_v['question_type'] = cot_import($field_type[$key], 'D', 'TXT');
			$ins_v['question_position'] = cot_import($field_number[$key], 'D', 'INT');

			if ((int)$ins_v['question_position'] < 1)
			{
				$ins_v['question_position'] = $max_n;
				$max_n++;
			}

			if ($val == 'new' && $ins_v['question_text'])
			{
				$db->insert($db_survey_questions, $ins_v);
			}
			elseif ((int)$val > 0)
			{
				if (!empty($field_text[$key]))
				{
					$db->update($db_survey_questions, $ins_v, "question_id='".(int)$val."'");
				}
				else
				{
					$db->delete($db_survey_questions, "question_id='".(int)$val."'");
				}
			}
		}
		cot_message('SUR33');
	}
}

$sskin = cot_tplfile('survey.admin', 'module');
$t = new XTemplate($sskin);

if (empty($id))
{
	list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['survey']['listsurveys']);

	$totalitems = $db->query("SELECT COUNT(*) FROM $db_survey WHERE 1")->fetchColumn();
	$sql2 = $db->query("SELECT * FROM $db_survey WHERE 1 ORDER BY survey_id DESC LIMIT $d, {$cfg['survey']['listsurveys']}");

	while ($row = $sql2->fetch())
	{
		$totalres = $db->query("SELECT COUNT(*) FROM $db_survey_results WHERE results_surveyid=".$row['survey_id'])->fetchColumn();
		$t->assign(array(
			"SUR_ID" => $row['survey_id'],
			"SUR_NAME" => $row['survey_name'],
			"SUR_TESTER" => $row['survey_tester'],
			"SUR_COUNT" => $totalres,
			"SUR_TEXT" => cot_parse($row['survey_message'], $cfg['survey']['markup'], $cfg['survey']['parser']),
			"SUR_DELETE" => cot_url('admin', "m=survey&a=delete&id=".$row['survey_id']."&".cot_xg()),
			"SUR_RESET" => cot_url('admin', "m=survey&a=reset&id=".$row['survey_id']."&".cot_xg()),
		));
		$t->parse("MAIN.GENERAL.LIST");
		$sur_list = true;
	}
	if (!$sur_list)
	{
		$t->parse("MAIN.GENERAL.NO");
	}

	$pagenav = cot_pagenav('admin', 'm=survey', $d, $totalitems, $cfg['survey']['listsurveys'], 'd', '', true);

	$t->assign(array(
		"SUR_NAV" => $pagenav['main'],
		"SUR_PREV" => $pagenav['prev'],
		"SUR_NEXT" => $pagenav['next']
	));
	$t->parse("MAIN.GENERAL");
	$id = '';
}
else
{
	if ((int)$id > 0)
	{
		$sql = $db->query("SELECT * FROM $db_survey WHERE survey_id=".(int)$id);
		if ($survey = $sql->fetch())
		{
			$adminpath[] = array(cot_url('admin', "m=survey&id=".(int)$id), $survey['survey_name']);
			$sql1 = $db->query("SELECT * FROM $db_survey_questions WHERE question_surveyid='".$id."' ORDER by question_position ASC");
			while ($row1 = $sql1->fetch())
			{
				$qid = $row1['question_id'];
				$field_text = cot_inputbox('hidden', 'field_number['.$qid.']', $row1['question_position'], 'class="field_number"')
					.cot_inputbox('hidden', 'field_id['.$qid.']', $row1['question_id'], 'class="field_id"')
					.cot_textarea('field_text['.$qid.']', $row1['question_text'], 4, 25, 'class="field_text"');
				$t->assign(array(
					"SUR_OPTIONS_FIELDTEXT" => $field_text,
					"SUR_OPTIONS_FIELDREQ" => cot_selectbox($row1['question_req'], "field_req['.$qid.']", array(0, 1, 2), array($L['Sur_simple'], $L['Sur_req'], $L['Sur_hidden']), false),
					"SUR_OPTIONS_FIELDTYPE" => cot_selectbox($row1['question_type'], 'field_type['.$qid.']', array_keys($fieldtypes), array_values($fieldtypes), false, 'class="field_type"'),
					"SUR_OPTIONS_FIELDCHOICES" => cot_textarea('field_choices['.$qid.']', $row1['question_parms'], 4, 25, 'class="field_choices"'),
					"SUR_OPTIONS_FIELDRIGHT" => cot_textarea('['.$qid.']', $row1['question_right'], 4, 25, 'class="field_right"'),
					"SUR_OPTIONS_FIELDID" => $row1['question_id'],
				));
				$t->parse("MAIN.SURVEY_GENERALINFO.OPTIONS.OPT");
				$fval = $row1['question_position'];
			}
			$submit_n = "update";
			$submit_v = $L['Update'];
			$survey_url = cot_url("survey", "id=".$survey_id);
		}
		else
		{
			$id = 'new';
		}
	}
	if ($id == 'new')
	{
		$adminpath[] = array(cot_url('admin', "m=survey&id=new"), $L['sur_new']);
		$survey['survey_name'] = "";
		$survey['survey_mailto'] = "";
		$survey['survey_message'] = "";
		$survey['survey_submit_message'] = "";
		$survey_url = "";
		$survey['survey_rightcat'] = "";
		$survey['survey_tester'] = "";
		$submit_n = "add";
		$submit_v = $L['Create'];
	}


	$qid++;
	$fval++;
	$field_text = cot_inputbox('hidden', 'field_number[]', $fval, 'class="field_number"')
		.cot_inputbox('hidden', 'field_id[]', 'new', 'class="field_id"')
		.cot_textarea('field_text[]', '', 4, 25, 'class="field_text"');
	$t->assign(array(
		"SUR_OPTIONS_FIELDTEXT" => $field_text,
		"SUR_OPTIONS_FIELDREQ" => cot_selectbox(0, "field_req[]", array(0, 1, 2), array($L['Sur_simple'], $L['Sur_req'], $L['Sur_hidden']), false),
		"SUR_OPTIONS_FIELDTYPE" => cot_selectbox(0, 'field_type[]', array_keys($fieldtypes), array_values($fieldtypes), false, 'class="field_type"'),
		"SUR_OPTIONS_FIELDCHOICES" => cot_textarea('field_choices[]', '', 4, 25, 'class="field_choices"'),
		"SUR_OPTIONS_FIELDRIGHT" => cot_textarea('field_right[]', '', 4, 25, 'class="field_right"'),
		"SUR_OPTIONS_FIELDID" => 'new',
	));
	$t->parse("MAIN.SURVEY_GENERALINFO.OPTIONS.OPT");

	$t->parse("MAIN.SURVEY_GENERALINFO.OPTIONS");

	$t->assign(array(
		"SUR_GI_FORMACTION" => cot_url("admin", "m=survey&id=".$id),
		"SUR_GI_SURVEY_LINK" => $survey_url,
		"SUR_GI_NAME" => cot_inputbox('text', 'survey_name', $survey['survey_name'], "size='50' maxlength='255'"),
		"SUR_GI_EMAIL" => cot_inputbox('text', 'survey_mailto', $survey['survey_mailto'], "size='50' maxlength='255'"),
		"SUR_GI_ONCE" => cot_selectbox($survey['survey_once'], 'survey_once', array(1, 0), array($L['Yes'], $L['No']), false),
		"SUR_GI_MESSAGE" => cot_textarea('survey_message', $survey['survey_message'], 5, 60, '', 'input_textarea_editor'),
		"SUR_GI_SUB_MESSAGE" => cot_textarea('survey_submit_message', $survey['survey_submit_message'], 5, 60, '', 'input_textarea_editor'),
		"SUR_GI_SUBMIT" => $submit_n,
		"SUR_GI_SUBTEXT" => $submit_v,
		"SUR_GI_TESTER" => $survey['survey_tester'],
	));
	$t->parse("MAIN.SURVEY_GENERALINFO");
	$t->parse("MAIN.HELP");
}

cot_display_messages($t);
$t->parse("MAIN");
$adminmain = $t->text("MAIN");

?>