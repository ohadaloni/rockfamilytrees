<table border="0" width="100%">
	<tr class="androidHeaderAndFooter">
		<td><b>Rock Family Trees</b></td>
	</tr>
	<tr class="rftHeaderRow androidText">
		<td>
			{$band.name}
			<img border="0" width="0" height="0" src="http://android.theora.com/log.php?appName=Rft&className=Rft&methodName=androidBand.tpl" />
		</td>
	</tr>
	<tr class="rftHeaderRow androidText">
		<td><font size="-1">Members</font></td>
	</tr>
	{foreach from=$artists item=artist}
		<tr class="rftRow androidArtist androidText" id="{$artist.id}">
			<td>{$artist.name}</td>
		</tr>
	{/foreach}
	<tr class="androidHeaderAndFooter">
		<td><b>Rock Family Trees</b></td>
	</tr>
</table>
