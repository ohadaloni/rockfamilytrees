<table>
	<tr class="rftHeaderRow">
		<td colspan="3">
			{if $band}
				Members
			{else}
				Musicians
			{/if}
				({$artists|@count})
		</td>
	</tr>
	<tr class="rftHeaderRow">
		<td colspan="2">
			<form action="/rft/addArtist">
				<input type="text" size="30" name="artistName" placeholder="New Musician" />
			</form>
		</td>
	</tr>
	{foreach from=$artists item=artist}
		<tr class="rftRow{if $artist.id == $currentArtist} currentArtist{/if}">
			<td>
				<a {* {if $band}{/if} *} href="/rft/artist&artistId={$artist.id}">{$artist.name|htmlspecialchars}</a>
			</td>
			<td>
				{* in a band page remove a tie to the artist *}
				{if $band && $artist.createdBy == $user.id}
					<form action="/rft/unBandArtist">
						<input type ="checkbox" name="ok" />
						<input type ="hidden" name="artistId" value="{$artist.id}" />
						<input type ="hidden" name="bandId" value="{$band.id}" />
						<input type ="hidden" name="page" value="band" />
						<input type="image" src="/images/delete.png"
							title="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars} (check the box to confirm)"
						/>
					</form>
				{/if}


				{if $homeUser.favoriteArtists && in_array($artist.id, $homeUser.favoriteArtists)}
					{if $homeUser.id == $user.id}
						<form action="/rft/removeFavoriteArtist">
							<input type ="checkbox" name="ok" />
							<input type ="hidden" name="artistId" value="{$artist.id}" />
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
