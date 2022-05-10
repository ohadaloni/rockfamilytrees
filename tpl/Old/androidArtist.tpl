<table border="0" width="100%">
	<tr class="androidHeaderAndFooter">
		<td><b>Rock Family Trees</b></td>
	</tr>
	<tr class="rftHeaderRow androidText">
		<td>
			{$artist.name}
			<img border="0" width="0" height="0" src="http://android.theora.com/log.php?appName=Rft&className=Rft&methodName=androidArtist.tpl" />
		</td>
	</tr>
	<tr class="rftHeaderRow androidText">
		<td><font size="-1">Played with:</font></td>
	</tr>
	{foreach from=$bands item=band}
		<tr class="rftRow androidBand androidText" id="{$band.id}">
			<td>{$band.name}</td>
		</tr>
	{/foreach}
	<tr class="androidHeaderAndFooter">
		<td><b>Rock Family Trees</b></td>
	</tr>
</table>
