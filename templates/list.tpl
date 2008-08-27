{strip}

<div class="floaticon">{bithelp}</div>

<div class="listing citizens">
	<div class="header">
		<h1>{tr}Citizens{/tr}</h1>
	</div>

	<div class="body">

		{include file="bitpackage:citizen/display_list_header.tpl"}

		<div class="navbar">
			<ul>
				<li>{biticon ipackage="icons" iname="emblem-symbolic-link" iexplain="sort by"}</li>
		{*		<li>{smartlink ititle="Citizen Number" isort="content_id" idefault=1 iorder=desc ihash=$listInfo.ihash}</li>		
				<li>{smartlink ititle="Forename" isort="forename" ihash=$listInfo.ihash}</li>		*}
				<li>{smartlink ititle="Surname" isort="surname" ihash=$listInfo.ihash}</li>
				<li>{smartlink ititle="Organisation" isort="organisation" ihash=$listInfo.ihash}</li>
				<li>{smartlink ititle="Address" isort="street" ihash=$listInfo.ihash}</li>
				<li>{smartlink ititle="Town" isort="town" ihash=$listInfo.ihash}</li>
				<li>{smartlink ititle="Postcode" isort="postcode" ihash=$listInfo.ihash}</li>
			</ul>
		</div>

		<ul class="clear data">
			{section name=content loop=$listcitizens}
				<li class="item {cycle values='odd,even'}">
						<a href="display_citizen.php?content_id={$listcitizens[content].content_id}" title="ci_{$listcitizens[content].content_id}">
						{$listcitizens[content].content_id}&nbsp;-&nbsp;
						{$listcitizens[content].prefix}&nbsp;
						{$listcitizens[content].forename}&nbsp;
						{$listcitizens[content].surname} 
						</a>&nbsp;&nbsp;&nbsp;
						{if isset($listcitizens[content].organisation) && ($listcitizens[content].organisation <> '') }Company: {$listcitizens[content].organisation}&nbsp;&nbsp;{/if} 
						{if isset($listcitizens[content].dob) && ($listcitizens[content].dob <> '')  }DOB: {$listcitizens[content].dob}&nbsp;&nbsp;{/if}
						{if isset($listcitizens[content].nino) && ($listcitizens[content].nino <> '') }NI: {$listcitizens[content].nino}&nbsp;&nbsp;{/if}
						
					<div class="footer">
						{if isset($listcitizens[content].uprn) && ($listcitizens[content].uprn <> '') }UPRN: {$listcitizens[content].uprn}&nbsp;&nbsp;{/if}
						{tr}Address{/tr}: 
						{if isset($listcitizens[content].sao) && ($listcitizens[content].sao <> '') }
							{$listcitizens[content].sao},&nbsp;{/if}
						{if isset($listcitizens[content].pao) && ($listcitizens[content].pao <> '') }
							{$listcitizens[content].pao},&nbsp;{/if}
						{if isset($listcitizens[content].number) && ($listcitizens[content].number <> '') }
							{$listcitizens[content].number},&nbsp;{/if}
						{if isset($listcitizens[content].street) && ($listcitizens[content].street <> '') }
							{$listcitizens[content].street},&nbsp;{/if}
						{if isset($listcitizens[content].locality) && ($listcitizens[content].locality <> '') }
							{$listcitizens[content].locality},&nbsp;{/if}
						{if isset($listcitizens[content].town) && ($listcitizens[content].town <> '') }
							{$listcitizens[content].town},&nbsp;{/if}
						{if isset($listcitizens[content].county) && ($listcitizens[content].county <> '') }
							{$listcitizens[content].county},&nbsp;{/if}
						{$listcitizens[content].postcode}&nbsp;&nbsp;
						{tr}Links{/tr}: {$listcitizens[content].links|default:0}
						{tr}Enquiries{/tr}: {$listcitizens[content].enquiries|default:0}
					</div>

					<div class="clear"></div>
				</li>
			{sectionelse}
				<li class="item norecords">
					{tr}No records found{/tr}
				</li>
			{/section}
		</ul>

		{pagination}
	</div><!-- end .body -->
</div><!-- end .irlist -->

{/strip}
