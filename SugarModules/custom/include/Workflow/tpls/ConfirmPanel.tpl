<script>

  YAHOO.util.Event.onDOMReady(function () {ldelim}
  
  wf_errors = [];
  {foreach name="l" from=$errors key="status" item="es"}
    wf_errors["{$status}"] =
        [ {foreach name="e" from=$es key="k" item="u"}
              "{$u}" {if $smarty.foreach.e.last}{else},{/if} /* FIXME надо экранировать $u */
        {/foreach} ];
  {/foreach}

  wf_assignedUsers = {$workflow.assignedUsersString};
  {literal}
    addToValidate('confirm', 'resolution', null, true, 'Резолюция');
    addToValidate('confirm', 'assigned_user', null, true, 'Ответственный');
    $('#confirm input[name="submit"]').click(function() {
      var _form = document.getElementById('confirm'); return check_form('confirm');
    });
    
    wf_onchange_new_status();
  {/literal}
  
  {rdelim});
{literal}
// TODO название формы и полей
function wf_onchange_new_status () {
  var statusSel = document.getElementById('newStatus');
  var disable = true;
  if (statusSel.length > 0) {
    var status = statusSel[statusSel.selectedIndex].value;

    var userSel = document.confirm.assigned_user;
    userSel.options.length = 0;
    if (status != "" && wf_assignedUsers[status] !== undefined && wf_assignedUsers[status].length > 0) {
      disable = false;

      for (i = 0; i < wf_assignedUsers[status].length; i++) 
         userSel.options[i] = new Option(wf_assignedUsers[status][i][1], wf_assignedUsers[status][i][0]);
    }

    if (status != "" && wf_errors[status] !== undefined && wf_errors[status].length > 0) {
      var error_list = "<ul>"
      for (i = 0; i < wf_errors[status].length; i++) error_list += "<li>" + wf_errors[status][i] + "</li>";
      error_list += "</ul>";
      document.getElementById('workflow_errors').innerHTML = error_list;
    }
    else {
      document.getElementById('workflow_errors').innerHTML = '';
      disable = false;
    }

  }

  document.confirm.submit.disabled = disable;

  
}

function wf_toggle_panel (id) {
  var panel = document.getElementById(id);
  if (panel.style.display == 'none') {
    panel.style.display = 'block';
    document.getElementById(id + "_toggle_img").src='themes/default/images/basic_search.gif';
  } else {
    panel.style.display = 'none';
    document.getElementById(id + "_toggle_img").src='themes/default/images/advanced_search.gif';
  }
}

{/literal}
</script>

<span id="confirm_panel_title" style="display:inline-block;">
  <a style="color: #0b578f; text-decoration: none;" href="javascript: wf_toggle_panel('confirm_panel');">
    <span style="text-decoration: underline;">
      Панель согласования
      <img src="themes/default/images/advanced_search.gif" id="confirm_panel_toggle_img" 
           name="confirm_panel_toggle_img" border="0"
           onclick="wf_togle_panel('confirm_panel');"/>
  </a>
</span>

<div id="confirm_panel" style="display:none">
                
  {if !empty($workflow.newStatuses)}  
  <form id='confirm' name='confirm' action='index.php?entryPoint=wf_confirm' method='POST' 
         style="margin-top: 5px;
                border-style: solid;
                border-width: 1px;
                border-color: #abc3d7;
                padding: 5px;
                padding-right: 0px;">
    <input type='hidden' id='record' name='record' value='{$fields.id.value}'> 
    <input type='hidden' id='module' name='module' value='{$module}'>

    <input type='hidden' id='return_module' name='return_module' value = '{$return_module}'>
    <input type='hidden' id='return_action' name='return_action' value = '{$return_action}'>
    <input type='hidden' id='return_record' name='return_record' value = '{$return_record}'>

    <table border="0" margin="5" style="min-width:400px">
      <tr margin="15">
        <td style="padding:5px"><label for="resolution">Резолюция:</label><span class="required">*</span></td>
       <td style="padding:5px"><textarea name="resolution" id="resolution" style="width:100%"></textarea></td> 
      </tr>
      <tr margin="15">
        <td style="padding:5px"><label for="status">Новый статус:</label><span class="required">*</span></td>
       <td style="padding:5px">{html_options name=status options=$workflow.newStatuses id=newStatus style="width:100%"
                                             onchange="wf_onchange_new_status();"}</td> 
      </tr>

      <tr margin="15">
        <td style="padding:5px"><label for="assigned_user">Ответственный:</label><span class="required">*</span></td>
       <td style="padding:5px">{html_options name=assigned_user options="" id=assigned_user style="width:100%"}</td> 
      </tr>

      <tr margin="15">
      <td style="padding:5px"></td>
      <td style="padding:5px"><input type='submit' name='submit' value='Изменить'></td>
      </tr>
    </table>
  </form>
  {/if}
  
  {if !empty($workflow.confirmUsers)}
  <form id='assign' name='assign' action='index.php?entryPoint=wf_assign' method='POST'
         style="margin-top: 5px;
                border-style: solid;
                border-width: 1px;
                border-color: #abc3d7;
                padding: 5px;
                padding-right: 0px;">
    <input type='hidden' id='record' name='record' value='{$fields.id.value}'> 
    <input type='hidden' id='module' name='module' value='{$module}'>

    <input type='hidden' id='return_module' name='return_module' value = '{$return_module}'>
    <input type='hidden' id='return_action' name='return_action' value = '{$return_action}'>
    <input type='hidden' id='return_record' name='return_record' value = '{$return_record}'>

    <table border="0" margin="5" style="min-width:400px">
      <tr margin="15">
        <td style="padding:5px"><label for="status">Новый ответственный:</label><span class="required">*</span></td>
        <td style="padding:5px">{html_options name=new_assign_user options=$workflow.confirmUsers id=new_assign_user style="width:100%"}</td> 
      </tr>
      <tr margin="15">
        <td style="padding:5px"></td>
        <td style="padding:5px"><input type='submit' name='submit' value='Изменить'></td>
      </tr>
    </table>
  </form>
  {/if}
  <div id="workflow_errors" class="required validation_message"></div>
</div>
