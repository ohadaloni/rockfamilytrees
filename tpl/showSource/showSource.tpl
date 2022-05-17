<table>
	<tr>
		<td valign="top">
			<table>
				<tr class="rftHeaderRow">
					<td>Files</td>
				</tr>
				{foreach from=$files item=fileName}
					<tr class="rftRow">
						<td>
							<a href="/showSource?file={$fileName}">{$fileName}</a>
						</td>
					</tr>
				{/foreach}
			</table>
		</td>
		<td valign="top">
			{if $file}
				<h4>{$file}</h4>
				{$source}
			{/if}
		</td>
	</tr>
</table>
