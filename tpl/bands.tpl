<table border="0">
	<tr class="rftHeaderRow">
		<td colspan="3">
			Bands ({$bands|@count})
			{if $artist}
				<a href="javascript:addBandToArtist({$artist.id},'{$artist.name|htmlspecialchars|msuJsStr}');"><img width="16" height="16" border="0"
					src="/images/addBand.png" alt="Add a band to {$artist.name|htmlspecialchars}" title="Add a band to {$artist.name|htmlspecialchars}" /></a>
			{else}
				<a href="javascript:addBand();"><img width="16" height="16" border="0" src="/images/addBand.png" alt="Add a band" title="Add a band" /></a>
				{if ! $user }
					{* visitors also see explicit link *}
					<a href="javascript:addBand();">Add a band</a>
				{/if}
			{/if}
			{if $user && ! $artist}
				{* home page shows link to delete all favorites from favorite list *}
				<a href="/rft/unFavoriteAll"><img width="16" height="16" border="0" src="/images/delete.png"
					alt="Wipe out my favorite lists" title="Wipe out my favorite lists" /></a>
			{/if}
		</td>
	</tr>
	{foreach from=$bands item=band}
		<tr class="rftRow{if $band.id == $currentBand} currentBand{/if}">
			<td>
				<a {* {if $artist}{/if} *}href="/rft/band&amp;bandId={$band.id}">{$band.name|htmlspecialchars}</a>
				{* in an artist page remove a tie to the artist *}
				{if $artist && ( $band.createdBy == $user.id || $user.numOps > $adminNumOps || $user.status == "Admin" || $user.status == "superAdmin" ) }
					<a href="javascript:unBandArtist({$band.id}, {$artist.id}, 'artist')"><img width="16" height="16" border="0" src="/images/delete.png"
						alt="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars}"
						title="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars}" /></a>
				{/if}
				{if $homeUser.favoriteBands && in_array($band.id, $homeUser.favoriteBands)}
					{if $homeUser.id == $user.id}
						<a href="/rft/removeFavoriteBand&amp;bandId={$band.id}"><img width="16" height="16" border="0" src="/images/removeFavorite.png"
									alt="Remove from Favorites" title="Remove from Favorites" /></a>
					{else}
						<img width="16" height="16" border="0" src="/images/favorite.png" alt="A {$homeUser.id|avatar}'s Favorite" title="A {$homeUser.id|avatar}'s Favorite" />
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
					<input type="image" width="16" height="16" border="0" src="/images/addArtist.png" alt="Add a band to {$artist.name|htmlspecialchars}" title="Add a band to {$artist.name|htmlspecialchars}" />
				</form>
			</td>
		</tr>
	{/if}
</table>
