<link rel="stylesheet" href="{sugar_getjspath file='custom/include/Workflow/css/wf_style.css'}" />
<script src="{sugar_getjspath file='custom/include/Workflow/js/wf_ui.js'}"></script>
<input type="hidden" id="confirm_panel_mode" data-panelmode="{$workflow.panelmode}" />

<div id="confirm-panel-wr" class="detail view">
  {include file="custom/include/Workflow/tpls/ToggleButton.tpl"}
  <div id="confirm_panel">
  {if empty($workflow.panelmode) || $workflow.panelmode == "immediate.closed" || $workflow.panelmode == "immediate.opened"}
    {include file="custom/include/Workflow/tpls/ConfirmPanelBody.tpl"}
  {/if}
  </div>
</div>

{if $workflow.parentView == 'list' || $workflow.panelmode == "immediate.opened" || $workflow.panelmode == "delayed.closed"
    || $workflow.panelmode == "delayed.opened"}
<script type="text/javascript">
SUGAR.util.doWhen('document.readyState == "complete" && typeof lab321 != "undefined" && typeof lab321.wf != "undefined"', function() {ldelim}
    {if $workflow.parentView == "list"}
    lab321.wf.setListViewHandlers();
    {/if}

    {if $workflow.panelmode == "immediate.opened"}
    lab321.wf.togglePanel(true);
    {elseif $workflow.panelmode == "delayed.closed"}
    lab321.wf.loadPanelBody();
    {elseif $workflow.panelmode == "delayed.opened"}
    lab321.wf.loadPanelBody(function(){ldelim}
        lab321.wf.togglePanel(true);
    {rdelim});
    {/if}
{rdelim});
</script>
{/if}
