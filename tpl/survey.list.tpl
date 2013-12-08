<!-- BEGIN: MAIN -->
<div class="block">
<h2><a href="{SUR_ID|cot_url('survey')}">{PHP.L.survey}</a></h2>

   <!-- BEGIN: ERROR -->
   <div class="error">{SUR_ERROR}</div>
   <!-- END: ERROR -->
    <table class="cells">
        <tr>
            <td class="coltop" style="width:40px;">#</td>
            <td class="coltop">{PHP.L.Name}</td>
            <td class="coltop" style="width:70px;">{PHP.L.View}</td>
        </tr>
        <!-- BEGIN: LIST -->
        <tr>
            <td>{SUR_ID}</td>
            <td><a href="{SUR_ID|cot_url('survey', 'id=$this')}">{SUR_NAME}</a></td>
            <td>
                <!-- IF {SUR_ADMIN} -->
            <a href="{SUR_ID|cot_url('survey', 'id=$this&a=view')}">{PHP.L.View}</a>
                <!-- ELSE -->
            </td>
            <td>
                <!-- ENDIF -->
            </td>
        </tr>
        <!-- END: LIST -->
        <!-- BEGIN: NO -->
        <tr>
            <td colspan="4">
                <div style='text-align:center;'>{PHP.L.SUR5}</div>
            </td>
        </tr>
        <!-- END: NO -->
    </table>
<div class="centerall">{SUR_PREV}{SUR_NAV}{SUR_NEXT}</div>

</div>
<!-- END: MAIN -->
