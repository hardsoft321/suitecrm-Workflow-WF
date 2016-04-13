<div id="confirm_panel_title">
<a href="#" id="confirm_panel_title_btn" onclick="lab321.wf.togglePanel();return false;">
<span>
<h4>
<input type='hidden' name='record' value='{$workflow.record}'>
<input type='hidden' name='module' value='{$workflow.module}'>
<img src="{sugar_getimagepath file="basic_search.gif"}" id="confirm_panel_toggle_img_opened"
    name="confirm_panel_toggle_img" border="0" />
<img src="{sugar_getimagepath file="advanced_search.gif"}" id="confirm_panel_toggle_img_closed"
    name="confirm_panel_toggle_img" border="0" />
    {sugar_translate label='LBL_TOGGLE_BUTTON' module='WFWorkflows'}
</h4>
</span>
</a>
</div>
<div id="confirm_panel_dummy" onclick="lab321.wf.togglePanel();return false;" title="{sugar_translate label='LBL_EXPAND' module='WFWorkflows'}">
    <div class="dots"></div>
</div>
