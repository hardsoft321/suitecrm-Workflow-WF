{**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.7.16
 *}

{assign var=idname value="assigned_user_copy"}
<tr class="assigned_copy">
    <td><label>{sugar_translate label='LBL_RECIPIENT_LIST' module='WFWorkflows'}:</label></td>
    <td>

<button type="button" id="notify_recipient_add"
      title="{sugar_translate label="LBL_ID_FF_ADD"}"
      onclick="lab321.wf.cloneRecipientField($(this).closest('td').find('.item_template'), '{$idname}')">
    <img src="{sugar_getimagepath file="id-ff-add.png"}">
</button>

<script type="text/template" class="item_template">
    <input type="text" name="{$idname}[template][name]" value="" autocomplete="off" class="relate_name" readonly="readonly">
    <input type="hidden" name="{$idname}[template][id]" value="">
    <span class="id-ff multiple">
    <button type="button" name="btn_{$idname}[template][name]" class="button firstChild"
      title="{sugar_translate label="LBL_SELECT_BUTTON_TITLE"}"
      onclick='open_popup("Users", 600, 400, "&email_advanced=%", true, false,
        {ldelim}"call_back_function":"set_return","form_name":"{$formName}","field_to_name_array":{ldelim}"id":"{$idname}[template][id]","name":"{$idname}[template][name]"{rdelim}{rdelim},
        "single",true);'><img src="{sugar_getimagepath file="id-ff-select.png"}"></button>
    <button type="button" name="btn_clr_{$idname}[template]" class="button"
      title="{sugar_translate label="LBL_ID_FF_CLEAR"}"
      onclick="SUGAR.clearRelateField(this.form, '{$idname}[template][name]', '{$idname}[template][id]');"><img src="{sugar_getimagepath file="id-ff-clear.png"}"></button>
    <button class="id-ff-remove button lastChild" type="button"
      title="{sugar_translate label="LBL_ID_FF_REMOVE"}"
      onclick="$(this).closest('.editlistitem').remove()"><img src="{sugar_getimagepath file="id-ff-remove-nobg.png"}"></button>
    </span>
</script>

<script type="text/javascript">
SUGAR.util.doWhen("document.readyState == 'complete' && typeof lab321 != 'undefined' && typeof lab321.wf != 'undefined' && typeof lab321.wf.cloneRecipientField != 'undefined'", function() {ldelim}
    lab321.wf.cloneRecipientField($('form[name="{$formName}"] .item_template'), '{$idname}');
{rdelim});
</script>

    </td>
</tr>
