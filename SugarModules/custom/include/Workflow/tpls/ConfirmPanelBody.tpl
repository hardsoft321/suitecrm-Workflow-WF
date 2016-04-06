<input type="hidden" name="current_status" id="confirmForm_current_status" value="{$workflow.currentStatus}" /> {* Для автотеста *}
{if !empty($workflow.confirmData) or !empty($workflow.roles) or !empty($workflow.statusAssignedUsers) or !empty($workflow.customView)}
  {if !empty($workflow.confirmData)}
    {include file="custom/include/Workflow/tpls/ConfirmBlock.tpl"}
  {/if}
  {if !empty($workflow.roles)}
    {include file="custom/include/Workflow/tpls/AssignBlock.tpl"}
  {/if}
  {if !empty($workflow.statusAssignedUsers)}
    {include file="custom/include/Workflow/tpls/LogBlock.tpl"}
  {/if}  
  {if !empty($workflow.customView)}
    {$workflow.customView}
  {/if}
{else}
<p>{sugar_translate label="LBL_NO_DATA"}</p>
{/if}
