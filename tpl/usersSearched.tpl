<table border="0">
	<tr class="rftHeaderRow">
		<td>Avatars ({$users|@count})</td>
	</tr>
	{foreach from=$users item=user}
		<tr class="rftRow">
			<td>
				{$user.id|avatar}
				<a href="/rft/userHome&userId={$user.id}"><img width="16" height="16" border="0" src="/images/home.png"
						alt="Home Page of {$user.id|avatar}" title="Home Page of {$user.id|avatar}"/></a>
				<a href="/rft/follow&userId={$user.id}"><img width="16" height="16" border="0" src="/images/follow.png"
								alt="Follow" title="Follow" /></a>
				<img border="0" src="/images/info.png" width="16" height="16"
					alt="Member since {$user.created|msuDateFmt}{if $user.numOps}, last entry on {$user.lastOp|msuDateFmt},total: {$user.numOps}{/if}"
					title="Member since {$user.created|msuDateFmt}{if $user.numOps}, last entry on {$user.lastOp|msuDateFmt},total: {$user.numOps}{/if}" />
			</td>
		</tr>
	{/foreach}
</table>
