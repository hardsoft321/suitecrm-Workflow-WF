{if !empty($workflow.statusAssignedUsers)}

<div id="log_block" class="wf_block">
  <div id='status-assigned' class="wf_block_body">
    {foreach from=$workflow.statusAssignedUsers item="sa"}
    <div class="col-xs-12">
      <div class="col-xs-12 col-sm-4 label">
        <label class="role">{$sa.role_name}:</label>
      </div>
      <div class="col-xs-12 col-sm-8 edit-view-field " type="text" field="status">
        <span class="user_name">{$sa.first_name} {$sa.last_name}</span>
      </div>
    </div>
    {/foreach}
  </div>
</div>

{/if}
