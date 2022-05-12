<table border="0">
	<tr class="rftFormRow">
		<td>id</td>
		<td>{$homeUser.id}</td>
	</tr>
	<tr class="rftFormRow">
		<td>nickname</td>
		<td>
			{$homeUser.nickname|htmlspecialchars}
			{if $smarty.session.rftId != $homeUser.id}
				<a href="javascript:follow({$homeUser.id})"><img width="16" height="16" border="0" src="/images/follow.png"
					 title="Follow {$homeUser.nickname}" /></a>
			{/if}
		</td>
	</tr>
	<tr class="rftFormRow">
		<td>Member Since</td>
		<td>{$homeUser.created|msuDateFmt}</td>
	</tr>
	<tr class="rftFormRow">
		<td>Last Entry</td>
		<td>{$homeUser.lastOp|msuDateFmt}</td>
	</tr>
	<tr class="rftFormRow">
		<td>Total Entries</td>
		<td>{$homeUser.numOps}</td>
	</tr>
	{if $user.status == "superAdmin"}
		<tr class="rftFormRow">
			<td>Status</td>
			<td>
				{$homeUser.status}
				{if $homeUser.status == "superAdmin"}
				{elseif $homeUser.status == "Admin"}
					<a href="javascript:invertStatus({$homeUser.id})"><img width="16" height="16" src="/images/revoke.png" border="0" title="Revoke Admin" /></a>
				{else}
					<a href="javascript:invertStatus({$homeUser.id})"><img width="16" height="16" src="/images/award.png" border="0" title="Award Admin" /></a>
				{/if}
			</td>
		</tr>
	{/if}
</table>
