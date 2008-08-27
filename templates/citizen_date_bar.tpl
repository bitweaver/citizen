<div class="floaticon">
  {if $lock}
    {biticon ipackage="icons" iname="locked" iexplain="locked"}{$info.editor|userlink}
  {/if}
  {if $print_page ne 'y'}
    {if !$lock}
      {if $gBitUser->hasPermission('p_edit_citizen')}
		<a href="edit.php?content_id={$citizenInfo.content_id}" {if $beingEdited eq 'y'}{popup_init src="`$gBitLoc.THEMES_PKG_URL`overlib.js"}{popup text="$semUser" width="-1"}{/if}>{biticon ipackage="icons" iname="accessories-text-editor" iexplain="edit"}</a>
      {/if}
    {/if}
    <a title="{tr}print{/tr}" href="print.php?content_id={$citizenInfo.content_id}">{biticon ipackage="icons" iname="document-print" iexplain="print"}</a>
      {if $gBitUser->hasPermission('p_remove_citizen')}
        <a title="{tr}remove this citizen{/tr}" href="remove_citizen.php?content_id={$citizenInfo.content_id}">{biticon ipackage="icons" iname="edit-delete" iexplain="delete"}</a>
      {/if}
  {/if} {* end print_page *}
</div> {*end .floaticon *}
<div class="date">
	{tr}Created by{/tr} {displayname user=$citizenInfo.creator_user user_id=$citizenInfo.user_id real_name=$citizenInfo.creator_real_name}, {tr}Last modification by{/tr} {displayname user=$citizenInfo.modifier_user user_id=$citizenInfo.modifier_user_id real_name=$citizenInfo.modifier_real_name} on {$citizenInfo.last_modified|bit_long_datetime}
</div>
