{assign var='formName' value=$workflow.confirmData.formName}
{if empty($formName)}
    {assign var='formNum' value=1|rand:1000}
    {assign var='formName' value='confirmForm0'|cat:$formNum}
    <script>if(typeof console != "undefined") console.error('confirmData.formName was empty and is set to "{$formName}"');</script>
{/if}
<script>
SUGAR.util.doWhen('document.readyState == "complete" && typeof lab321 != "undefined" && typeof lab321.wf != "undefined"', function() {ldelim}
    var resolutionLabel = '{sugar_translate label='LBL_RESOLUTION' module='WFWorkflows'}';
    var assignedLabel = '{sugar_translate label='LBL_ASSIGNED' module='WFWorkflows'}';
    var formName = '{$formName}';
    addToValidate(formName, 'resolution', null, true, resolutionLabel);
    addToValidate(formName, 'assigned_user', null, true, assignedLabel);
    $('#'+formName+' input[type="submit"]').click(function() {ldelim}
        return check_form('{$formName}');
    {rdelim});
    
    lab321.wf.onChangeNewStatus(formName);
{rdelim});
</script>

<div id="confirm_block">
<h4>{if !empty($workflow.confirmData.title)}{$workflow.confirmData.title}{else}{sugar_translate label='LBL_CONFIRM_STATUS' module='WFWorkflows'}{/if}</h4>
<form id='{$formName}' name='{$formName}' action='index.php?entryPoint=wf_confirm' method='POST' 
        data-assignedusers = "{$workflow.confirmData.assignedUsersString|escape:"html"}"
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
    <input type="hidden" name="current_status" id="current_status" value="{$workflow.currentStatus}" />

    <div class="errors required validation_message"><ul></ul></div>
    
    <table border="0" margin="5" style="min-width:400px">
      <tr margin="15">
        <td style="padding:5px"><label for="resolution">{sugar_translate label='LBL_RESOLUTION' module='WFWorkflows'}:</label><span class="required">*</span></td>
       <td style="padding:5px"><textarea name="resolution" id="resolution" style="width:100%"></textarea></td> 
      </tr>
      <tr margin="15">
        <td style="padding:5px"><label for="status">{sugar_translate label='LBL_NEW_STATUS' module='WFWorkflows'}:</label><span class="required">*</span></td>
       <td style="padding:5px">{html_options name=status options=$workflow.confirmData.newStatuses id=newStatus style="width:100%"
                                             onchange="lab321.wf.onChangeNewStatus('$formName');"}</td>
      </tr>

      <tr margin="15">
        <td style="padding:5px"><label for="assigned_user">{sugar_translate label='LBL_ASSIGNED' module='WFWorkflows'}:</label><span class="required">*</span></td>
       <td style="padding:5px">{html_options name=assigned_user options="" id=assigned_user style="width:100%"}</td> 
      </tr>

      <tr margin="15">
      <td style="padding:5px"></td>
      <td style="padding:5px"><input type='submit' name='submit_btn' value='{sugar_translate label='LBL_CONFIRM_SUBMIT' module='WFWorkflows'}' onclick="{if !empty($workflow.confirmData.confirmFunc)}{$workflow.confirmData.confirmFunc}('{$formName}');{else}lab321.wf.confirmStatus('{$formName}');{/if}return false;"></td>
      

      </tr>
    </table>
</form>
</div>