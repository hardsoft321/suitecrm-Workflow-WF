<input type="hidden" name="current_status" id="confirmForm_current_status" value="{$workflow.currentStatus}" /> {* Для автотеста *}
{if !empty($workflow.confirmData) or !empty($workflow.roles) or !empty($workflow.statusAssignedUsers)
    or !empty($workflow.customView)}
<style>{* TODO: вынести css в файл (и из inline-style) *}
{literal}
.wf_block {
    margin-top: 10px;
}
#status-assigned {
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #abc3d7;
    padding: 5px;
    padding-right: 0px;
}
#status-assigned table {
    border-spacing: 0px;
}
#status-assigned .role {
    background-color: #F6F6F6;
}
#status-assigned td, #status-assigned th {
    padding: 5px 6px 5px 6px;
    font-size: 12px;
}
#status-assigned td {
    border-bottom: 1px solid #CBDAE6;
}
.confirmForm {
    margin-top: 5px;
    border-style: solid;
    border-width: 1px;
    border-color: #abc3d7;
    padding: 5px;
    padding-right: 0px;
}
.confirmForm tr {margin:15px}
.confirmForm td {padding:5px; vertical-align: top;}
.confirmForm select {width:100%}
.confirmForm textarea {width:100%}
.confirmForm textarea#resolution {min-width:240px;}
.confirmForm table {border:none; margin:5px; min-width:400px}
.confirmForm .relate_name {position: relative; top: 1px;}
{/literal}
</style>

{$workflow.include_script}
{include file="custom/include/Workflow/tpls/ToggleButton.tpl"}
<div id="confirm_panel" style="display:none">
  <h4 class="formHeader h3Row" style="padding-top: 8px;margin-bottom: -13px;"></h4>
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
</div>
{/if}
