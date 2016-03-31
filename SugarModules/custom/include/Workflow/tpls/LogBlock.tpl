<div id="log_block" class="wf_block">
  <h4>{sugar_translate label='LBL_ASSIGNEDS' module='WFWorkflows'}</h4>
  <div id='status-assigned'>
      <table>
        <tr><th>{sugar_translate label='LBL_ROLE' module='WFWorkflows'}</th><th>{sugar_translate label='LBL_ASSIGNED' module='WFWorkflows'}</th></tr>
        {foreach from=$workflow.statusAssignedUsers item="sa"}
          <tr><td class="role">{$sa.role_name}</td><td class="user_name">{$sa.first_name} {$sa.last_name}</td></tr>
        {/foreach}
      </table>
  </div>
</div>