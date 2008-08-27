		<div class="row">
			{formlabel label="Address" for="lpi"}
			{forminput}
				{if isset($citizenInfo.sao) && ($citizenInfo.sao <> '') }
					{$citizenInfo.sao},&nbsp;{/if}
				{if isset($citizenInfo.pao) && ($citizenInfo.pao <> '') }
					{$citizenInfo.pao},<br />{/if}
				{if isset($citizenInfo.number) && ($citizenInfo.number <> '') }
					{$citizenInfo.number},<br />{/if}
				{if isset($citizenInfo.street) && ($citizenInfo.street <> '') }
					{$citizenInfo.street},<br />{/if}
				{if isset($citizenInfo.locality) && ($citizenInfo.locality <> '') }
					{$citizenInfo.locality},&nbsp;{/if}
				{if isset($citizenInfo.town) && ($citizenInfo.town <> '') }
					{$citizenInfo.town},&nbsp;{/if}
				{if isset($citizenInfo.county) && ($citizenInfo.county <> '') }
					{$citizenInfo.county},&nbsp;{/if}
				{$citizenInfo.postcode}&nbsp;&nbsp;
			{/forminput}
		</div>
		{if isset($citizenInfo.x_coordinate) && ($citizenInfo.x_coordinate <> '') }
		<div class="row">
			{formlabel label="Visual Centre Coordinates" for="street_start_x"}
			{forminput}
				Easting: {$citizenInfo.x_coordinate|escape} Northing: {$citizenInfo.y_coordinate|escape}
				&nbsp;&lt;<a href="http://www.multimap.com/maps/?map={$citizenInfo.prop_lat},{$citizenInfo.prop_lng}|17|4&loc=GB:{$citizenInfo.prop_lat}:{$citizenInfo.prop_lng}:17" title="{$citizenInfo.title}">
					Multimap
				</a>&gt;<br />
				{$citizenInfo.rpa|escape}
			{/forminput}
		</div>
		{/if}
		