{**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 *}
{literal}
<style>
td pre {white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word;}
td.usages ul {margin: 0; padding-left: 10px;}
td.usages ul li {list-style-type: inherit;}
</style>
{/literal}
{******************************************************************************}
<h3>{sugar_translate label='LBL_FILTER_FUNCTION' module='WFEvents'}</h3>
{if !empty($functionsDoc.filters)}
<table class="list view">
<tr>
    <th>{sugar_translate label='LBL_NAME'}</th>
    <th>{sugar_translate label='LBL_DESCRIPTION'}</th>
    <th>{sugar_translate label='LBL_USAGES'}</th>
</tr>
{foreach from=$functionsDoc.filters item=item}
<tr class="{cycle values='oddListRowS1,evenListRowS1'}">
    <td><span title="{$item.classname}">{$item.name}</span></td>
    <td><pre>{$item.description}</pre></td>
    <td class="usages">
        <ul>
        {foreach from=$item.usages item=usageitem}
            <li>{$usageitem.wf_name}: <a href="index.php?module=WFEvents&action=DetailView&record={$usageitem.event_id}">{$usageitem.status1_name} &rarr; {$usageitem.status2_name}</a></li>
        {/foreach}
        </ul>
    </td>
</tr>
{/foreach}
</table>
{/if}
<br/>
{******************************************************************************}
<h3>{sugar_translate label='LBL_VALIDATE_FUNCTION' module='WFEvents'}</h3>
{if !empty($functionsDoc.validators)}
<table class="list view">
<tr>
    <th>{sugar_translate label='LBL_NAME'}</th>
    <th>{sugar_translate label='LBL_DESCRIPTION'}</th>
    <th>{sugar_translate label='LBL_USAGES'}</th>
</tr>
{foreach from=$functionsDoc.validators item=item}
<tr class="{cycle values='oddListRowS1,evenListRowS1'}">
    <td><span title="{$item.classname}">{$item.name}</span></td>
    <td><pre>{$item.description}</pre></td>
    <td class="usages">
        <ul>
        {foreach from=$item.usages item=usageitem}
            <li>{$usageitem.wf_name}: <a href="index.php?module=WFEvents&action=DetailView&record={$usageitem.event_id}">{$usageitem.status1_name} &rarr; {$usageitem.status2_name}</a></li>
        {/foreach}
        </ul>
    </td>
</tr>
{/foreach}
</table>
{/if}
<br/>
{******************************************************************************}
<h3>{sugar_translate label='LBL_AFTER_SAVE' module='WFEvents'}</h3>
{if !empty($functionsDoc.procedures)}
<table class="list view">
<tr>
    <th>{sugar_translate label='LBL_NAME'}</th>
    <th>{sugar_translate label='LBL_DESCRIPTION'}</th>
    <th>{sugar_translate label='LBL_USAGES'}</th>
</tr>
{foreach from=$functionsDoc.procedures item=item}
<tr class="{cycle values='oddListRowS1,evenListRowS1'}">
    <td><span title="{$item.classname}">{$item.name}</span></td>
    <td><pre>{$item.description}</pre></td>
    <td class="usages">
        <ul>
        {foreach from=$item.usages item=usageitem}
            <li>{$usageitem.wf_name}: <a href="index.php?module=WFEvents&action=DetailView&record={$usageitem.event_id}">{$usageitem.status1_name} &rarr; {$usageitem.status2_name}</a></li>
        {/foreach}
        </ul>
    </td>
</tr>
{/foreach}
</table>
{/if}
<br/>
{******************************************************************************}
<h3>{sugar_translate label='LBL_USER_LISTS_FUNCTIONS'}</h3>
{if !empty($functionsDoc.userlists)}
<table class="list view">
<tr>
    <th>{sugar_translate label='LBL_NAME'}</th>
    <th>{sugar_translate label='LBL_DESCRIPTION'}</th>
    <th>{sugar_translate label='LBL_USAGES'}</th>
</tr>
{foreach from=$functionsDoc.userlists item=item}
<tr class="{cycle values='oddListRowS1,evenListRowS1'}">
    <td><span title="{$item.classname}">{$item.name}</span></td>
    <td><pre>{$item.description}</pre></td>
    <td class="usages">
        <ul>
        {foreach from=$item.usages item=usageitem}
            <li>{$usageitem.field}: <a href="index.php?module=WFStatuses&action=DetailView&record={$usageitem.id}">{$usageitem.name}</a></li>
        {/foreach}
        </ul>
    </td>
</tr>
{/foreach}
</table>
{/if}
<br/>
{******************************************************************************}
