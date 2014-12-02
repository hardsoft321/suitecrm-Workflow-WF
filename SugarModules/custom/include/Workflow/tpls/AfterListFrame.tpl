{include file="custom/include/Workflow/tpls/ConfirmPanel.tpl"}
<script type="text/javascript">
{literal}
SUGAR.util.doWhen('document.readyState == "complete" && typeof lab321 != "undefined" && typeof lab321.wf != "undefined"', function() {
    lab321.wf.setListViewHandlers();
});
{/literal}
</script>
