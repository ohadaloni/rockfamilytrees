<table border="0">
	<tr class="rftHeaderRow">
		<td>nickname ({$users|@count})</td>
	</tr>
	{foreach from=$users item=user}
		<tr class="rftRow">
			<td>
				{$user.id|nickname}
				<a href="/rft/userHome&userId={$user.id}"><img width="16" height="16" border="0" src="/images/home.png"
						 title="Home Page of {$user.id|nickname}"/></a>
				<a href="/rft/follow&userId={$user.id}"><img width="16" height="16" border="0" src="/images/follow.png"
								 title="Follow" /></a>
				<img border="0" src="/images/info.png" width="16" height="16"
					
					title="Member since {$user.created|msuDateFmt}{if $user.numOps}, last entry on {$user.lastOp|msuDateFmt},total: {$user.numOps}{/if}" />
			</td>
		</tr>
	{/foreach}
</table>
