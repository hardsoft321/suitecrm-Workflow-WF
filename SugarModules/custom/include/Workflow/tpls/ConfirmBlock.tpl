<script>
{literal}
SUGAR.util.doWhen('document.readyState == "complete" && typeof lab321 != "undefined" && typeof lab321.wf != "undefined"', function() {
{/literal}
    lab321.wf.assignedUsers = {$workflow.confirmData.assignedUsersString};
{literal}
    addToValidate('confirm', 'resolution', null, true, 'Резолюция');
    addToValidate('confirm', 'assigned_user', null, true, 'Ответственный');
    $('#confirm input[type="submit"]').click(function() {
        return check_form('confirm');
    });
    
    lab321.wf.onChangeNewStatus();
});
{/literal}
</script>

<div id="confirm_block">
<h4 class="formHeader h3Row" style="padding-top: 8px">Согласование</h4>
<input type="hidden" name="confirm_current_status" id="confirm_current_status" value="{$workflow.confirmData.currentStatus}" />
<form id='confirm' name='confirm' action='index.php?entryPoint=wf_confirm' method='POST' 
         style="margin-top: 5px;
                border-style: solid;
                border-width: 1px;
                border-color: #abc3d7;
                padding: 5px;
                padding-right: 0px;">
    <input type='hidden' id='record' name='record' value='{$fields.id.value}'> 
    <input type='hidden' id='module' name='module' value='{$module}'>

    <input type='hidden' id='return_module' name='return_module' value = '{$return_module}'>
    <input type='hidden' id='return_action' name='return_action' value = '{$return_action}'>
    <input type='hidden' id='return_record' name='return_record' value = '{$return_record}'>

    <div class="errors required validation_message"><ul></ul></div>
    
    <table border="0" margin="5" style="min-width:400px">
      <tr margin="15">
        <td style="padding:5px"><label for="resolution">Резолюция:</label><span class="required">*</span></td>
       <td style="padding:5px"><textarea name="resolution" id="resolution" style="width:100%"></textarea></td> 
      </tr>
      <tr margin="15">
        <td style="padding:5px"><label for="status">Новый статус:</label><span class="required">*</span></td>
       <td style="padding:5px">{html_options name=status options=$workflow.confirmData.newStatuses id=newStatus style="width:100%"
                                             onchange="lab321.wf.onChangeNewStatus();"}</td> 
      </tr>

      <tr margin="15">
        <td style="padding:5px"><label for="assigned_user">Ответственный:</label><span class="required">*</span></td>
       <td style="padding:5px">{html_options name=assigned_user options="" id=assigned_user style="width:100%"}</td> 
      </tr>

      <tr margin="15">
      <td style="padding:5px"></td>
      <td style="padding:5px"><input type='submit' name='submit_btn' value='Изменить' onclick="lab321.wf.confirm();return false;"></td>
      </tr>
    </table>
</form>
</div>