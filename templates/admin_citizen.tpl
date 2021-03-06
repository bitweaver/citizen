{strip}
{form legend="Citizen List Features"}
	<input type="hidden" name="page" value="{$page}" />

	{foreach from=$formCitizenListFeatures key=item item=output}
		<div class="form-group">
			{formlabel label=$output.label for=$item}
			{forminput}
				{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
			{/forminput}
			{formhelp note=$output.help page=$output.page}
		</div>
	{/foreach}

	<div class="form-group submit">
		<input type="submit" class="btn btn-default" name="citizenlistfeatures" value="{tr}Change preferences{/tr}" />
	</div>
{/form}

{/strip}
