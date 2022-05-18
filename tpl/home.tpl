<h3>{$title|htmlspecialchars}</h3>
<table>
	<tr>
		{if $homeUser}
			<td valign="top">
				{include file="homeUser.tpl"}
			</td>
		{/if}
		<td valign="top">
			{include file="bands.tpl"}
		</td>
		<td valign="top">
			{include file="artists.tpl"}
		</td>
	</tr>
</table>
