<table border="0">
		<tr class="rftHeaderRow">
			<td>Avatars</td>
		</tr>
	{if $followees }
		{* followees *}
		<tr class="rftHeaderRow">
			<td>Following ({$followees|@count})</td>
		</tr>
		{foreach from=$followees item=followee}
			<tr class="rftRow">
				<td>
					{$followee.id|avatar}
					<a href="/rft/userHome?userId={$followee.id}"><img width="16" height="16" border="0" src="/images/home.png"
							alt="{$followee.id|avatar}'s home" title="{$followee.id|avatar}'s home" /></a>
				</td>
			</tr>
		{/foreach}
	{/if}

	{if $followers }
		{* followers *}
		<tr class="rftHeaderRow">
			<td>Followers ({$followers|@count})</td>
		</tr>
		{foreach from=$followers item=follower}
			<tr class="rftRow">
				<td>
					{$follower.id|avatar}
					<a href="/rft/userHome?userId={$follower.id}"><img width="16" height="16" border="0" src="/images/home.png"
							alt="{$follower.id|avatar}'s home" title="{$follower.id|avatar}'s home" /></a>
				</td>
			</tr>
		{/foreach}
	{/if}

	{if $mostPopular }
		{* most popular users *}
		<tr class="rftHeaderRow">
			<td>Most Popular ({$mostPopular|@count})</td>
		</tr>
		{foreach from=$mostPopular item=popularUser}
			<tr class="rftRow">
				<td>
					{$popularUser.id|avatar}
					<a href="/rft/userHome?userId={$popularUser.id}"><img width="16" height="16" border="0" src="/images/home.png"
							alt="{$popularUser.id|avatar}'s home" title="{$popularUser.id|avatar}'s home"/></a>
				</td>
			</tr>
		{/foreach}
	{/if}

	{if $mostActive }
		{* most active users *}
		<tr class="rftHeaderRow">
			<td>Most Active ({$mostActive|@count})</td>
		</tr>
		{foreach from=$mostActive item=activeUser}
			<tr class="rftRow">
				<td>
					{$activeUser.id|avatar}
					<a href="/rft/userHome?userId={$activeUser.id}"><img width="16" height="16" border="0" src="/images/home.png"
							alt="{$activeUser.id|avatar}'s home" title="{$activeUser.id|avatar}'s home"/></a>
				</td>
			</tr>
		{/foreach}
	{/if}

	{if $latelyActive }
		{* lately active users *}
		<tr class="rftHeaderRow">
			<td>Lately Active ({$latelyActive|@count})</td>
		</tr>
		{foreach from=$latelyActive item=lAtiveUser}
			<tr class="rftRow">
				<td>
					{$lAtiveUser.id|avatar}
					<a href="/rft/userHome?userId={$lAtiveUser.id}"><img width="16" height="16" border="0" src="/images/home.png"
							alt="{$lAtiveUser.id|avatar}'s home" title="{$lAtiveUser.id|avatar}'s home"/></a>
				</td>
			</tr>
		{/foreach}
	{/if}

</table>
