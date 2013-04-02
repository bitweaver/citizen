
		{assign var=xrefcnt value=$citizenInfo.xref|@count}
		{jstab title="Cross reference ($xrefcnt)"}
		{legend legend="Imported References"}
		<div class="control-group">
			{formlabel label="Cross reference" for="xref"}
			{forminput}
			<table>
				<caption>{tr}List of linked references{/tr}</caption>
				<thead>
					<tr>
						<th>Data</th>
						<th>Application</th>
						<th>USN</th>
						<th>Reference</th>
					</tr>
				</thead>
				<tbody>
					{section name=xref loop=$citizenInfo.xref}
						<tr class="{cycle values="even,odd"}" title="{$list[county].title|escape}">
							<td>
								{$citizenInfo.xref[xref].last_update_date|bit_long_date}
							</td>
							<td>
								{$citizenInfo.xref[xref].source|escape}
							</td>
							<td>
								{if isset($citizenInfo.xref[xref].usn) && ($citizenInfo.xref[xref].usn <> '') }
									{$citizenInfo.xref[xref].usn|escape}
									{smartlink ititle="Link to" ifile="display_citizen.php" booticon="icon-edit" content_id=$citizenInfo.xref[xref].usn}
								{/if}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="view_xref.php" booticon="icon-edit" source=$citizenInfo.xref[xref].source xref=$citizenInfo.xref[xref].cross_reference}
								</span>
								<label for="ev_{$citizenInfo.xref[xref].cross_reference}">	
									{$citizenInfo.xref[xref].cross_reference}
								</label>
							</td>
						</tr>
					{sectionelse}
						<tr class="norecords">
							<td colspan="3">
								{tr}No records found{/tr}
							</td>
						</tr>
					{/section}
				</tbody>
			</table>
			{/forminput}
		</div>
		{/legend}
		{/jstab}
