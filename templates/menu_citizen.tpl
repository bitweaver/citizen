{strip}
<ul>
	<li><a class="item" href="{$smarty.const.CITIZEN_PKG_URL}list.php">{tr}List Citizens{/tr}</a></li>
	{if $gBitUser->isAdmin() or $gBitUser->hasPermission( 'bit_p_edit_irlist' ) }
		<li><a class="item" href="{$smarty.const.CITIZEN_PKG_URL}edit.php">{biticon ipackage="icons" iname="document-new" iexplain="create citizen" iforce="icon"} {tr}Create/Edit a Citizen{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission('p_citizen_admin')}
		<li><a class="item" href="{$smarty.const.KERNEL_PKG_URL}admin/index.php?page=citizen">{tr}Admin citizens{/tr}</a></li>
	{/if}
</ul>
{/strip}