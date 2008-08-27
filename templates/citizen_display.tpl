<div class="body">
	<div class="content">
		{include file="bitpackage:liberty/storage_thumbs.tpl"}
		Title: {$citizenInfo.prefix}<br />
		Forename: {$citizenInfo.forename}<br />
		Surname: {$citizenInfo.surname}<br />
		Suffix: {$citizenInfo.suffix}<br />
		<br />
		Organisation: {$citizenInfo.organisation}<br />
		<br />
		NI Number:{$citizenInfo.nino} Date of Birth:{$citizenInfo.dob} Date of eighteenth:{$citizenInfo.eighteenth} Date of Death:{$citizenInfo.dod} <br />
		<br />
		Note: {$citizenInfo.note}<br />
		Memo:<br />
		{$citizenInfo.data}<br />
	</div><!-- end .content -->
</div><!-- end .body -->
