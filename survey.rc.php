<?php

/*
 * [BEGIN_COT_EXT]
 * Hooks=rc
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
defined('COT_CODE') or die('Wrong URL');

if ($cfg['survey']['css'])
{
	cot_rc_add_file($cfg['modules_dir'].'/survey/inc/survey_cal.css');
}

?>