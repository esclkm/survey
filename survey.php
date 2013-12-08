<?php

/*
 * [BEGIN_COT_EXT]
 * Hooks=module
 * [END_COT_EXT]
 */

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

require_once cot_incfile('extrafields');
require_once cot_incfile('survey', 'module');

list($usr['survey_read'], $usr['survey_write'], $usr['survey_admin']) = cot_auth('survey', 'any');

$id = cot_import('id', 'G', 'INT');
$action = cot_import('a', 'G', 'TXT');

$listshow = true;
if ($id)
{
	$sql = $db->query("SELECT * FROM $db_survey WHERE survey_id=".$id);
	if ($row = $sql->fetch())
	{
		global $db, $db_survey_results, $usr;
		$where =  $usr['id'] ? "results_userid=".$usr['id'] : "results_userip='".$usr['ip']."'";
		$sql = $db->query("SELECT results_id FROM $db_survey_results WHERE results_surveyid=".$id." AND $where LIMIT 1");
		$alreadyvoted = ($sql->rowCount() == 1) ? 1 : 0;

		if (!$usr['survey_read'])
		{

			$error_string .= $L['LAN_SUR6']."<br />";
		}
		elseif ($row['survey_once'] && $alreadyvoted && $action != 'view')
		{

			$error_string .= $L['LAN_SUR2']."<br />";
		}
		elseif ($action == 'view' && $usr['survey_admin'])
		{

			require_once $cfg['modules_dir'].'/survey/inc/survey.view.php';
			$listshow = false;
		}
		else
		{

			require_once $cfg['modules_dir'].'/survey/inc/survey.main.php';
			$listshow = false;
		}
	}
}
if ($listshow)
{
	require_once $cfg['modules_dir'].'/survey/inc/survey.list.php';
}

?>