{assign var='formName' value=$workflow.assignFormName}

<script>
SUGAR.util.doWhen('document.readyState == "complete" && typeof lab321 != "undefined" && typeof lab321.wf != "undefined"', function() {ldelim}
    var assignedLabel = '{sugar_translate label='LBL_ASSIGNED' module='WFWorkflows'}';
    var formName = '{$formName}';
    addToValidate(formName, 'new_assign_user', null, true, assignedLabel);
    lab321.wf.onChangeRole('{$formName}');
    $('#'+formName+' input[type="submit"]').click(function() {ldelim}
        return check_form('{$formName}');
    {rdelim});
{rdelim});
</script>

<div id="assign_block" class="wf_block">
  <h4>{if !empty($workflow.assignFormTitle)}{$workflow.assignFormTitle}{else}{sugar_translate label='LBL_ASSIGNED_CHANGE_TITLE' module='WFWorkflows'}{/if}</h4>
  <form id='{$formName}' name='{$formName}' action='index.php?entryPoint=wf_assign' method='POST' class="wf_block_body"
        data-confirmusers = "{$workflow.confirmUsersString|escape:"html"}">
    <input type='hidden' name='record' value='{$workflow.record}'>
    <input type='hidden' name='module' value='{$workflow.module}'>
    <input type='hidden' name='return_module' value='{$workflow.module}'>
    <input type='hidden' name='return_action' value='DetailView'>
    <input type='hidden' name='return_record' value='{$workflow.record}'>

    <table>
      <tr margin="15">
        <td><label for="status">{sugar_translate label='LBL_ROLE' module='WFWorkflows'}:</label><span class="required">*</span></td>
        <td>{html_options name=role options=$workflow.roles id=role selected=$workflow.currentRole
                                             onchange="lab321.wf.onChangeRole('$formName');"}</td>
      </tr>
      <tr margin="15">
        <td><label for="status">{sugar_translate label='LBL_NEW_ASSIGNED' module='WFWorkflows'}:</label><span class="required">*</span></td>
        <td>{html_options name=new_assign_user options="" id=new_assign_user}</td>
      </tr>
      <tr margin="15">
        <td></td>
        <td><input type='submit' name='submit_btn' value='{sugar_translate label='LBL_ASSIGN_SUBMIT' module='WFWorkflows'}'></td>
      </tr>
    </table>
  </form>
</div>