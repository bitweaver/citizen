{* $Header$ *}

<div class="floaticon">
{if $gContent->hasAdminPermission()}
  <a href="{$gBitLoc.CITIZEN_PKG_URL}admin/index.php"><img class="icon" src="{$gBitLoc.LIBERTY_PKG_URL}icons/config.gif"  alt="{tr}admin{/tr}" /></a>
{/if}
</div>

<div class="display citizen">
<div class="header">
<h1><a href="{$gBitLoc.CITIZEN_PKG_URL}index.php?view={$view}">{tr}Citizen List{/tr}</a></h1>
</div>
