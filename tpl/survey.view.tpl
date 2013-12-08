<!-- BEGIN: MAIN -->
<script type='text/javascript' src='{PHP.cfg.modules_dir}/survey/inc/survey_cal.js'></script>
<script type='text/javascript' src='{PHP.cfg.modules_dir}/survey/lang/ru.lang.js'></script>
<div class="block">
<h2><a href="{SUR_ID|cot_url('survey')}">{PHP.L.survey}</a>: {SUR_NAME}</h2>

    <!-- BEGIN: FORM -->
<h2>{PHP.L.Sur_view_num}: {SUR_NUM} </h2>
{PHP.L.Sur_view_date}: {SUR_DATE}
        <!-- BEGIN: OPTIONS -->
    <!-- IF {SUR_SEPARATOR} -->
    <div class="{SUR_SEPARATOR_ODDEVEN}" style="padding: 7px;text-align:right;border-bottom: 1px solid #c8d9e2;border-top: 1px solid #c8d9e2;">
        {SUR_SEPARATOR}
    </div>
    <!-- ENDIF -->
    <div class="{SUR_ODDEVEN}">
        <div style="border-bottom: 1px dashed #c8d9e2;">&raquo; {SUR_QUEST}</div>
        <div style='padding: 7px 10px 10px 50px;'>{SUR_FIELD}</div>
    </div>
    <!-- END: OPTIONS -->
<div style="text-align:right;">{PHP.L.Delete}: [<a href="{SUR_DELETE}">x</a>]</div>
<hr />

    <!-- END: FORM -->
    <div class="centerall">{SUR_PREV}{SUR_NAV}{SUR_NEXT}</div>
</div>
<!-- END: MAIN -->
