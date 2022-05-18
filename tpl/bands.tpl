<table>
	<tr class="rftHeaderRow">
		<td colspan="3">
			Bands ({$bands|@count})
			{if $user && ! $artist}
				{* home page shows link to delete all favorites from favorite list *}
				<a href="/rft/unFavoriteAll"><img src="/images/delete.png"
					 title="Wipe out my favorite lists" /></a>
			{/if}
		</td>
	</tr>
	{foreach from=$bands item=band}
		<tr class="rftRow{if $band.id == $currentBand} currentBand{/if}">
			<td>
				<a href="/rft/band&bandId={$band.id}">{$band.name|htmlspecialchars}</a>
			</td>
			<td>
				{* in an artist page remove a tie to the artist *}
				{if $artist && $band.createdBy == $user.id}
					<form action="/rft/unBandArtist">
						<input type ="checkbox" name="ok" />
						<input type ="hidden" name="bandId" value="{$band.id}" />
						<input type ="hidden" name="artistId" value="{$artist.id}" />
						<input type ="hidden" name="page" value="artist" />
						<input type="image" src="/images/delete.png"
							title="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars} (check the box to confirm)"
						/>
					</form>
				{/if}
				{if $homeUser.favoriteBands && in_array($band.id, $homeUser.favoriteBands)}
					{if $homeUser.id == $user.id}
						<a href="/rft/removeFavoriteBand&bandId={$band.id}"><img src="/images/removeFavorite.png"
									 title="Remove from Favorites" /></a>
					{else}
						<img src="/images/favorite.png" title="A {$homeUser.id|nickname}'s Favorite" />
					{/if}
				{/if}
			</td>
		</tr>
	{/foreach}
	{if $artist && $smarty.session.rftId}
		<tr class="rftHeaderRow">
			<td>
				<form method="post" id="newArtistForm" action="/rft/addBandToArtist">
					<input type="text" size="30" name="bandName" />
					<input type="hidden" name="artistId" value="{$artist.id}" />
					<input type="image" src="/images/addArtist.png" title="Add a band to {$artist.name|htmlspecialchars}" />
				</form>
			</td>
		</tr>
	{/if}
</table>
