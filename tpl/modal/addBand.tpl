<h4>Add Band</h4>
<form method="post" class="validateForm" id="addBandForm">
<table border="0" class="center">
	<tr class="rftFormRow">
		<td>
			Band Name
		</td>
		<td>
			<input type="text" size="50" name="bandName" />
		</td>
	</tr>
	{if ! $smarty.session.rftId}
		<tr class="rftHeaderRow">
			<td>First time user</td>
			<td><img border="0" src="images/Captch/{$captchaFileName}"</td>
		</tr>
		<tr class="rftFormRow">
				<td><a target="_blank">Captcha</a></td>
				<td><input type="text" name="captchEntered" /></td>
		</tr>
	<tr class="rftHeaderRow">
		<td colspan="2">
			<input type="hidden" name="action" value="addBand" />
			<input type="submit" value="Add Band" />
		</td>
	</tr>
</table>
</form>
<br />
<br />
<br />
