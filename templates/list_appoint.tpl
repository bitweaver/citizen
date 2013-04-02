

		{assign var=ticketscnt value=$citizenInfo.tickets|@count}
		{jstab title="Appointments ($ticketscnt)"}
		{legend legend="Appointments"}
		<div class="control-group">
			{formlabel label="Tickets" for="ticket"}
			{forminput}
			<table>
				<caption>{tr}List of appointments{/tr}</caption>
				<thead>
					<tr>
						<th>Data</th>
						<th>TAG</th>
						<th>Note</th>
					</tr>
				</thead>
				<tbody>
					{section name=ticket loop=$citizenInfo.tickets}
						<tr class="{cycle values="even,odd"}" title="{$citizenInfo.ticket[ticket].title|escape}">
							<td>
								{$citizenInfo.tickets[ticket].ticket_ref|bit_long_date} - {$citizenInfo.tickets[ticket].ticket_no}
							</td>
							<td>
								{$citizenInfo.tickets[ticket].tags|escape}
							</td>
							<td>
								<span class="actionicon">
									{smartlink ititle="View" ifile="view_ticket.php" ibiticon="icons/accessories-text-editor" ticket_id=$citizenInfo.tickets[ticket].ticket_id}
								</span>
								<label for="ev_{$citizenInfo.tickets[ticket].ticket_no}">	
									{$citizenInfo.tickets[ticket].staff_id}
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
