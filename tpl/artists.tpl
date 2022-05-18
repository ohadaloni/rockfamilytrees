<table>
	<tr class="rftHeaderRow">
		<td colspan="3">
			{if $band}
				Members
			{else}
				Musicians
			{/if}
				({$artists|@count})
			{if $band}
				<a href="/rft/addArtistToBand?bandId={$band.id}&, '{$band.name|htmlspecialchars|msuJsStr}');"><img 
					src="/images/addArtist.png" title="Add a member to {$band.name|htmlspecialchars}" /></a>
			{else}
				<a href="/rft/addArtist"><img src="/images/addArtist.png" title="Add a musician" /></a>
			{/if}
		</td>
	</tr>
	{foreach from=$artists item=artist}
		<tr class="rftRow{if $artist.id == $currentArtist} currentArtist{/if}">
			<td>
				<a {* {if $band}{/if} *} href="/rft/artist&artistId={$artist.id}">{$artist.name|htmlspecialchars}</a>
				{* in a band page remove a tie to the artist *}
				{if $band && $artist.createdBy == $user.id}
					<a href="/rft/unBandArtist?bandId={$band.id}&artistId={$artist.id}"><img src="/images/delete.png"
						
						title="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars}" /></a>
				{/if}


				{if $homeUser.favoriteArtists && in_array($artist.id, $homeUser.favoriteArtists)}
					{if $homeUser.id == $user.id}
						<a href="/rft/removeFavoriteArtist&artistId={$artist.id}"><img src="/images/removeFavorite.png"
									 title="Remove from Favorites" /></a>
					{else}
						<img src="/images/favorite.png" title="A {$homeUser.id|nickname}'s Favorite" />
					{/if}
				{/if}
			</td>
		</tr>
	{/foreach}
	{if $band && $smarty.session.rftId}
		<tr class="rftHeaderRow">
			<td>
				<form method="post" id="newArtistForm" action="/rft/addArtistToBand">
					<input type="text" size="30" name="artistName" />
					<input type="hidden" name="bandId" value="{$band.id}" />
					<input type="image" src="/images/addArtist.png" title="Add a member to {$band.name|htmlspecialchars}" />
				</form>
			</td>
		</tr>
	{/if}
</table>
