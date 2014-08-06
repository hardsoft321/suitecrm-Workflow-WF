<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.edit.php');

class WFWorkflowsViewEdit extends ViewEdit {

	function display() {
        global $db;
        global $app_list_strings;
        
        $q = "SELECT wf_module, type_field FROM wf_modules WHERE deleted = 0";
        $qr = $db->query($q);
        $options = array();
        $fields = array();
        while ($row = $db->fetchByAssoc($qr)) {
            $bean = BeanFactory::newBean($row['wf_module']);
            
            // Список значений типов
            if(isset($bean->field_defs[$row['type_field']]['options'])) {
                $optionsKey = $bean->field_defs[$row['type_field']]['options'];
                $options[$row['wf_module']] = $app_list_strings[$optionsKey];
            }
            
            // Список полей
            $fields[$row['wf_module']] = array();
            foreach($bean->field_defs as $field => $def) {
                if($def['type'] == 'enum')
                    $fields[$row['wf_module']][$field] = $field;
            }
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
        for(var key in options[module]) {
            html += '<option value="'+key+'">'+options[module][key]+'</option>';
        }
        $('#bean_type').html(html);
        
        var htmlF = '';
        for(var key in fields[module]) {
            htmlF += '<option value="'+key+'">'+fields[module][key]+'</option>';
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
