/* 0.9.8.1 Survey fix */
ALTER TABLE `cot_survey_questions` MODIFY `question_type` char(64) collate utf8_unicode_ci NOT NULL default '';
UPDATE `cot_survey_questions` SET `question_type` = 'string' WHERE `question_type` = '1';
UPDATE `cot_survey_questions` SET `question_type` = 'textarea' WHERE `question_type` = '2';
UPDATE `cot_survey_questions` SET `question_type` = 'check' WHERE `question_type` = '3';
UPDATE `cot_survey_questions` SET `question_type` = 'radio' WHERE `question_type` = '4';
UPDATE `cot_survey_questions` SET `question_type` = 'select' WHERE `question_type` = '5';
UPDATE `cot_survey_questions` SET `question_type` = 'separator' WHERE `question_type` = '6';
UPDATE `cot_survey_questions` SET `question_type` = 'date' WHERE `question_type` = '7';
UPDATE `cot_survey_questions` SET `question_type` = 'name' WHERE `question_type` = '8';
UPDATE `cot_survey_questions` SET `question_type` = 'math' WHERE `question_type` = '9';
UPDATE `cot_survey_questions` SET `question_type` = 'email' WHERE `question_type` = '10';
UPDATE `cot_survey_questions` SET `question_type` = 'number' WHERE `question_type` = '11';
UPDATE `cot_survey_questions` SET `question_type` = 'emailto' WHERE `question_type` = '12';
UPDATE `cot_survey_questions` SET `question_type` = 'file' WHERE `question_type` = '13';

