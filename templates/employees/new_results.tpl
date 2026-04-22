<!-- Add New Customer results TPL -->
<table width="100%" cellpadding="0" cellspacing="0" class="olotable">
	<tr>
			<td class="olohead">
				<!-- Tool Bar -->
				<div class="mb-3 d-flex align-items-center justify-content-between">
					<div class="flex-grow-1">
						{include file="core/tool_bar.tpl"}
					</div>
					<a class="btn btn-sm btn-primary" href="?page=employees:new&page_title=New Employee">
						Add
					</a>
				</div>
			<table width="100%" border="0" cellpadding="8" cellspacing="0">
				<tr>
					<td class="olotd">
					
						<table class="olotable" border="0" cellpadding="5" cellspacing="0" width="100%" summary="Customer Contact">
							<tr>
								<td class="olohead" colspan="4">New Employee Information</td>
							</tr><tr>						
								<td class="menutd"><b>Contact</b></td>
								<td class="menutd"> {$VAR.displayName}</td>
								<td class="menutd"><b>Email</b></td>
								<td class="menutd"> {$VAR.email}</td>
							</tr><tr>
								<td class="menutd"><b>First Name</b></td>
								<td class="menutd">{$VAR.firstName}</td>
								<td class="menutd"><b>Last Name</b>
								<td class="menutd">{$VAR.lastName}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr><tr>
								<td class="menutd"><b>Address</b></td>
								<td class="menutd"></td>
								<td class="menutd"><b>Home</b></td>
								<td class="menutd">{$VAR.homePhone}</td>
							</tr><tr>
								<td class="menutd" colspan="2">{$VAR.address}</td>			
								<td class="menutd"><b>Work</b></td>
								<td class="menutd"> {$VAR.workPhone}</td>
							</tr><tr>
								<td class="menutd"> {$VAR.city},</td>
								<td class="menutd">{$VAR.state} {$VAR.zip}</td>
								<td class="menutd"><b>Mobile</b></td>
								<td class="menutd"> {$VAR.mobilePhone}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr><tr>
								<td class="menutd"><b>Type</b></td>
								<td class="menutd"> {$VAR.type}</td>
								<td class="menutd"><b>Login:</b></td>
								<td class="menutd">{$VAR.login}</td>
							</tr><tr>
								<td class="row2" colspan="4">&nbsp;</td>
							</tr>
						</table>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
