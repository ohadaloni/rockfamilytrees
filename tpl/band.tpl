<h2>{$band.name|htmlspecialchars}</h2>
<table>
	<tr class="rftFormRow">
		<td>
			<a href="/rft/band&bandId={$band.id}"><img src="/images/refresh.png" title="Reload" /></a>

			{if $isFavorite}
				<a href="/rft/removeFavoriteBand&bandId={$band.id}"><img src="/images/removeFavorite.png"
							title="Remove from Favorites" /></a>
			{else}
				<a href="/rft/addBandToFavorites?bandId={$band.id}"><img src="/images/addFavorite.png"
					
						title="Add {$band.name|htmlspecialchars} to My Favorites" /></a>
			{/if}

			<a target="_blank" href="http://www.youtube.com/results?search_query={$band.name|urlencode}"><img
				src="/images/youtube.png" title="Search {$band.name|htmlspecialchars} on YouTube"
					 /></a>

			<a target="_blank" href="http://en.wikipedia.org/w/index.php?title=Special%3ASearch&search={$band.name|urlencode}"><img
				src="/images/wikipedia.png" title="Search {$band.name|htmlspecialchars} in the Wikipedia"
					 /></a>
			<a target="_blank" href="http://www.google.com/search?q={$band.name|urlencode}"><img
				src="/images/google.png" title="Google search {$band.name|htmlspecialchars}"
					 /></a>
			<a target="_blank" href="http://www.google.com/search?q={$searchQuery|urlencode}"><img
				src="/images/googleWiki.png" title="synthesized Search" /></a>

		</td>
		<td>
			{if $band.createdBy == $user.id && $artists|@count == 0}
				<a href="/rft/deleteBand?bandId={$band.id}"><img src="/images/delete.png" title="Delete" /></a>
			{/if}
		</td>
	</tr>
	{if $band.createdBy == $user.id}
		<tr class="rftFormRow">
			<td colspan="2">
					<form method="post" id="changeBandForm" action="/rft/changeBand">
						<input type="text" size="30" name="bandName" value="{$band.name|htmlspecialchars}" />
						<input type="hidden" name="bandId" value="{$band.id}" />
						<input type="image" src="/images/edit.png" title="Correct Name" />
					</form>
			</td>
		</tr>
	{/if}
	<tr class="rftFormRow">
		<td>Created</td>
		<td>{$band.createdOn|msuDateFmt}</td>
	</tr>
	<tr class="rftFormRow">
		<td>By</td>
		<td>
			{$band.createdBy|nickname}
			{if $band.createdBy != $smarty.session.rftId}
				<a href="/rft/userHome&userId={$band.createdBy}"><img src="/images/home.png"
					title="{$band.createdBy|nickname}'s home"/></a>
			{/if}
		</td>
	</tr>
</table>
<br />
<div id="bandArtists">
{include file="artists.tpl"}
</div>
