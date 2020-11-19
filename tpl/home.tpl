<h3>{$title|htmlspecialchars}</h3>
{*
{if ! $user}
	{include file="help.tpl"}
{/if}
*}
<table border="0">
	<tr>
		{if $homeUser}
			<td valign="top">
				{include file="homeUser.tpl"}
			</td>
		{/if}
		<td valign="top">
			{include file="users.tpl"}
		</td>
		<td valign="top">
			{include file="bands.tpl"}
		</td>
		<td valign="top">
			{include file="artists.tpl"}
		</td>
	</tr>
</table>
