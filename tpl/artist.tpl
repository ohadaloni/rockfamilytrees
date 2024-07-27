<h2>{$artist.name|htmlspecialchars}</h2>
<table>
	<tr class="rftFormRow">
		<td>
			<a href="/rft/artist?artistId={$artist.id}"><img src="/images/refresh.png" title="Reload" /></a>
		</td>
		<td>
			{if $isFavorite}
				<form action="/rft/removeFavoriteArtist">
					<input type ="checkbox" name="ok" />
					<input type ="hidden" name="artistId" value="{$artist.id}" />
					<input type="image" src="/images/removeFavorite.png"
						title="Remove from Favorites (check the box to confirm)" />
				</form>
			{else}
				<a href="/rft/addArtistToFavorites?artistId={$artist.id}"><img src="/images/addFavorite.png"
						title="Add {$artist.name|htmlspecialchars} to My Favorites" /></a>
			{/if}
		</td>
		<td>
			<a target="_blank" href="http://www.google.com/search?q=musician+{$artist.name|urlencode}"><img
				src="/images/google.png" title="Google search {$artist.name|htmlspecialchars}" /></a>
		</td>
		<td>
			<a target="_blank" href="http://www.youtube.com/results?search_query={$artist.name|urlencode}"><img
				src="/images/youtube.png" title="Search {$artist.name|htmlspecialchars} on YouTube" /></a>
		</td>
		<td>
			<a target="_blank" href="http://en.wikipedia.org/w/index.php?title=Special%3ASearch&search={$artist.name|urlencode}"><img
				src="/images/wikipedia.png" title="Search {$artist.name|htmlspecialchars} in the Wikipedia" /></a>
		</td>
		<td>
			<a target="_blank" href="http://www.google.com/search?q=musician+{$searchQuery|urlencode}"><img
				src="/images/googleWiki.png" title="synthesized Search" /></a>
		</td>
		<td>
			{if $artist.createdBy == $user.id && $bands|@count == 0 }
				<form action="/rft/deleteArtist">
					<input type ="checkbox" name="ok" />
					<input type ="hidden" name="artistId" value="{$artist.id}" />
					<input type="image" src="/images/delete.png"
						title="Delete Artist (check the box to confirm)" />
				</form>
			{/if}
		</td>
	</tr>
	{if $artist.createdBy == $user.id}
		<tr class="rftFormRow">
			<td colspan="7">
					<form method="post" id="changeArtistForm" action="/rft/changeArtist">
						<input type="text" size="30" name="artistName" value="{$artist.name|htmlspecialchars}" />
						<input type="hidden" name="artistId" value="{$artist.id}" />
						<input type="image" src="/images/edit.png" title="Correct Name" />
					</form>
			</td>
		</tr>
	{/if}
	<tr class="rftFormRow">
		<td colspan="4">Created</td>
		<td colspan="3">{$artist.createdOn|msuDateFmt}</td>
	</tr>
	<tr class="rftFormRow">
		<td colspan="3">By</td>
		<td colspan="4">
			{$artist.createdBy|nickname}
			{if $artist.createdBy != $rftId}
				<a href="/rft/userHome?userId={$artist.createdBy}"><img src="/images/home.png"
					title="{$artist.createdBy|nickname}'s home" /></a>
			{/if}
		</td>
	</tr>
</table>
<br />
<div id="artistBands">
{include file="bands.tpl"}
</div>
