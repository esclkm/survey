CREATE TABLE IF NOT EXISTS cot_survey (
        survey_id int(10) unsigned NOT NULL auto_increment,
        survey_name varchar(255) collate utf8_unicode_ci NOT NULL default '',
        survey_once tinyint(1) unsigned NOT NULL default '0',
        survey_mailto varchar(255) collate utf8_unicode_ci NOT NULL default '',
        survey_message text collate utf8_unicode_ci NOT NULL,
        survey_submit_message text collate utf8_unicode_ci NOT NULL,
        survey_rightcat varchar(255) collate utf8_unicode_ci NOT NULL default '',
        survey_tester int(10) unsigned NOT NULL default '0',
        PRIMARY KEY  (survey_id)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 CREATE TABLE IF NOT EXISTS cot_survey_questions (
        question_id int(10) unsigned NOT NULL auto_increment,
        question_surveyid int(10) unsigned NOT NULL default '0',
        question_position int(10) unsigned NOT NULL default '0',
        question_text text collate utf8_unicode_ci NOT NULL,
        question_parms text collate utf8_unicode_ci NOT NULL,
        question_right text collate utf8_unicode_ci NOT NULL,
        question_type varchar(64) collate utf8_unicode_ci NOT NULL default 'text',
        question_hide tinyint(1) unsigned NOT NULL default '0',
        question_req  tinyint(2) unsigned NOT NULL default '0',
        PRIMARY KEY  (question_id)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS cot_survey_results (
        results_id int(10) unsigned NOT NULL auto_increment,
        results_date int(11) NOT NULL default '0',
        results_surveyid int(10) unsigned NOT NULL default '0',
        results_userid int(11) unsigned NOT NULL default '0',
        results_userip varchar(15) NOT NULL default '',
        PRIMARY KEY  (results_id)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 CREATE TABLE IF NOT EXISTS cot_survey_answers (
        answers_id int(10) unsigned NOT NULL auto_increment,
        answers_resultsid int(10) unsigned NOT NULL default '0',
        answers_questionid int(10) unsigned NOT NULL default '0',
        answers_surveyid int(10) unsigned NOT NULL default '0',
        answers_result text collate utf8_unicode_ci NOT NULL,
        answers_right tinyint(2) unsigned NOT NULL default '0',
        PRIMARY KEY  (answers_id)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;