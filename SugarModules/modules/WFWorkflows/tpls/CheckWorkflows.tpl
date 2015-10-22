{**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.7.17
 *}
{******************************************************************************}
{sugar_translate label='LBL_STATUS_ROLE_FUNCTIONS_CHECK'}...
{if !empty($checkResults.wf_st_roles_conflict)}
<p>{sugar_translate label='LBL_CONFLICTS_FOUND'}:<p>
<table class="list view">
<tr>
    <th>{sugar_translate label='LBL_OBJECT_NAME'}</th>
    <th>{sugar_translate label='LBL_ROLE'}</th>
    <th>{sugar_translate label='LBL_STATUS'}</th>
</tr>
{foreach from=$checkResults.wf_st_roles_conflict item=item}
<tr>
    <td>{$item.workflow_name}</td>
    <td>{$item.role_name}</td>
    <td><a href="index.php?module=WFStatuses&action=DetailView&record={$item.status_id}">{$item.status_name}</a></td>
</tr>
{/foreach}
</table>
<p>{sugar_translate label='LBL_STATUS_ROLE_FUNCTIONS_INFO'}</p>
{else}
OK<br/>
{/if}
<br/>
{******************************************************************************}
{sugar_translate label='LBL_STATUS_UNIQ_CHECK'}...
{if !empty($checkResults.wf_st_uniq)}
<p>{sugar_translate label='LBL_CONFLICTS_FOUND'}:<p>
<table class="list view">
<tr>
    <th>{sugar_translate label='LBL_WF_MODULE'}</th>
    <th>{sugar_translate label='LBL_STATUS'}</th>
</tr>
{foreach from=$checkResults.wf_st_uniq item=item}
<tr>
    <td>{sugar_translate label=$item.wf_module}</td>
    <td><a href="index.php?module=WFStatuses&action=DetailView&record={$item.id}">{$item.name}</a></td>
</tr>
{/foreach}
</table>
<p>{sugar_translate label='LBL_STATUS_UNIQ_INFO'}</p>
{else}
OK<br/>
{/if}
<br/>
{******************************************************************************}
{sugar_translate label='LBL_EVENT_UNIQ_CHECK'}...
{if !empty($checkResults.wf_events_uniq)}
<p>{sugar_translate label='LBL_CONFLICTS_FOUND'}:<p>
<table class="list view">
<tr>
    <th>{sugar_translate label='LBL_OBJECT_NAME'}</th>
    <th>{sugar_translate label='LBL_EVENT'}</th>
</tr>
{foreach from=$checkResults.wf_events_uniq item=item}
<tr>
    <td>{$item.workflow_name}</td>
    <td><a href="index.php?module=WFEvents&action=DetailView&record={$item.id}">{$item.status1_name} - {$item.status2_name}</a></td>
</tr>
{/foreach}
</table>
<p>{sugar_translate label='LBL_EVENT_UNIQ_INFO'}</p>
{else}
OK<br/>
{/if}
<br/>
{******************************************************************************}
{sugar_translate label='LBL_STATUSES_WITHOUT_EVENTS'}...
{if !empty($checkResults.wf_statuses_without_events)}
<table class="list view">
<tr>
    <th>{sugar_translate label='LBL_STATUS'}</th>
</tr>
{foreach from=$checkResults.wf_statuses_without_events item=item}
<tr>
    <td><a href="index.php?module=WFStatuses&action=DetailView&record={$item.id}">{$item.name}</a></td>
</tr>
{/foreach}
</table>
{else}
OK<br/>
{/if}
<br/>
{******************************************************************************}
