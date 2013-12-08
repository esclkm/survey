<!-- BEGIN: MAIN -->
<div id="ajaxBlock">
<script type='text/javascript' src='{PHP.cfg.modules_dir}/survey/inc/survey_cal.js'></script>
<script type='text/javascript' src='{PHP.cfg.modules_dir}/survey/lang/ru.lang.js'></script>
<div class="block">
<h2><a href="{PHP.z|cot_url('survey')}">{PHP.L.survey}</a>: {SUR_NAME}</h2>

    <!-- BEGIN: FORM -->
    {SUR_VIEW}{SUR_STATS}
	{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
    <form action="{SUR_GI_FORMACTION}" enctype="multipart/form-data" method="post" id="generalsurvey" name="generalsurvey" class="ajax">
        <div>{SUR_GI_MESSAGE}</div>

        <!-- BEGIN: OPTIONS -->

        <div class="{SUR_ODDEVEN}">
            <!-- IF !{SUR_SEPARATOR} -->
            <div style="border-bottom: 1px dashed #c8d9e2;">&raquo; {SUR_QUEST}</div>

            <div style='color:red;'>{SUR_QUEST_REQ}{SUR_QUEST_ERR}</div>
            <div style='padding: 7px 10px 10px 50px;'>{SUR_FIELD}</div>
             <!-- ELSE -->
             <div style="padding: 7px;text-align:right;border-bottom: 1px solid #c8d9e2;border-top: 1px solid #c8d9e2;">
             {SUR_QUEST}
             </div>
        <!-- ENDIF -->
        </div>

        <!-- END: OPTIONS -->
		<!-- BEGIN: CAPTCHA -->
		<div>{SUR_VERIFYIMG}: {SUR_VERIFY}</div>
		<!-- END: CAPTCHA -->
        {PHP.L.LAN_SUR4}
        <div class='centerall'>
            <input class='button' type='submit'  value='{PHP.L.Submit}' name='submit' />

        </div>

    </form>
    <!-- END: FORM -->
    <!-- BEGIN: SUBMITED -->
    <div>{SUR_MESSAGE}</div>
    <!-- END: SUBMITED -->
</div>
</div>
<!-- END: MAIN -->
