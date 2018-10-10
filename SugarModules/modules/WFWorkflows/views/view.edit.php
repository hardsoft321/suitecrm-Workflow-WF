<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.edit.php');
require_once('modules/WFWorkflows/wfworkflow_fields.php');

class WFWorkflowsViewEdit extends ViewEdit {

    function display() {
        global $db;
        global $app_list_strings;

        if (!empty($this->bean->bean_type)) {
            $this->bean->bean_type = str_replace('^^', '^'.WF_EMPTY_BEANTYPE_VALUE.'^', $this->bean->bean_type);
        }
        $q = "SELECT wf_module, type_field FROM wf_modules WHERE deleted = 0 ORDER BY wf_module";
        $qr = $db->query($q);
        $options = array();
        $fields = array();
        while ($row = $db->fetchByAssoc($qr)) {
            $options[$row['wf_module']] = wfworkflow_module_beantypes($row['wf_module'], $row['type_field']);
            $fields[$row['wf_module']] = wfworkflow_module_statusfields($row['wf_module'], $row['type_field']);
        }
        $jsOptions = json_encode($options);
        $jsFields = json_encode($fields);
        $javascript = <<<EOQ
<script type="text/javascript">
$(document).ready(function(){
    var options = $jsOptions;
    var fields = $jsFields;
    $('#wf_module').change(function(){
        var module = $('#wf_module option:selected').val();
        var html = '';
        var bean_type = $('#bean_type').val();
        for(var key in options[module]) {
            html += '<option value="'+key+'" '+(bean_type && bean_type.indexOf(key) != -1 ? 'selected' : '')+'>'+options[module][key]+'</option>';
        }
        $('#bean_type').html(html);

        var htmlF = '';
        var status_field = $('#status_field').val();
        for(var key in fields[module]) {
            htmlF += '<option value="'+key+'" '+(status_field == key ? 'selected' : '')+'>'+fields[module][key]+'</option>';
        }
        $('#status_field').html(htmlF);
    }).change();
});
</script>
EOQ;
        parent::display();
        echo $javascript;
    }
}
