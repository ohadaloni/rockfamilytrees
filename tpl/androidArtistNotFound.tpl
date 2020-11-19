<table border="0" width="100%">
	<tr class="androidHeaderAndFooter">
		<td>Rock Family Trees</td>
	</tr>
	<tr class="rftHeaderRow androidText">
		<td>{$artist}</td>
	</tr>
	<tr class="rftRow androidText">
		<td>
			Sorry. We couldn't find what bands<br />
			<b>{$artist|htmlspecialchars}</b> played with.<br />
			This mishap has been noted.<br />
			It will be corrected soon.<br />
			See
			<a href="?className=Rft&action=androidBand&bandId={$beatlesId}"><b>Other</b></a>
			bands and musicians.
			<img border="0" width="0" height="0" src="http://android.theora.com/log.php?appName=Rft&className=Rft&methodName=androidArtistNotFound.tpl&isError=true&detail={$artist|urlencode}" />
		</td>
	</tr>
	<tr class="androidHeaderAndFooter">
		<td>Rock Family Trees</td>
	</tr>
</table>
