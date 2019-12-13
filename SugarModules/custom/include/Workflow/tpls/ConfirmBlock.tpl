<link rel="stylesheet" href="{sugar_getjspath file='custom/include/Workflow/css/wf_confirm_block.css'}" />
<script src="{sugar_getjspath file='custom/include/Workflow/js/wf_ui.js'}"></script>

{assign var='formName' value=$workflow.confirmData.formName}
{if empty($formName)}
    {assign var='formNum' value=1|rand:1000}
    {assign var='formName' value='confirmForm0'|cat:$formNum}
    <script>if(typeof console != "undefined") console.error('confirmData.formName was empty and is set to "{$formName}"');</script>
{/if}

<div class="row edit-view-row wf_block">
<form id='{$formName}' name='{$formName}' action='index.php?entryPoint=wf_confirm' method='POST' class="confirmForm wf_block_body"
    data-resolutionrequired = "{$workflow.confirmData.resolutionRequiredData|@json_encode|escape:"html"}">
    <input type='hidden' id='record' name='record' value='{$workflow.record}'>
    <input type='hidden' id='module' name='module' value='{$workflow.module}'>

    <input type="hidden" name="current_status" id="confirmForm_current_status" value="{$workflow.currentStatus}" />
    <input type="hidden" name="current_status" id="{$formName}_current_status" value="{$workflow.currentStatus}" />

    <div class="errors required validation_message"><ul></ul></div>

  <div class="col-xs-12 edit-view-row-item">
    <div class="col-xs-12 col-sm-4 label" data-label="LBL_STATUS">
      <label for="status">{sugar_translate label='LBL_STATUS' module='WFWorkflows'}:</label>
    </div>
    <div class="col-xs-12 col-sm-8 edit-view-field " type="text" field="status">
      {$workflow.currentStatusName}
    </div>
  </div>

  {if !empty($workflow.confirmData)}
  <div class="col-xs-12 edit-view-row-item">
    <div class="col-xs-12 col-sm-4 label" data-label="LBL_NEW_STATUS">
      <label for="status">{sugar_translate label='LBL_NEW_STATUS' module='WFWorkflows'}:</label><span class="required">*</span>
    </div>
    <div class="col-xs-12 col-sm-8 edit-view-field " type="text" field="status">
      {html_options name=status options=$workflow.confirmData.newStatuses id=newStatus
                                             onchange="lab321.wf.onChangeNewStatus('$formName');"}
    </div>
  </div>

  <div class="col-xs-12 edit-view-row-item">
    <div class="col-xs-12 col-sm-4 label" data-label="LBL_ASSIGNED">
      <label for="assigned_user">{sugar_translate label='LBL_ASSIGNED' module='WFWorkflows'}:</label><span class="required">*</span>
    </div>
    <div class="col-xs-12 col-sm-8 edit-view-field " type="text" field="assigned_user">
      <select name="assigned_user" id="assigned_user"
          data-assignedusers = "{$workflow.confirmData.assignedUsersData|@json_encode|escape:"html"}"></select>
    </div>
  </div>

  <div class="col-xs-12 edit-view-row-item">
    <div class="col-xs-12 col-sm-4 label" data-label="LBL_RESOLUTION">
      <label for="resolution">{sugar_translate label='LBL_RESOLUTION' module='WFWorkflows'}:</label><span class="required">*</span>
    </div>
    <div class="col-xs-12 col-sm-8 edit-view-field " type="text" field="resolution">
      <textarea name="resolution" id="resolution"></textarea>
    </div>
  </div>

  {if !empty($workflow.confirmData.customFields)}{$workflow.confirmData.customFields}{/if}

  <div class="col-xs-12 edit-view-row-item">
    <div class="col-xs-12 col-sm-4 label">
    </div>
    <div class="col-xs-12 col-sm-8 edit-view-field " type="text" field="submit_btn">
      <input type='submit' name='submit_btn' value='{sugar_translate label='LBL_CONFIRM_SUBMIT' module='WFWorkflows'}'
        onclick="{if !empty($workflow.confirmData.confirmFunc)}{$workflow.confirmData.confirmFunc}('{$formName}');
          {else}lab321.wf.confirmStatus('{$formName}');{/if}return false;">
    </div>
  </div>

<script>
SUGAR.util.doWhen('document.readyState == "complete" && typeof lab321 != "undefined" && typeof lab321.wf != "undefined"', function() {ldelim}
    var resolutionLabel = '{sugar_translate label='LBL_RESOLUTION' module='WFWorkflows'}';
    var assignedLabel = '{sugar_translate label='LBL_ASSIGNED' module='WFWorkflows'}';
    var formName = '{$formName}';
    addToValidate(formName, 'resolution', null, true, resolutionLabel);
    addToValidate(formName, 'assigned_user', null, true, assignedLabel);
    lab321.wf.onChangeNewStatus(formName);
    $('.detail-view-field[field="wf_confirm_block"]').parent().children('.label').addClass('wf_confirm_block_label')
    $('.detail-view-field[field="wf_assign_block"]').parent().children('.label').addClass('wf_assign_block_label')
    $('.detail-view-field[field="wf_assigned_block"]').parent().children('.label').addClass('wf_assigned_block_label')
    $('.detail-view-field[field="wf_status_audit"]').parent().children('.label').addClass('wf_status_audit_label')
    $('.detail-view-field[field="confirm_list"]').parent().children('.label').addClass('confirm_list_label')
{rdelim});
</script>

  {/if}

</form>
</div>

{if !empty($workflow.customView)}
<div class="wf_block wf_confirm_custom_block">
  {$workflow.customView}
</div
{/if}
