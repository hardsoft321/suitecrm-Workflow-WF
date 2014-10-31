<div id="log_block">
  <h4>Ответственные</h4>
  <div id='status-assigned' style="margin-top: 5px; margin-bottom: 15px; border: 1px solid #abc3d7; padding: 5px; padding-right: 0px;">
      <table>
        <tr><th>Роль</th><th>Ответственный</th></tr>
        {foreach from=$workflow.statusAssignedUsers item="sa"}
          <tr><td class="role">{$sa.role_name}</td><td class="user_name">{$sa.first_name} {$sa.last_name}</td></tr>
        {/foreach}
      </table>
  </div>
</div>