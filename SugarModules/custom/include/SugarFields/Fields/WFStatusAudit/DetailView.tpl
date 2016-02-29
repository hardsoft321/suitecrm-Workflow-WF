{if isset($fields.confirm_list.value)}
    <div id="confirmlistdiv" name="confirmlistdiv"><textarea id="confirmtext" name="confirmtext">{$fields.confirm_list.value}</textarea></div>
    <script type="text/javascript">
    {literal}
    $(document).ready(draw_confirmlist_in_details);
    function draw_confirmlist_in_details() {
        var confirm_list = document.getElementById('confirmtext').value;
        confirm_list = confirm_list.replace(/$/g, "<br>");
        var cl = confirm_list.split(";");
        document.getElementById('confirmtext').value='';
        var i=0;
        for (i=0; i<cl.length; i++) {
        if (i>0 && i!=cl.length-1) {
            document.getElementById('confirmtext').value=document.getElementById('confirmtext').value+"<hr noshade size=1 width=340 style='margin: 0;'>";
        }
        document.getElementById('confirmtext').value=document.getElementById('confirmtext').value+cl[i];
        }
        document.getElementById('confirmlistdiv').innerHTML = document.getElementById('confirmtext').value.replace(/\n/g, "<br>");
    }
    {/literal}
    </script>
{elseif isset($wf_statusAudit) && count($wf_statusAudit) gt 0}
    <table id="wf_status_audit">
    <tr><th>{$APP.LBL_DATE}</th><th>{$APP.LBL_STATUS_CHANGE}</th><th>{$APP.LBL_USER}</th></tr>
    {foreach from=$wf_statusAudit key=k item=v}
    <tr>
     <td class="date_created">{$v.date}</td>
     <td class="name">{$v.name}</td>
     <td class="user">{$v.first_name} {$v.last_name}</td>
    </tr>
    {/foreach}
    </table>
{else}
{/if}
