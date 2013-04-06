{strip}
<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>
<ul class="{$packageMenuClass}">
	<li><a class="item" href="{$smarty.const.CITIZEN_PKG_URL}list.php">{tr}List Citizens{/tr}</a></li>
	{if $gBitUser->isAdmin() or $gBitUser->hasPermission( 'bit_p_edit_irlist' ) }
		<li><a class="item" href="{$smarty.const.CITIZEN_PKG_URL}edit.php">{booticon iname="icon-file" ipackage="icons" iexplain="create citizen" iforce="icon"} {tr}Create/Edit a Citizen{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission('p_citizen_admin')}
		<li><a class="item" href="{$smarty.const.CITIZEN_PKG_URL}load_golden.php">{tr}Load Citizen Index Dump{/tr}</a></li>
		<li><a class="item" href="{$smarty.const.CITIZEN_PKG_URL}load_golden.php?update=1">{tr}Load Citizen Index Update{/tr}</a></li>
		<li><a class="item" href="{$smarty.const.CITIZEN_PKG_URL}load_history.php">{tr}Load History Dump{/tr}</a></li>
		<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=citizen">{tr}Admin citizens{/tr}</a></li>
	{/if}
</ul>
{/strip}
