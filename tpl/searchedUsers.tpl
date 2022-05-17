<table>
	<tr class="rftHeaderRow">
		<td>nickname ({$searchedUsers|@count})</td>
	</tr>
	{foreach from=$searchedUsers item=searchedUser}
		<tr class="rftRow">
			<td>
				{$searchedUser.id|nickname}
				<a href="/rft/userHome&amp;userId={$searchedUser.id}"><img src="/images/home.png"
						 title="{$searchedUser.id|nickname}'s home" /></a>
				<a href="/rft/follow?userId={$searchedUser.id}"><img src="/images/follow.png"
								 title="Follow" /></a>
				<img src="/images/info.png"
					
					title="Member since {$searchedUser.created|msuDateFmt}{if $searchedUser.numOps}, last entry on {$searchedUser.lastOp|msuDateFmt},total: {$searchedUser.numOps}{/if}" />
			</td>
		</tr>
	{/foreach}
</table>
