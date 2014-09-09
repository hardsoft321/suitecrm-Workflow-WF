<!--
<script type="text/javascript" src="custom/include/Workflow/js/wf_panels.js"></script>
<script type="text/javascript" src="custom/include/Workflow/js/wf_confirm_panel.js"></script>
-->
<script>

  YAHOO.util.Event.onDOMReady(function () {ldelim}
  wf_executers = [];
  {assign var="firstStatus" value=""}
  {foreach name="l" from=$executers key="status" item="users"}
    {if $smarty.foreach.l.first} {assign var="firstStatus" value=$status} {/if}
    {append var=allExecuters key=$status value=$desc}
    wf_executers["{$status}"] =
        [ {foreach name="l" from=$users key="k" item="u"}
              ["{$k}", "{$u}"] {if $smarty.foreach.l.last}{else},{/if}
        {/foreach} ];
  {/foreach}
  wf_errors = [];
  {foreach name="l" from=$errors key="status" item="es"}
    wf_errors["{$status}"] =
        [ {foreach name="e" from=$es key="k" item="u"}
              "{$u}" {if $smarty.foreach.e.last}{else},{/if} /* FIXME надо экранировать $u */
        {/foreach} ];
  {/foreach}
  
  {literal}
    addToValidate('confirm', 'resolution', null, true, 'Резолюция');
    $('#confirm input[name="submit"]').click(function() {
      var _form = document.getElementById('confirm'); if(check_form('confirm'))SUGAR.ajaxUI.submitForm(_form);return false;
    });
  {/literal}
  
  {rdelim});
{literal}
// TODO название формы и полей
function wf_onchange_new_status () {
  var statusSel = document.getElementById('newStatus');
  var disable = true;
  if (statusSel.length > 0) {
    var status = statusSel[statusSel.selectedIndex].value;
/*
    var userSel = document.confirm.executer;
    userSel.options.length = 0;
    if (status != "" && wf_executers[status] !== undefined && wf_executers[status].length > 0) {
      disable = false;

      for (i = 0; i < wf_executers[status].length; i++) 
         userSel.options[i] = new Option(wf_executers[status][i][1], wf_executers[status][i][0]);
    }
*/

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
<div id="confirm_panel" style="display: none;
                margin-top: 5px;
                border-style: solid;
                border-width: 1px;
                border-color: #abc3d7;
                padding: 5px;
                padding-right: 0px;">

  <form id='confirm' name='confirm' action='index.php?entryPoint=wf_confirm' method='POST'>
    <input type='hidden' id='record' name='record' value='{$fields.id.value}'> 
    <input type='hidden' id='action' name='action' value='confirm'>
    <input type='hidden' id='module' name='module' value='{$module}'>
    <input type='hidden' id='wf_type' name='wf_type' value='{$fields.wf_type.value}'>

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
       <td style="padding:5px">{html_options name=status options=$newStatuses id=newStatus style="width:100%"
                                             onchange="wf_onchange_new_status();"}</td> 
      </tr>
<!--
      <tr margin="15">
        <td style="padding:5px"><label for="executer">Исполнитель:</label></td>
       <td style="padding:5px">{html_options name=executer options=$executers[$firstStatus] id=executer style="width:100%"}</td> 
      </tr>
-->
      <tr margin="15">
      <td style="padding:5px"></td>
      <td style="padding:5px"><input type='submit' name='submit' value='Изменить'></td>
      </tr>
    </table>
  </form>
  <div id="workflow_errors" class="required validation_message"></div>
</div>
<script>
  {literal}
  YAHOO.util.Event.onDOMReady(function(){ wf_onchange_new_status(); });
  {/literal}
</script>
