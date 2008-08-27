<div class="header">
{if $is_categorized eq 'y' and $gBitSystem->isFeatureActive('package_categories') and $gBitSystem->isFeatureActive('feature_categorypath')}
<div class="category">
  <div class="path">{$display_catpath}</div>
</div> {* end category *}
{/if}

	<h1>{$citizenInfo.content_id}&nbsp;-&nbsp;
		{if isset($citizenInfo.organisation) && ($citizenInfo.organisation <> '') }
		{$citizenInfo.organisation}
		{else}
		{$citizenInfo.prefix}&nbsp;
		{$citizenInfo.forename}&nbsp;
		{$citizenInfo.surname}
		{/if}</h1>
	<div class="description">{$citizenInfo.description}</div>

</div> {* end .header *}
