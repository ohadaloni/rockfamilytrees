<h2>{$band.name|htmlspecialchars}</h2>
<table>
	<tr class="rftFormRow">
		<td>
			<a href="/rft/band?bandId={$band.id}"><img src="/images/refresh.png" title="Reload" /></a>
		</td>
		<td>
			{if $isFavorite}
					<form action="/rft/removeFavoriteBand">
						<input type ="checkbox" name="ok" />
						<input type ="hidden" name="bandId" value="{$band.id}" />
						<input type="image" src="/images/removeFavorite.png"
							title="Remove from Favorites" />
					</form>
			{else}
				<a href="/rft/addBandToFavorites?bandId={$band.id}"><img src="/images/addFavorite.png"
						title="Add {$band.name|htmlspecialchars} to My Favorites" /></a>
			{/if}
		</td>
		<td>
			<a target="_blank" href="http://www.youtube.com/results?search_query={$band.name|urlencode}"><img
				src="/images/youtube.png" title="Search {$band.name|htmlspecialchars} on YouTube" /></a>
		</td>
		<td>
			<a target="_blank" href="http://en.wikipedia.org/w/index.php?title=Special%3ASearch&search={$band.name|urlencode}+(band)"><img
				src="/images/wikipedia.png" title="Search {$band.name|htmlspecialchars} in the Wikipedia" /></a>
		</td>
		<td>
			<a target="_blank" href="http://www.google.com/search?q=band+{$band.name|urlencode}"><img
				src="/images/google.png" title="Google search {$band.name|htmlspecialchars}"
					 /></a>
		</td>
		<td>
			<a target="_blank" href="http://www.google.com/search?q=band+{$searchQuery|urlencode}"><img
				src="/images/googleWiki.png" title="synthesized Search" /></a>
		</td>
		<td>
			{if $band.createdBy == $user.id && $artists|@count == 0}
				<form action="/rft/deleteBand">
					<input type ="checkbox" name="ok" />
					<input type ="hidden" name="bandId" value="{$band.id}" />
					<input type="image" src="/images/delete.png"
						title="Delete band (check the box to confirm delete" />
				</form>
			{/if}
		</td>
	</tr>
	{if $band.createdBy == $user.id}
		<tr class="rftFormRow">
			<td colspan="7">
					<form method="post" id="changeBandForm" action="/rft/changeBand">
						<input type="text" size="30" name="bandName" value="{$band.name|htmlspecialchars}" />
						<input type="hidden" name="bandId" value="{$band.id}" />
						<input type="image" src="/images/edit.png" title="Correct Name" />
					</form>
			</td>
		</tr>
	{/if}
	<tr class="rftFormRow">
		<td colspan="4">Created</td>
		<td colspan="3">{$band.createdOn|msuDateFmt}</td>
	</tr>
	<tr class="rftFormRow">
		<td colspan="3">By</td>
		<td colspan="4">
			{$band.createdBy|nickname}
			{if $band.createdBy != $rftId}
				<a href="/rft/userHome?userId={$band.createdBy}"><img src="/images/home.png"
					title="{$band.createdBy|nickname}'s home"/></a>
			{/if}
		</td>
	</tr>
</table>
<br />
<div id="bandArtists">
{include file="artists.tpl"}
</div>
