<table>
	<tr class="rftHeaderRow">
		<td colspan="3">
			Bands ({$bands|@count})
			{if $artist}
				<a href="/rft/addBandToArtist?artistId={$artist.id},'{$artist.name|htmlspecialchars|msuJsStr}');"><img 
					src="/images/addBand.png" title="Add a band to {$artist.name|htmlspecialchars}" /></a>
			{else}
				<a href="/rft/addBand"><img src="/images/addBand.png" title="Add a band" /></a>
			{/if}
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
				<a {* {if $artist}{/if} *}href="/rft/band&amp;bandId={$band.id}">{$band.name|htmlspecialchars}</a>
				{* in an artist page remove a tie to the artist *}
				{if $artist && ( $band.createdBy == $user.id || $user.numOps > $adminNumOps || $user.status == "Admin" || $user.status == "superAdmin" ) }
					<a href="/rft/unBandArtist?bandId={$band.id}&artistId={$artist.id}"><img src="/images/delete.png"
						
						title="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars}" /></a>
				{/if}
				{if $homeUser.favoriteBands && in_array($band.id, $homeUser.favoriteBands)}
					{if $homeUser.id == $user.id}
						<a href="/rft/removeFavoriteBand&amp;bandId={$band.id}"><img src="/images/removeFavorite.png"
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
