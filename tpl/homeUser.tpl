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
</table>
