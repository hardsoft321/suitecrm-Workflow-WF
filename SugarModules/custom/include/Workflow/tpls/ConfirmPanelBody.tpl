<input type="hidden" name="current_status" id="confirmForm_current_status" value="{$workflow.currentStatus}" /> {* Для автотеста *}
{if !empty($workflow.confirmData) or !empty($workflow.roles) or !empty($workflow.statusAssignedUsers) or !empty($workflow.customView)}
  {if !empty($workflow.confirmData)}
    <div id="confirm_block" class="wf_block">
    <h4>{if !empty($workflow.confirmData.title)}{$workflow.confirmData.title}{else}{sugar_translate label='LBL_CONFIRM_STATUS' module='WFWorkflows'}{/if}</h4>
    {include file="custom/include/Workflow/tpls/ConfirmBlock.tpl"}
    </div>
  {/if}
  {if !empty($workflow.roles)}
    <h4>{if !empty($workflow.assignFormTitle)}{$workflow.assignFormTitle}{else}{sugar_translate label='LBL_ASSIGNED_CHANGE_TITLE' module='WFWorkflows'}{/if}</h4>
    {include file="custom/include/Workflow/tpls/AssignBlock.tpl"}
  {/if}
  {if !empty($workflow.statusAssignedUsers)}
    <h4>{sugar_translate label='LBL_ASSIGNEDS' module='WFWorkflows'}</h4>
    {include file="custom/include/Workflow/tpls/LogBlock.tpl"}
  {/if}  
  {* moved to ConfirmBlock *}
  {* {if !empty($workflow.customView)}
    {$workflow.customView}
  {/if} *}
{else}
<p>{sugar_translate label="LBL_NO_DATA"}</p>
{/if}
