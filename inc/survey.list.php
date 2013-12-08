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

require_once $cfg['system_dir'].'/header.php';

$sskin = cot_tplfile('survey.list', 'module');
$t = new XTemplate($sskin);

list($pn, $d, $d_url) = cot_import_pagenav('d', $cfg['survey']['listsurveys']);
list($usr['survey_read'], $usr['survey_write'], $usr['survey_admin']) = cot_auth('survey', 'any');

if ($usr['survey_read'])
{
	$totalitems = $db->query("SELECT COUNT(*) FROM $db_survey WHERE 1")->fetchColumn();
	$sql2 = $db->query("SELECT * FROM $db_survey WHERE 1 ORDER BY survey_id DESC LIMIT $d, {$cfg['survey']['listsurveys']}");

	while ($row = $sql2->fetch())
	{
		$t->assign(array(
			"SUR_ID" => $row['survey_id'],
			"SUR_NAME" => $row['survey_name'],
			"SUR_TESTER" => $row['survey_tester'],
			"SUR_TEXT" => cot_parse($row['survey_message'], $cfg['survey']['markup'], $cfg['survey']['parser']),
			"SUR_ADMIN" => $usr['survey_admin']
		));
		$t->parse("MAIN.LIST");
		$sur_list = true;
	}
}
if (!$sur_list)
{
	$t->parse("MAIN.NO");
}

if ($error_string)
{
	$t->assign(array(
		"SUR_ERROR" => $error_string,
	));
	$t->parse("MAIN.ERROR");
}
$pagenav = cot_pagenav('survey', '', $d, $totalitems, $cfg['survey']['listsurveys']);
$t->assign(array(
	"SUR_NAV" => $pagenav['main'],
	"SUR_PREV" => $pagenav['prev'],
	"SUR_NEXT" => $pagenav['next']
));
$t->parse("MAIN");
$t->out('MAIN');

require_once $cfg['system_dir'].'/footer.php';
?>