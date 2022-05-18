<table>
	<tr class="rftFormRow">
		<td>id</td>
		<td>{$homeUser.id}</td>
	</tr>
	<tr class="rftFormRow">
		<td>nickname</td>
		<td>
			{$homeUser.nickname|htmlspecialchars}
		</td>
	</tr>
	<tr class="rftFormRow">
		<td>Member Since</td>
		<td>{$homeUser.created|msuDateFmt}</td>
	</tr>
</table>
