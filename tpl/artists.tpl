<table>
	<tr class="rftHeaderRow">
		<td colspan="2">
			{if $band}
				Members
			{else}
				Musicians
			{/if}
		</td>
		<td>
			<a style="decoration:none;" title="#Bands">#</a>
		</td>
		<td>
		</td>
		<td>
			{if $user && ! $band}
				<form action="/rft/unFavoriteAllArtists">
					<input type ="checkbox" name="ok" />
					<input type="image" src="/images/removeFavorite.png"
						title="Wipe out my favorite musicians list (check the box to confirm)"
					/>
				</form>
			{/if}
		</td>
	</tr>
	{if $user && ! $band}
		<tr class="rftHeaderRow">
			<td colspan="5">
				<form action="/rft/addArtist">
					<input type="text" size="40" name="artistName" placeholder="Musician Name" />
					<input type="image" src="/images/addArtist.png" title="New Musician" />
				</form>
			</td>
		</tr>
	{/if}
	{if $band && $rftId}
		<tr class="rftHeaderRow">
			<td colspan="5">
				<form method="post" id="newArtistForm" action="/rft/addArtistToBand">
					<input type="text" size="40" name="artistName" placeholder="New Member" />
					<input type="hidden" name="bandId" value="{$band.id}" />
					<input type="image" src="/images/addArtist.png" title="Add a member to {$band.name|htmlspecialchars}" />
				</form>
			</td>
		</tr>
	{/if}
	{foreach from=$artists key=key item=artist}
		{assign var=No value=`$key+1`}
		<tr class="rftRow{if $artist.id == $currentArtist} currentArtist{/if}">
			<td>
				{$No}
			</td>
			<td>
				<a href="/rft/artist?artistId={$artist.id}">{$artist.name|htmlspecialchars}</a>
			</td>
			<td>
				<a style="decoration:none;" title="#bands">{$artist.numBands}</a>
			</td>
			<td>
				{if $band}
					{if $artist.createdBy == $user.id}
						<form action="/rft/unBandArtist">
							<input type ="checkbox" name="ok" />
							<input type ="hidden" name="bandId" value="{$band.id}" />
							<input type ="hidden" name="artistId" value="{$artist.id}" />
							<input type ="hidden" name="page" value="band" />
							<input type="image" src="/images/untie.png"
								title="Un-tie {$artist.name|htmlspecialchars} and {$band.name|htmlspecialchars} (check the box to confirm)"
							/>
						</form>
					{/if}
				{/if}
			</td>
			<td>
				{if $artist.isFavoraite }
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
				{elseif $artist.myLatest}
					<img src="/images/person.png" title="Created on {$artist.createdOn} by {$artist.createdBy|nickname}" />
				{elseif $artist.latest}
					<img src="/images/clock.png" title="Created on {$artist.createdOn} by {$artist.createdBy|nickname}" />
				{elseif $band}
					<img src="/images/info.png" title="Created on {$band.createdOn} by {$band.createdBy|nickname}" />
				{else}
					<img src="/images/help.png" title="Unexpected Status!" />
				{/if}
			</td>
		</tr>
	{/foreach}
</table>
