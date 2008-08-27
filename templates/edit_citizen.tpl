{* $Header: /cvsroot/bitweaver/_bit_citizen/templates/edit_citizen.tpl,v 1.1 2008/08/27 16:20:01 lsces Exp $ *}
{popup_init src="`$gBitLoc.THEMES_PKG_URL`overlib.js"}
{strip}
<div class="floaticon">{bithelp}</div>

{* Check to see if there is an editing conflict *}
{if $editpageconflict == 'y'}
	<script language="javascript" type="text/javascript">
		<!-- Hide Script
			alert("{tr}This page is being edited by{/tr} {$semUser}. {tr}Proceed at your own peril{/tr}.")
		//End Hide Script-->
	</script>
{/if}

<div class="admin citizen">

	{if $preview}
		<h2>Preview - {$pageInfo.title}</h2>
		<div class="preview">
			{include file="bitpackage:citizen/citizen_display.tpl" page=`$citizenInfo.content_id`}
		</div>
	{/if}

	<div class="header">
		<h1>
		{if $citizenInfo.content_id}
			{tr}{tr}Edit - {/tr} {$citizenInfo.title}{/tr}
		{else}
			{tr}Create New Record{/tr}
		{/if}
		</h1>
	</div>

	<div class="body">
		{form legend="Edit/Create Citizen Record" enctype="multipart/form-data" id="editpageform"}
			<input type="hidden" name="content_id" value="{$citizenInfo.content_id}" />

			<div class="row">
				{formlabel label="Title" for="title"}
				{forminput}
					<input size="60" type="text" name="prefix" id="prefix" value="{$citizenInfo.prefix|escape}" />
				{/forminput}
			</div>
			<div class="row">
				{formlabel label="Forename" for="forename"}
				{forminput}
					<input size="60" type="text" name="forename" id="forename" value="{$citizenInfo.forename|escape}" />
				{/forminput}
			</div>
			<div class="row">
				{formlabel label="Surname" for="surname"}
				{forminput}
					<input size="60" type="text" name="surname" id="surname" value="{$citizenInfo.surname|escape}" />
				{/forminput}
			</div>
			<div class="row">
				{formlabel label="Suffix" for="suffix"}
				{forminput}
					<input size="60" type="text" name="suffix" id="suffix" value="{$citizenInfo.suffix|escape}" />
				{/forminput}
			</div>
			<div class="row">
				{formlabel label="Organisation" for="organisation"}
				{forminput}
					<input size="60" type="text" name="organisation" id="organisation" value="{$citizenInfo.organisation|escape}" />
				{/forminput}
			</div>
			<div class="row">
				{formlabel label="NI Number" for="nino"}
				{forminput}
					<input size="10" type="text" name="nino" id="nino" value="{$citizenInfo.nino|escape}" />
				{/forminput}
			</div>
			<div class="row">
				{formlabel label="Note" for="description"}
				{forminput}
					<input size="60" type="text" name="description" id="description" value="{$citizenInfo.description|escape}" />
				{/forminput}
			</div>

			<div class="row">
				{formlabel label="Memo" for="$textarea_id"}
				{forminput}
					<input type="hidden" name="rows" value="{$rows}" />
					<input type="hidden" name="cols" value="{$cols}" />
					<textarea id="{$textarea_id}" name="edit" rows="{$rows|default:20}" cols="{$cols|default:80}">{if !$preview}{$citizenInfo.data|escape}{else}{$edit}{/if}</textarea>
				{/forminput}
			</div>

			<div class="row submit">
				<input type="submit" name="preview" value="{tr}Preview{/tr}" /> 
				<input type="submit" name="fSavePage" value="{tr}Save{/tr}" />&nbsp;
				<input type="submit" name="cancel" value="{tr}Cancel{/tr}" />
			</div>
		{/form}

	</div><!-- end .body -->
</div><!-- end .citizen -->

{/strip}

<br />

