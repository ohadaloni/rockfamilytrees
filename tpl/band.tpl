<h2>{$band.name|htmlspecialchars}</h2>
<table border="0">
	<tr class="rftFormRow">
		<td colspan="2">
			<a href="?action=band&amp;bandId={$band.id}"><img border="0" width="16" height="16" src="images/refresh.png" alt="Reload" title="Reload" /></a>
			{if ( $band.createdBy == $user.id || $user.numOps > $adminNumOps ) && $artists|@count == 0 || $user.status == "Admin" || $user.status == "superAdmin"}
				<a href="javascript:deleteBand({$band.id})"><img width="16" height="16" border="0" src="images/delete.png" alt="Delete" title="Delete" /></a>
			{/if}

			{if $isFavorite}
				<a href="?action=removeFavoriteBand&amp;bandId={$band.id}"><img width="16" height="16" border="0" src="images/removeFavorite.png"
							alt="Remove from Favorites" title="Remove from Favorites" /></a>
			{else}
				<a href="javascript:addBandToFavorites({$band.id})"><img width="16" height="16" border="0" src="images/addFavorite.png"
					alt="Add {$band.name|htmlspecialchars} to My Favorites"
						title="Add {$band.name|htmlspecialchars} to My Favorites" /></a>
			{/if}

			<a target="_blank" href="http://www.youtube.com/results?search_query={$band.name|urlencode}"><img
				border="0" src="images/youtube.png" title="Search {$band.name|htmlspecialchars} on YouTube"
					alt="Search {$band.name|htmlspecialchars} on YouTube" /></a>

			<a target="_blank" href="http://en.wikipedia.org/w/index.php?title=Special%3ASearch&amp;search={$band.name|urlencode}"><img
				border="0" src="images/wikipedia.png" title="Search {$band.name|htmlspecialchars} in the Wikipedia"
					alt="Search {$band.name|htmlspecialchars} in the Wikipedia" /></a>
			<a target="_blank" href="http://www.google.com/search?q={$band.name|urlencode}"><img
				border="0" src="images/google.png" title="Google search {$band.name|htmlspecialchars}"
					alt="Google search {$band.name|htmlspecialchars}" /></a>
			<a target="_blank" href="http://www.google.com/search?q={$searchQuery|urlencode}"><img
				border="0" src="images/googleWiki.png" title="synthesized Search" alt="Synthesized Search" /></a>

		</td>
	</tr>
	{if $band.createdBy == $user.id || $user.status == "Admin" || $user.status == "superAdmin" || $user.numOps > $adminNumOps}
		<tr class="rftFormRow">
			<td colspan="2">
					<form method="post" id="changeBandForm">
						<input type="text" size="30" name="bandName" value="{$band.name|htmlspecialchars}" />
						<input type="hidden" name="bandId" value="{$band.id}" />
						<input type="hidden" name="action" value="changeBand" />
						<input type="image" width="16" height="16" border="0" src="images/edit.png" alt="Correct Name" title="Correct Name" />
					</form>
			</td>
		</tr>
	{/if}
	<tr class="rftFormRow">
		<td>Created</td>
		<td>{$band.createdOn|msuDateFmt}</td>
	</tr>
	<tr class="rftFormRow">
		<td>By</td>
		<td>
			{$band.createdBy|avatar}
			{if $band.createdBy != $smarty.session.rftId}
				<a href="?action=userHome&amp;userId={$band.createdBy}"><img width="16" height="16" border="0" src="images/home.png"
					alt="{$band.createdBy|avatar}'s home" title="{$band.createdBy|avatar}'s home"/></a>
				<a href="javascript:follow({$band.createdBy})"><img width="16" height="16" border="0" src="images/follow.png"
					alt="Follow {$band.createdBy|avatar}" title="Follow {$band.createdBy|avatar}"/></a>
			{/if}
		</td>
	</tr>
</table>
<br />
<div id="bandArtists">
{include file="artists.tpl"}
</div>
