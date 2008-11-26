<div class="body">
	<div class="content">

		{if isset($citizenInfo.usn) && ($citizenInfo.usn <> '') }
		<div class="row">
			{formlabel label="USN" for="usn"}
			{forminput}
				{$citizenInfo.usn|escape} 
			{/forminput}
		</div>
		{/if}
		{if isset($citizenInfo.organisation) && ($citizenInfo.organisation <> '') }
		<div class="row">
			{formlabel label="Organisation" for="organisation"}
			{forminput}
				{$citizenInfo.organisation|escape} 
			{/forminput}
		</div>
		{/if}
		{if isset($citizenInfo.dob) && ($citizenInfo.dob <> '') }
		<div class="row">
			{formlabel label="Date of Birth" for="dob"}
			{forminput}
				{$citizenInfo.dob|bit_long_date}
			{/forminput}
		</div>
		{/if}
		{if isset($citizenInfo.nino) && ($citizenInfo.nino <> '') }
		<div class="row">
			{formlabel label="National Insurance Number" for="nino"}
			{forminput}
				{$citizenInfo.nino|escape}
			{/forminput}
		</div>
		{/if}
		{include file="bitpackage:citizen/display_address.tpl"}
		{jstabs}
			{include file="bitpackage:citizen/list_xref.tpl"}
			{include file="bitpackage:citizen/list_ticket.tpl"}
			{include file="bitpackage:citizen/list_appoint.tpl"}
		{/jstabs}
	</div><!-- end .content -->
</div><!-- end .body -->
