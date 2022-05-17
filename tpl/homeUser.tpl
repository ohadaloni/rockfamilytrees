<table>
	<tr class="rftFormRow">
		<td>id</td>
		<td>{$homeUser.id}</td>
	</tr>
	<tr class="rftFormRow">
		<td>nickname</td>
		<td>
			{$homeUser.nickname|htmlspecialchars}
			{if $smarty.session.rftId != $homeUser.id}
				<a href="/rft/follow?userId={$homeUser.id}"><img src="/images/follow.png"
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
					<a href="/rft/invertStatus?userId={$homeUser.id}"><img src="/images/revoke.png" title="Revoke Admin" /></a>
				{else}
					<a href="/rft/invertStatus?userId={$homeUser.id}"><img src="/images/award.png" title="Award Admin" /></a>
				{/if}
			</td>
		</tr>
	{/if}
</table>
