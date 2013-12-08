<?php
/* 
 * [BEGIN_COT_EXT]
 * Code=survey
 * Name=Survey
 * Description=Survey service for cotonti. Original plugin (for e107 system) by McFly (mcfly@rocketmail.com)
 * Version=0.9.8
 * Date=2011-10-27
 * Author=esclkm
 * Copyright=Partial copyright (c) 2008-2011 Cotonti Team
 * Notes=BSD License
 * SQL=
 * Auth_guests=R
 * Lock_guests=W12345A
 * Auth_members=R
 * Lock_members=W12345A
 * [END_COT_EXT]

 * [BEGIN_COT_EXT_CONFIG]
 * markup=01:radio::1:
 * parser=02:callback:cot_get_parsers():none:
 * listsurveys=03:select:1,2,3,4,5,6,7,8,9,10,15,20,25,30,50,100:10:Surveys per page
 * viewsurveys=04:select:1,2,3,4,5,6,7,8,9,10,15,20,25,30,50,100:10:Results per page
 * fullmail=05:radio::1:Send full mail
 * filepath=06:string::datas/survey/:File path
 * captcha=07:radio::1:Add captcha for guests
 * css=99:radio:0,1:1:Enable plugin CSS
 * [END_COT_EXT_CONFIG]

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

?>