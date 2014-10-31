<style>{* TODO: вынести css в файл (и из inline-style) *}
{literal}
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
{/literal}
</style>
{$workflow.include_script}
{include file="custom/include/Workflow/tpls/ToggleButton.tpl"}
<div id="confirm_panel" style="display:none">
  {if !empty($workflow.confirmData)}
    {include file="custom/include/Workflow/tpls/ConfirmBlock.tpl"}
  {/if}
  {if !empty($workflow.roles)}
    {include file="custom/include/Workflow/tpls/AssignBlock.tpl"}
  {/if}
  {if !empty($workflow.statusAssignedUsers)}
    {include file="custom/include/Workflow/tpls/LogBlock.tpl"}
  {/if}  
</div>
