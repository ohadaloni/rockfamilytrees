<table border="0">
	<tr class="rftHeaderRow">
		<td>nickname ({$searchedUsers|@count})</td>
	</tr>
	{foreach from=$searchedUsers item=searchedUser}
		<tr class="rftRow">
			<td>
				{$searchedUser.id|nickname}
				<a href="/rft/userHome&amp;userId={$searchedUser.id}"><img width="16" height="16" border="0" src="/images/home.png"
						 title="{$searchedUser.id|nickname}'s home" /></a>
				<a href="javascript:follow({$searchedUser.id})"><img width="16" height="16" border="0" src="/images/follow.png"
								 title="Follow" /></a>
				<img border="0" src="/images/info.png" width="16" height="16"
					
					title="Member since {$searchedUser.created|msuDateFmt}{if $searchedUser.numOps}, last entry on {$searchedUser.lastOp|msuDateFmt},total: {$searchedUser.numOps}{/if}" />
			</td>
		</tr>
	{/foreach}
</table>
