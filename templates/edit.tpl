{* $Header$ *}
<div class="floaticon">{bithelp}</div>

{assign var=serviceEditTpls value=$gLibertySystem->getServiceValues('content_edit_tpl')}

<div class="admin citizen">
	<div class="header">
		<h1>
		{* this weird dual assign thing is cause smarty wont interpret backticks to object in assign tag - spiderr *}
		{assign var=conDescr value=$gContent->getContentTypeName()}
		{if $citizenInfo.content_id}
			{assign var=editLabel value="{tr}Edit{/tr} $conDescr"}
			{tr}{tr}Edit{/tr} {$citizenInfo.title}{/tr}
		{else}
			{assign var=editLabel value="{tr}Create{/tr} $conDescr"}
			{tr}{$editLabel}{/tr}
		{/if}
		</h1>
	</div>

	{* Check to see if there is an editing conflict *}
	{if $errors.edit_conflict}
		<script language="javascript" type="text/javascript">
			<!--
				alert( "{$errors.edit_conflict|strip_tags}" );
			-->
		</script>
		{formfeedback warning=$errors.edit_conflict}
	{/if}

	{strip}
	<div class="body">
		{form enctype="multipart/form-data" id="editpageform"}
			{jstabs}
				{jstab title="$editLabel Body"}
					{legend legend="`$editLabel` Body"}
						<input type="hidden" name="content_id" value="{$citizenInfo.content_id}" />
						
						<div class="control-group">
							{formfeedback warning=$errors.names}
							{formfeedback warning=$errors.store}

							{formlabel label="$conDescr Citizen" for="contentno"}
							{if !$citizenInfo.usn}
								{forminput}
									New Citizen Entry
								{/forminput}
							{else}
								{forminput}
									Edit Citizen Entry No : {$citizenInfo.usn}
								{/forminput}
							{/if}

							{formlabel label="Citizen Type" for="citizen_type"}
							{forminput}
								<input type="text" size="24" maxlength="24" name="citizen_type" id="project_name" value="{$citizenInfo.citizen_type}" />
							{/forminput}
							
						</div>
						<div class="control-group">
							{formlabel label="Title" for="prefix"}
							{forminput}
								<input size="60" type="text" name="prefix" id="prefix" value="{$citizenInfo.prefix|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="Forename" for="forename"}
							{forminput}
								<input size="60" type="text" name="forename" id="forename" value="{$citizenInfo.forename|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="Surname" for="surname"}
							{forminput}
								<input size="60" type="text" name="surname" id="surname" value="{$citizenInfo.surname|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="Suffix" for="suffix"}
							{forminput}
								<input size="60" type="text" name="suffix" id="suffix" value="{$citizenInfo.suffix|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="Organisation" for="organisation"}
							{forminput}
								<input size="60" type="text" name="organisation" id="organisation" value="{$citizenInfo.organisation|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="NI Number" for="nino"}
							{forminput}
								<input size="10" type="text" name="nino" id="nino" value="{$citizenInfo.nino|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="Date of Birth" for="dob"}
							{forminput}
								<input size="10" type="text" name="dob" id="dob" value="{$citizenInfo.dob|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="Date of eighteen" for="eighteenth"}
							{forminput}
								<input size="10" type="text" name="eighteenth" id="eighteenth" value="{$citizenInfo.eighteenth|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="Date of Death" for="dod"}
							{forminput}
								<input size="10" type="text" name="dod" id="dod" value="{$citizenInfo.dod|escape}" />
							{/forminput}
						</div>
						<div class="control-group">
							{formlabel label="Note" for="description"}
							{forminput}
								<input size="60" type="text" name="description" id="description" value="{$citizenInfo.description|escape}" />
							{/forminput}
						</div>
					{/legend}
				{/jstab}

				{jstab title="Citizen Notes"}
					{legend legend="Notes Body"}
						{if $gBitSystem->isPackageActive( 'quicktags' )}
							{include file="bitpackage:quicktags/quicktags_full.tpl"}
						{/if}

						<div class="control-group">
							{forminput}
								<textarea id="{$textarea_id}" name="edit" rows="{$rows|default:20}" cols="{$cols|default:80}">{$citizenInfo.data|escape:html}</textarea>
							{/forminput}
						</div>

						{if $page ne 'SandBox'}
							<div class="control-group">
								{formlabel label="Comment" for="comment"}
								{forminput}
									<input size="50" type="text" name="comment" id="comment" value="{$citizenInfo.comment}" />
									{formhelp note="Add a comment to illustrate your most recent changes."}
								{/forminput}
							</div>
						{/if}

					{/legend}
				{/jstab}

				{jstab title="Liberty Extensions"}
					{if $serviceEditTpls.categorization }
						{legend legend="Categorize"}
							{include file=$serviceEditTpls.categorization}
						{/legend}
					{/if}
				{/jstab}
			{/jstabs}

			{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_mini_tpl"}

			<div class="control-group submit">
				<input type="submit" class="btn" name="fCancel" value="{tr}Cancel{/tr}" />&nbsp;
				<input type="submit" class="btn" name="fSaveCitizen" value="{tr}Save{/tr}" />
			</div>
		{/form}

	</div><!-- end .body -->
</div><!-- end .admin -->

{/strip}
