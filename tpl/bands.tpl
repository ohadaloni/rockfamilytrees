<table>
	<tr class="rftHeaderRow">
		<td>
			Bands ({$bands|@count})
		</td>
		<td>
			{if $user && ! $artist}
				{* home page shows link to delete all favorites from favorite list *}
				<form action="/rft/unFavoriteAll">
					<input type ="checkbox" name="ok" />
					<input type="image" src="/images/delete.png"
						title="Wipe out my favorite lists (check the box to confirm)"
					/>
				</form>
			{/if}
		</td>
	</tr>
	<tr class="rftHeaderRow">
		<td colspan="2">
			<form action="/rft/addBand">
				<input type="text" size="30" name="bandName" placeholder="New Band" />
			</form>
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
						<form action="/rft/removeFavoriteBand">
							<input type ="checkbox" name="ok" />
							<input type ="hidden" name="bandId" value="{$band.id}" />
							<input type="image" src="/images/removeFavorite.png"
								title="Remove from Favorites (check the box to confirm)" />
						</form>
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
