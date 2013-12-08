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
$db_survey = (!empty($db_survey)) ? $db_survey : $db_x.'survey';
$db_survey_results = (!empty($db_survey_results)) ? $db_survey_results : $db_x.'survey_results';
$db_survey_questions = (!empty($db_survey_questions)) ? $db_survey_questions : $db_x.'survey_questions';
$db_survey_answers = (!empty($db_survey_answers)) ? $db_survey_answers : $db_x.'survey_answers';
//						1			2			3		4			5		6			7		8		9		10		11		12
$survey_types = array('string', 'textarea', 'check', 'radio', 'select', 'separator', 'date', 'name', 'math', 'email', 'number', 'file');

require_once(cot_langfile('survey', 'module'));
require_once(cot_incfile('forms'));
require_once(cot_incfile('uploads'));

function show_form_question($field)
{
	global $tell_required, $error_text, $L, $cfg;
	$fn = &$field['question_id'];
	if ($field['question_req'])
	{
		$ret.="* ";
	}
	$ret .= cot_parse($field['question_text'], $cfg['survey']['markup'], $cfg['survey']['parser']);
	$err_req = ($tell_required[$fn]) ? $L['LAN_SUR3'] : "";
	$err_text = $error_text[$fn];
	return array($ret, $err_req, $err_text);
}

function show_form_field($field, $value=null)
{
	global $get_results, $usr, $R, $cfg;

	$fn = $field['question_id'];

	$get_results[$fn] = (is_null($get_results[$fn]) && !empty($value)) ? $value : $get_results[$fn];
	
	$options = explode_escaped(",", $field['question_parms']);
	switch ($field['question_type'])
	{
		case 'string':  // text
			$options[0] = (empty($options[0])) ? "" : ' size="'.$options[0].'"';
			$options[1] = (empty($options[1])) ? "" : ' maxlength="'.$options[1].'"';
			$options[2] = (empty($options[2])) ? "" : ' class="'.$options[2].'"';
			$ret = cot_inputbox('text', 'results['.$fn.']', $get_results[$fn], $options[0].$options[1].$options[2]);
			break;
		case 'textarea': // textarea
			$options[0] = (empty($options[0])) ? 60 : $options[0];
			$options[1] = (empty($options[1])) ? 3 : $options[1];
			$options[2] = (empty($options[2])) ? "" : ' class="'.$options[2].'"';
			$ret = cot_textarea('results['.$fn.']', $get_results[$fn], $options[0], $options[1], $options[2]);
			break;
		case 'check':  //checkbox
			(array)$checked_vals = $get_results[$fn];
			if (!empty($checked_vals))
			{
				foreach ($checked_vals as $k => $v)
				{
					$checked_vals[$k] = trim($v);
				}
			}
			foreach ($options as $o)
			{
				if (!empty($checked_vals))
				{
					$ch = (in_array(trim($o), $checked_vals)) ? "checked='checked'" : "";
				}
				$ret .= '<label><input type="checkbox" value="'.$o.'" name="results['.$fn.'][]" '.$ch.' />&nbsp;'.trim($o).'</label><br />';
			}
			break;
		case 'radio':  //radio
			$ret = cot_radiobox($get_results[$fn], 'results['.$fn.']', $options, $options);
			break;
		case 'select':  //dropdown
			$ret = cot_selectbox($get_results[$fn], 'results['.$fn.']', $options, $options);
			break;
		case 'separator':  //separator
			break;
		case 'date':  //date
			if ($field['question_parms'] != "mdy")
			{
				$calfmt = "dd/mm/y";
				$calmsg = "dd/mm/yyyy";
			}
			else
			{
				$calfmt = "mm/dd/y";
				$calmsg = "mm/dd/yyyy";
			}
			if ($get_results[$fn])
			{
				$xdate = $get_results[$fn];
			}
			$ret .= "
                <input class='tbox' type='text' name='results[{$fn}]' id='date_{$fn}' value='$xdate' />
                <input class='tbox' type='button' name='reset' value=' ... ' id='trigger_{$fn}' /> {$calmsg}
                <script type='text/javascript'>
                Calendar.setup({
                inputField     :    'date_{$fn}',
                ifFormat       :    '{$calfmt}',
                button         :    'trigger_{$fn}',
                singleClick    :    true
                });
                </script>
                ";
			break;
		case 'name':  //name
			if ($usr['id'] > 0)
			{
				$ret = cot_inputbox('hidden', 'results['.$fn.']', $get_results[$fn]).$usr['name'];
			}
			else
			{
				$options[0] = (empty($options[0])) ? "" : ' size="'.$options[0].'"';
				$options[1] = (empty($options[1])) ? "" : ' maxlength="'.$options[1].'"';
				$options[2] = (empty($options[2])) ? "" : ' class="'.$options[2].'"';
				$ret = cot_inputbox('text', 'results['.$fn.']', $get_results[$fn], $options[0].$options[1].$options[2]);
			}
			break;
		case 'email':  //email
			if ($usr['id'] > 0)
			{
				$ret = cot_inputbox('hidden', 'results['.$fn.']', $get_results[$fn]).$usr['profile']['user_email'];
			}
			else
			{
				$options[0] = (empty($options[0])) ? "" : ' size="'.$options[0].'"';
				$options[1] = (empty($options[1])) ? "" : ' maxlength="'.$options[1].'"';
				$options[2] = (empty($options[2])) ? "" : ' class="'.$options[2].'"';
				$ret = cot_inputbox('text', 'results['.$fn.']', $get_results[$fn], $options[0].$options[1].$options[2]);
			}
			break;
		case 'number':  // number
			$options[0] = (empty($options[0])) ? "" : ' size="'.$options[0].'"';
			$options[1] = (empty($options[1])) ? "" : ' maxlength="'.$options[1].'"';
			$options[2] = (empty($options[2])) ? "" : ' class="'.$options[2].'"';
			$ret = cot_inputbox('text', 'results['.$fn.']', $get_results[$fn], $options[0].$options[1].$options[2]);
			break;
		case 'file':
			$ret = cot_filebox('results['.$fn.']');
		break;
	}
	return $ret;
}

function field_calc($str)
{
	global $get_results;
	$i = 0;
	while (preg_match("/\{(.*?)\}/", $str, $matches) && $i < 5)
	{
		$val = $get_results[$matches[1]];
		$str = str_replace("{".$matches[1]."}", $val, $str);
		$i++;
	}
	eval("\$total = ".$str.";");
	return $total;
}

function explode_escaped($delimiter, $string, $limit = 1000000, $escape = '\\')
{
	$exploded = explode($delimiter, $string, $limit);
	$fixed = array();
	for ($k = 0, $l = count($exploded); $k < $l; ++$k)
	{
		if ($exploded[$k][mb_strlen($exploded[$k]) - 1] == $escape)
		{
			if ($k + 1 >= $l)
			{
				$fixed[] = $exploded[$k];
				break;
			}
			$exploded[$k][mb_strlen($exploded[$k]) - 1] = $delimiter;
			$exploded[$k] .= $exploded[$k + 1];
			array_splice($exploded, $k + 1, 1);
			--$l;
			--$k;
		} 
		else
		{
			$fixed[] = $exploded[$k];
		}
	}
	return $fixed;
}

?>