<table>
	<tr class="rftHeaderRow">
		<td>nickname ({$searchedUsers|@count})</td>
	</tr>
	{foreach from=$searchedUsers item=searchedUser}
		<tr class="rftRow">
			<td>
				{$searchedUser.id|nickname}
				<a href="/rft/userHome&userId={$searchedUser.id}"><img src="/images/home.png"
						 title="{$searchedUser.id|nickname}'s home" /></a>
				<img src="/images/info.png"
					
					title="Member since {$searchedUser.created|msuDateFmt}" />
			</td>
		</tr>
	{/foreach}
</table>
