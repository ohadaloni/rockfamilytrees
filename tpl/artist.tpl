<h2>{$artist.name|htmlspecialchars}</h2>
<table
	<tr class="rftFormRow">
		<td>
			<a href="/rft/artist?artistId={$artist.id}"><img src="/images/refresh.png" title="Reload" /></a>



			{if $isFavorite}
				<a href="/rft/removeFavoriteArtist?artistId={$artist.id}"><img 
							 /></a>
			{else}
				<a href="/rft/addArtistToFavorites?artistId={$artist.id}"><img src="/images/addFavorite.png"
						title="Add {$artist.name|htmlspecialchars} to My Favorites" /></a>
			{/if}
			<a target="_blank" href="http://www.google.com/search?q={$artist.name|urlencode}"><img
				src="/images/google.png" title="Google search {$artist.name|htmlspecialchars}"
					 /></a>
			<a target="_blank" href="http://www.youtube.com/results?search_query={$artist.name|urlencode}"><img
				src="/images/youtube.png" title="Search {$artist.name|htmlspecialchars} on YouTube"
					/></a>
			<a target="_blank" href="http://en.wikipedia.org/w/index.php?title=Special%3ASearch&amp;search={$artist.name|urlencode}"><img
				src="/images/wikipedia.png" title="Search {$artist.name|htmlspecialchars} in the Wikipedia"
					/></a>
			<a target="_blank" href="http://www.google.com/search?q={$searchQuery|urlencode}"><img
				src="/images/googleWiki.png" title="synthesized Search" /></a>
		</td>
		<td>
			{if $artist.createdBy == $user.id && $bands|@count == 0 }
				<a href="/rft/deleteArtist&artistId={$artist.id}"><img src="/images/delete.png" title="Delete" /></a>
			{/if}
		</td>
	</tr>
	{if $artist.createdBy == $user.id}
		<tr class="rftFormRow">
			<td colspan="2">
					<form method="post" id="changeArtistForm" action="/rft/changeArtist">
						<input type="text" size="30" name="artistName" value="{$artist.name|htmlspecialchars}" />
						<input type="hidden" name="artistId" value="{$artist.id}" />
						<input type="image" src="/images/edit.png" title="Correct Name" />
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
				<a href="/rft/userHome?userId={$artist.createdBy}"><img src="/images/home.png"
					title="{$artist.createdBy|nickname}'s home" /></a>
				<a href="/rft/follow?userId={$artist.createdBy}"><img src="/images/follow.png"
					title="Follow {$artist.createdBy|nickname}"/></a>
			{/if}
		</td>
	</tr>
</table>
<br />
<div id="artistBands">
{include file="bands.tpl"}
</div>
