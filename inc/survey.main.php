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


//Считываем в массив все записи
$sql1 = $db->query("SELECT * FROM $db_survey_questions WHERE question_surveyid='".$id."' AND question_req!='2'  ORDER by question_position ASC");
while ($row1 = $sql1->fetch())
{
	$questions[$row1['question_id']] = $row1;
}
// Конец считывания

require_once $cfg['system_dir'].'/header.php';

$sskin = cot_tplfile(array('survey', $id), 'module');
$t = new XTemplate($sskin);

$get_results = cot_import('results', 'P', 'ARR');
$submit = count($get_results) > 0 ? true : false;
$show = true;

if ($submit)
{
	$surfileupload = array();
	$mail_message = '';
	foreach ($questions as $key => $row1)
	{

		$q_p = &$row1['question_type'];
		$get_results[$key] = cot_import($get_results[$key], 'D', $q_p == 'check' ? 'ARR' : 'TXT');
		$sur_ans = &$get_results[$key];		
	
		if (!in_array($q_p, array('separator', 'math', 'file')))
		{
			if ($row1['question_req'] && 
				((in_array($q_p, array('string', 'textarea', 'date', 'name', 'email', 'number', 'select')) && empty($sur_ans)) || 
				($q_p == 'check' && !count($sur_ans))))
			{
					$tell_required[$key] = 1;
			}

			if ($q_p == 'email' && !empty($sur_ans) && !preg_match("#([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", $sur_ans))
			{
				$error_text[$key] = $L['sur_erroremail'];
			}
			elseif ($q_p == 'number' && !empty($sur_ans) && !is_numeric($sur_ans))
			{
				$error_text[$key] = $L['sur_errornum'];
			}
			elseif ($q_p == 'string')
			{
				$options = explode_escaped(",", $row1['question_parms'], 4);
				$regmask = $options[3];
				if ($regmask && !preg_match($regmask, $sur_ans))
				{
					$error_text[$key] = $L['sur_errormask'];
				}
			}
			if ($q_p == 'email')
			{
				$mailto_addresses = $sur_ans;
			}
		}
		elseif ($q_p == 'math')
		{
			$sur_ans = field_calc($row1['question_parms']);
		}
		elseif ($q_p == 'file')
		{
			
			$allowed_filetypes = !empty($row1['question_parms']) ? explode(",", $row1['question_parms']) : array('jpg','gif','bmp','png'); // Здесь мы перечисляем допустимые типы файлов
			$filename = $_FILES['results']['name'][$key]; // В переменную $filename заносим точное имя файла (включая расширение).
			$filename = cot_safename($filename, true, (int)$sys['now_offset']);
			$ext = mb_strtolower(end(explode(".", $filename))); // В переменную $ext заносим расширение загруженного файла.
			if (in_array($ext, $allowed_filetypes) && is_writable($cfg['survey']['filepath']))
			{
				$surfileupload[$_FILES['results']['tmp_name'][$key]] = $filename;
				$sur_ans = $cfg['survey']['filepath'].$filename;
			}	
		}
		if (is_array($sur_ans))
		{
			$sur_ans = implode(",", $sur_ans);
		}
		if ($cfg['survey']['fullmail'])
		{
			$mail_ans = ($q_p == 'file') ? $cfg['mainurl'].'/'.$sur_ans : $sur_ans;	
			$mail_message .= cot_parse($row1['question_text'], $cfg['survey']['markup'], $cfg['survey']['parser']).': '.$mail_ans." \n";
		}
	}

	if ($usr['id'] == '0' && $cfg['survey']['captcha'] && isset($cot_captcha))
	{
		$rverify = cot_import('rverify', 'P', 'TXT');
		if (!function_exists(cot_captcha_validate))
		{

			function cot_captcha_validate($verify = 0, $func_index = 0)
			{
				global $cot_captcha;
				if (!empty($cot_captcha[$func_index]))
				{
					$captcha = $cot_captcha[$func_index]."_validate";
					return $captcha($verify);
				}
				return true;
			}

		}

		if (!cot_captcha_validate($rverify))
		{
			cot_error('captcha_verification_failed', 'rverify');
		}
	}

	if (!cot_error_found() && empty($error_text) && empty($tell_required))
	{
		$submit_time = cot_date('datetime_medium', time());
		$mailtext = $L['LAN_SUR42'].": ".$submit_time."\n\n";
		$mailtext .= $mail_message;
		$mail_body = $mailtext;
		
		if ($row['survey_mailto'])
		{
			$usr_email = $row['survey_mailto'];
			$mail_subj = $L['LAN_SUR7']." ".$survey_name;
			cot_mail($usr_email, $mail_subj, $mail_body);
		}

		if ($mailto_addresses)
		{
			$usr_email = $mailto_addresses;
			$mail_subj = $L['LAN_SUR7']." ".$survey_name;
			cot_mail($usr_email, $mail_subj, $mail_body);
		}
		// TODO: запись ! кто уже голосовал и послед проверка.
		$survey_res = array(
			'results_date' => (int)$sys['now_offset'],
			'results_surveyid' => (int)$id,
			'results_userid' => (int)$usr['id'],
			'results_userip' => $usr['ip'],
		);
		$db->insert($db_survey_results, $survey_res);

		$r_id = $db->lastInsertId();

		foreach ($get_results as $key => $val)
		{
			if ((int)$key != 0)
			{
				$survey_ans = array(
					'answers_resultsid' => (int)$r_id,
					'answers_questionid' => (int)$key,
					'answers_surveyid' => (int)$id,
					'answers_result' => $val,
					'answers_right' => '0'
				);
				$db->insert($db_survey_answers, $survey_ans);
			}
		}
		foreach ($surfileupload as $surkey => $surval)
		{
			move_uploaded_file($surkey, $cfg['survey']['filepath'] . $surval);
		}

		$text = $L['LAN_SUR8'];
		if ($row['survey_submit_message'])
		{
			$text = cot_parse($row['survey_submit_message'], $cfg['survey']['markup'], $cfg['survey']['parser']);
		}

		$t->assign(array(
			"SUR_MESSAGE" => $text,
		));
		$t->parse("MAIN.SUBMITED");
		$show = false;
	}
}

if ($show)
{
	$jj = 0;
	
	$def_results = cot_import('dres', 'G', 'ARR');
	
	foreach ($questions as $key => $row1)
	{
		if ($row1['question_type'] != 9)
		{
			list($quest_text, $quest_req, $quest_err) = show_form_question($row1);
			
			$defres = cot_import($def_results[$key], 'D', $row1['question_type'] == 'check' ? 'ARR' : 'TXT');
			$quest_field = show_form_field($row1);
			$sur_sep = false;
			if ($row1['question_type'] == 6)
			{
				$quest_field == '';
				$sur_sep = true;
			}
			$t->assign(array(
				"SUR_QUEST" => $quest_text,
				"SUR_QUEST_REQ" => $quest_req,
				"SUR_QUEST_ERR" => $quest_err,
				"SUR_FIELD" => show_form_field($row1, $defres),
				"SUR_TYPE" => $row1['question_type'],
				"SUR_ODDEVEN" => cot_build_oddeven($jj),
				"SUR_NUM" => $jj,
				"SUR_SEPARATOR" => $sur_sep
			));
			$t->parse("MAIN.FORM.OPTIONS");
			$jj++;
		}
	}
	if ($usr['id'] == '0' && $cfg['survey']['captcha'] && isset($cot_captcha))
	{

		if (!function_exists(cot_captcha_generate))
		{
			function cot_captcha_generate($func_index = 0)
			{
				global $cot_captcha;
				if (!empty($cot_captcha[$func_index]))
				{
					$captcha = $cot_captcha[$func_index]."_generate";
					return $captcha();
				}
				return false;
			}
		}

		$verifyimg = cot_captcha_generate();
		$verifyinput = "<input name='rverify' type='text' id='rverify' size='18' maxlength='6' />";

		$t->assign(array(
			'SUR_VERIFYIMG' => cot_captcha_generate(),
			'SUR_VERIFY' => cot_inputbox('text', 'rverify', '', 'size="10" maxlength="20"'),
		));
		$t->parse("MAIN.FORM.CAPTCHA");
	}
	cot_display_messages($t, 'MAIN.FORM');
	
	$t->assign(array(
		"SUR_GI_FORMACTION" => cot_url("survey", "id=".$id),
		"SUR_GI_MESSAGE" => cot_parse($row['survey_message'], $cfg['survey']['markup'], $cfg['survey']['parser']),
	));
	$t->parse("MAIN.FORM");
}

if ($usr['survey_admin'] && $row['survey_save_results'])
{
	$t->assign(array(
		"SUR_VIEW" => "<a href='".cot_url("survey", "id=".$id."&a=view")."'>".$L['LAN_SUR1']."</a>",
		"SUR_STATS" => "<a href='".cot_url("survey", "id=".$id."&a=stats")."'>".$L['LAN_SUR1']."</a>",
	));
}

$t->assign(array(
	"SUR_ERROR" => $error_string,
	"SUR_ID" => $id,
	"SUR_NAME" => $row['survey_name'],
	"SUR_ADMIN" => $usr['survey_admin']
));

$t->parse("MAIN");
$t->out('MAIN');

require_once $cfg['system_dir'].'/footer.php';

?>