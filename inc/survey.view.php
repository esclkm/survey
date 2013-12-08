<?php

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

require_once $cfg['system_dir'] . '/header.php';

$sskin = cot_tplfile('survey.view', 'module');
$t = new XTemplate($sskin);

// TODO: Должно быть удаление и просмотр всех анкет
// TODO: Поиск по анкетам фильтр по ИД пользователля и по датам
// TODO: Никто не голосовал
$did = cot_import('did', 'G', 'INT');
list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['survey']['listsurveys']);
$caption = $row['survey_name'];
if ($did)
{
	cot_check_xg();
	$sql = $db->delete($db_survey_results, "results_id=".$did);
	$sql = $db->delete($db_survey_answers, "answers_resultsid=".$did);
}

//Считываем в массив все вопросы
$sql = $db->query("SELECT * FROM $db_survey_questions WHERE question_surveyid='".$id."' ORDER by question_position ASC");
while ($row = $sql->fetch())
{
	$questions[$row['question_id']] = $row;

	if (!empty($separator))
	{
		$splitters[$row['question_id']] = $separator;
		$separator = '';
	}
	if ($row['question_type'] == 6)
	{
		$separator = $row['question_id'];
	}
}

// Конец считывания

$totalitems = $db->query("SELECT COUNT(*) FROM $db_survey_results WHERE results_surveyid=".$id)->fetchColumn();
$jjj = 0;
$sql = $db->query("SELECT * FROM $db_survey_results WHERE results_surveyid=".$id." ORDER BY results_id ASC LIMIT $d, {$cfg['survey']['viewsurveys']}");
while ($row = $sql->fetch())
{
	$jj = 0;
	$sql2 = $db->query("SELECT * FROM $db_survey_answers WHERE answers_resultsid=".$row['results_id']." ORDER by answers_id ASC");
	while ($row2 = $sql2->fetch())
	{
		$q_id = $row2['answers_questionid'];
		if ($questions[$q_id]['question_type'] == 3)
		{
			$row2['answers_result'] = str_replace("|", "<br />", $row2['answers_result']);
		}
		if (isset($splitters[$q_id]))
		{
			$separator = cot_parse($questions[$splitters[$q_id]]['question_text'], $cfg['survey']['markup'], $cfg['survey']['parser']);
			$separatornum = $jj;
			$jj++;
		}
		else
		{
			$separator = '';
		}
		$t->assign(array(
			"SUR_QUEST" => cot_parse($questions[$q_id]['question_text'], $cfg['survey']['markup'], $cfg['survey']['parser']),
			"SUR_FIELD" => $row2['answers_result'],
			"SUR_ODDEVEN" => cot_build_oddeven($jj),
			"SUR_SEPARATOR_ODDEVEN" => cot_build_oddeven($separatornum),
			"SUR_SEPARATOR" => $separator,
			"SUR_SEPARATOR_NUM" => $separatornum,
			"SUR_NUM" => $jj,
		));
		$t->parse("MAIN.FORM.OPTIONS");
		$jj++;
	}
	$jjj++;
	$t->assign(array(
		"SUR_DATE" => cot_date('datetime_medium', $row['results_date']),
		"SUR_DATESTAMP" => $row['results_date'],
		"SUR_DELETE" => cot_url('survey', 'id='.$s_id.'&a=view&d='.$d.'&did='.$row['results_id'].'&'.cot_xg()),
		"SUR_NUM" => $cfg['survey']['viewsurveys'] * $d + $jjj
	));
	$t->parse("MAIN.FORM");
}

$pagenav = cot_pagenav('survey', 'id='.$id.'&a=view', $d, $totalitems, $cfg['survey']['listsurveys']);

$t->assign(array(
	"SUR_NAV" => $pagenav['main'],
	"SUR_PREV" => $pagenav['prev'],
	"SUR_NEXT" => $pagenav['next'],
	"SUR_NAME" => $caption
));
$t->parse("MAIN");
$t->out('MAIN');
require_once $cfg['system_dir'] . '/footer.php';

?>