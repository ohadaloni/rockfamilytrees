<h2>{$artist.name|htmlspecialchars}</h2>
<table border="0">
	<tr class="rftFormRow">
		<td colspan="2">
			<a href="/rft/artist?artistId={$artist.id}"><img border="0" width="16" height="16" src="/images/refresh.png" title="Reload" /></a>
			{if ( $artist.createdBy == $user.id || $user.numOps > $adminNumOps ) && $artists|@count == 0 || $user.status == "Admin" || $user.status == "superAdmin"}
				<a href="javascript:deleteArtist({$artist.id})"><img width="16" height="16" border="0" src="/images/delete.png" title="Delete" /></a>
			{/if}



			{if $isFavorite}
				<a href="/rft/removeFavoriteArtist?artistId={$artist.id}"><img width="16" height="16" border="0" src="/images/removeFavorite.png"
							 /></a>
			{else}
				<a href="javascript:addArtistToFavorites({$artist.id})"><img width="16" height="16" border="0" src="/images/addFavorite.png"
						title="Add {$artist.name|htmlspecialchars} to My Favorites" /></a>
			{/if}
			<a target="_blank" href="http://www.google.com/search?q={$artist.name|urlencode}"><img width="16" height="16"
				border="0" src="/images/google.png" title="Google search {$artist.name|htmlspecialchars}"
					 /></a>
			<a target="_blank" href="http://www.youtube.com/results?search_query={$artist.name|urlencode}"><img width="16" height="16"
				border="0" src="/images/youtube.png" title="Search {$artist.name|htmlspecialchars} on YouTube"
					/></a>
			<a target="_blank" href="http://en.wikipedia.org/w/index.php?title=Special%3ASearch&amp;search={$artist.name|urlencode}"><img width="16" height="16"
				border="0" src="/images/wikipedia.png" title="Search {$artist.name|htmlspecialchars} in the Wikipedia"
					/></a>
			<a target="_blank" href="http://www.google.com/search?q={$searchQuery|urlencode}"><img
				border="0" src="/images/googleWiki.png" title="synthesized Search" /></a>
		</td>
	</tr>
	{if $artist.createdBy == $user.id || $user.status == "Admin" || $user.status == "superAdmin" || $user.numOps > $adminNumOps}
		<tr class="rftFormRow">
			<td colspan="2">
					<form method="post" id="changeArtistForm" action="/rft/changeArtist">
						<input type="text" size="30" name="artistName" value="{$artist.name|htmlspecialchars}" />
						<input type="hidden" name="artistId" value="{$artist.id}" />
						<input type="image" width="16" height="16" border="0" src="/images/edit.png" title="Correct Name" />
					</form>
			</td>
		</tr>
	{/if}
	<tr class="rftFormRow">
		<td>Created</td>
		<td>{$artist.createdOn|msuDateFmt}</td>
	</tr>
	<tr class="rftFormRow">
		<td>By</td>
		<td>
			{$artist.createdBy|nickname}
			{if $artist.createdBy != $smarty.session.rftId}
				<a href="/rft/userHome?userId={$artist.createdBy}"><img width="16" height="16" border="0" src="/images/home.png"
					title="{$artist.createdBy|nickname}'s home" /></a>
				<a href="javascript:follow({$artist.createdBy})"><img width="16" height="16" border="0" src="/images/follow.png"
					title="Follow {$artist.createdBy|nickname}"/></a>
			{/if}
		</td>
	</tr>
</table>
<br />
<div id="artistBands">
{include file="bands.tpl"}
</div>
