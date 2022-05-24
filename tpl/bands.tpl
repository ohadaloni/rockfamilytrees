<table>
	<tr class="rftHeaderRow">
		<td colspan="2">
			Bands
		</td>
		<td>
			<a style="decoration:none;" title="#Members">#</a>
		</td>
		<td>
		</td>
		<td>
			{if $user && ! $artist}
				<form action="/rft/unFavoriteAllBands">
					<input type ="checkbox" name="ok" />
					<input type="image" src="/images/removeFavorite.png"
						title="Wipe out my favorite bands list (check the box to confirm)"
					/>
				</form>
			{/if}
		</td>
	</tr>
	{if $user && ! $artist}
		<tr class="rftHeaderRow">
			<td colspan="5">
				<form action="/rft/addBand">
					<input type="text" size="40" name="bandName" placeholder="Band Name" />
					<input type="image" src="/images/addBand.png" title="New band" />
				</form>
			</td>
		</tr>
	{/if}
	{if $artist && $rftId}
		<tr class="rftHeaderRow">
			<td colspan=5">
				<form method="post" id="newArtistForm" action="/rft/addBandToArtist">
					<input type="text" size="40" name="bandName" placeholder="Band Name" />
					<input type="hidden" name="artistId" value="{$artist.id}" />
					<input type="image" src="/images/addArtist.png" title="Add a band to {$artist.name|htmlspecialchars}" />
				</form>
			</td>
		</tr>
	{/if}
	{foreach from=$bands key=key item=band}
		{assign var=No value=`$key+1`}
		<tr class="rftRow{if $band.id == $currentBand} currentBand{/if}">
			<td>
				{$No}
			</td>
			<td>
				<a href="/rft/band?bandId={$band.id}">{$band.name|htmlspecialchars}</a>
			</td>
			<td>
				<a style="decoration:none;" title="#members">{$band.numArtists}</a>
			</td>
			<td>
				{if $artist}
					{if $band.createdBy == $user.id}
						<form action="/rft/unBandArtist">
							<input type ="checkbox" name="ok" />
							<input type ="hidden" name="bandId" value="{$band.id}" />
							<input type ="hidden" name="artistId" value="{$artist.id}" />
							<input type ="hidden" name="page" value="artist" />
							<input type="image" src="/images/untie.png"
								title="Un-tie {$band.name|htmlspecialchars} and {$artist.name|htmlspecialchars} (check the box to confirm)"
							/>
						</form>
					{/if}
				{/if}
			</td>
			<td>
				{if $band.isFavoraite }
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
				{elseif $band.myLatest}
					<img src="/images/user.png" title="Created on {$band.createdOn} by {$band.createdBy|nickname}" />
				{elseif $band.latest}
					<img src="/images/clock.png" title="Created on {$band.createdOn} by {$band.createdBy|nickname}" />
				{elseif $artist}
					<img src="/images/info.png" title="Created on {$band.createdOn} by {$band.createdBy|nickname}" />
				{else}
					<img src="/images/help.png" title="Unexpected Status!" />
				{/if}
			</td>
		</tr>
	{/foreach}
</table>
