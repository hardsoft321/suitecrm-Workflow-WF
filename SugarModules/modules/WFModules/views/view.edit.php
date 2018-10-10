<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/views/view.edit.php';
require_once 'modules/WFModules/wfmodule_fields.php';

class WFModulesViewEdit extends ViewEdit
{
    public function display()
    {
        $options = wfmodule_all_typefield_options();
        $jsOptions = json_encode($options);
        $javascript = <<<EOQ
<script type="text/javascript">
$(document).ready(function(){
    var options = $jsOptions;
    $('#wf_module').change(function(){
        var module = $('#wf_module option:selected').val();
        var type_field = $('#type_field').val();
        var html = '';
        for(var key in options[module]) {
            html += '<option value="'+key+'" '+(type_field == key ? 'selected' : '')+'>'+options[module][key]+'</option>';
        }
        $('#type_field').html(html);
    }).change();
});
</script>
EOQ;
        parent::display();
        echo $javascript;
    }
}
