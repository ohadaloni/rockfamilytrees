<table border="0">
	<tr class="rftHeaderRow">
		<td colspan="3">
			{if $band}
				Members
			{else}
				Musicians
			{/if}
				({$artists|@count})
			{if $band}
				<a href="javascript:addArtistToBand({$band.id}, '{$band.name|htmlspecialchars|msuJsStr}');"><img width="16" height="16" border="0"
					src="/images/addArtist.png" alt="Add a member to {$band.name|htmlspecialchars}" title="Add a member to {$band.name|htmlspecialchars}" /></a>
			{else}
				<a href="javascript:addArtist();"><img width="16" height="16" border="0" src="/images/addArtist.png" alt="Add a musician" title="Add a musician" /></a>
				{if ! $user }
					{* visitors also see explicit link *}
					<a href="javascript:addArtist();">Add a musician</a>
				{/if}
			{/if}
		</td>
	</tr>
	{foreach from=$artists item=artist}
		<tr class="rftRow{if $artist.id == $currentArtist} currentArtist{/if}">
			<td>
				<a {* {if $band}{/if} *} href="/rft/artist&amp;artistId={$artist.id}">{$artist.name|htmlspecialchars}</a>
				{* in a band page remove a tie to the artist *}
				{if $band && ( $artist.createdBy == $user.id || $user.numOps > $adminNumOps || $user.status == "Admin" || $user.status == "superAdmin" ) }
					<a href="javascript:unBandArtist({$band.id}, {$artist.id}, 'band')"><img width="16" height="16" border="0" src="/images/delete.png"
						alt="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars}"
						title="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars}" /></a>
				{/if}


				{if $homeUser.favoriteArtists && in_array($artist.id, $homeUser.favoriteArtists)}
					{if $homeUser.id == $user.id}
						<a href="/rft/removeFavoriteArtist&amp;artistId={$artist.id}"><img width="16" height="16" border="0" src="/images/removeFavorite.png"
									alt="Remove from Favorites" title="Remove from Favorites" /></a>
					{else}
						<img width="16" height="16" border="0" src="/images/favorite.png" alt="A {$homeUser.id|nickname}'s Favorite" title="A {$homeUser.id|nickname}'s Favorite" />
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
					<input type="image" width="16" height="16" border="0" src="/images/addArtist.png" alt="Add a member to {$band.name|htmlspecialchars}" title="Add a member to {$band.name|htmlspecialchars}" />
				</form>
			</td>
		</tr>
	{/if}
</table>
