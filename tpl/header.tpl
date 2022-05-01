<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>
		{if $title}
			{$title|htmlspecialchars} - Rock Family Trees
		{elseif $user}
			Rock Family Trees - {$user.avatar|htmlspecialchars}
		{else}
			Rock Family Trees
		{/if}
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{if $metaTitle}
		<meta name="title" content="{$metaTitle|htmlspecialchars}" />
		<meta name="keywords" content="{$metaKeywords|htmlspecialchars}" />
		<meta name="description" content="{$metaDescription|htmlspecialchars}" />
	{/if}
	<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery.hoverClass.js"></script>

	<link rel="stylesheet" type="text/css" href="/css/rft.css"></link>
	<script type="text/javascript" src="/js/jquery-tooltip/jquery.tooltip.js"></script>
	<script type="text/javascript" src="/js/jquery.imgToolTip.js"></script>
	<script type="text/javascript" src="/js/jquery.showImage.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/jquery-tooltip/jquery.tooltip.css"></link>
	<script type="text/javascript" src="/js/rft.js"></script>
</head>
<body>
<div id="headerTpl" align="center">
	<table border="0">
		<tr>
			<td>
				<a target="_blank" href="http://theora.com/"><img alt="theora.com" title="theora.com" height="50" style="border: 0px" src="/images/cortXR22.jpg" /></a>
				<a href="/rft/home"><img  border="0" alt="Home" title="Home" height="50" src="/images/home.jpg" /></a>
				<a target="_blank" href="http://en.wikipedia.org/wiki/Pete_Frame"><img id="rftImage" border="0" alt="Rock Family Trees" title="Rock Family Trees" height="50" src="/images/RockFamilyTrees.jpg" /></a>
				{if $smarty.session.captchaSet }
					<script type="text/javascript">var captchaSet = {$smarty.session.captchaSet};</script>
					<a target="_blank" href="http://en.wikipedia.org/wiki/CAPTCHA"><img id="captchaImage" border="0" alt="Captch" title="Captch" height="30" src="{$smarty.session.captchaFile}" /></a>
				{/if}
			</td>
		</tr>
	</table>
</div>
<div class="headerAndFooter">
	<b>Rock Family Trees (cheetah)</b><br />
</div>
<div class="topPane">
	<table border="0" width="100%">
		<tr>
			<td>
				<form method="post" id="switchUserForm" action="/rft">
					<table border="0">
						<tr>
							<td>
								Id: <input class="topItem required number" type="text" name="avatarId" value="{$smarty.session.rftId}" />
							</td>
							<td width="10"></td>
							<td>
								<img width="16" height="16" border="0" src="/images/lock.png" alt="{$user.passwd}" title="{$user.passwd}" />
								Password:
								<input class="topItem required" type="password" name="passwd" />
							</td>
							<td width="5"></td>
							<td>
								<input type="hidden" class="topItem" name="action" value="switchId" />
								<input type="image" src="/images/switchUser.png" alt="Switch User" title="Switch User" />
							</td>
						</tr>
					</table>
				</form>
			</td>
			{if $user}
				<td width="20"></td>
				<td>
					<form method="post" id="changeAvatarForm" action="/rft">
						<table border="0">
							<tr>
								<td>
									Avatar: <input class="topItem required" type="text" name="avatar" value="{$user.avatar|htmlspecialchars}" />
								</td>
								<td>
									<input type="hidden" class="topItem" name="action" value="changeAvatar" />
									<input type="image" src="/images/changeAvatar.png" alt="Change my Avatar Name" title="Change my Avatar Name" />
								</td>
							</tr>
						</table>
					</form>
				</td>
			{/if}
			<td width="20"></td>
			<td>
				<a href="javascript:addBand();"><img width="16" height="16" border="0" src="/images/addBand.png" alt="Add a band" title="Add a band" /></a>
			</td>
			<td>
				<a href="javascript:addArtist();"><img width="16" height="16" border="0" src="/images/addArtist.png" alt="Add a musician" title="Add a musician" /></a>
			</td>
			<td width="30"></td>
			<td>
				<form method="post" id="searchForm" action="">
					<table border="0">
						<tr>
							<td>
								<input class="topItem required" type="text" name="searchTerm" value="{$smarty.request.searchTerm}" />
							</td>
							<td>
								<input type="hidden" name="action" value="search" />
								{if ! $user}Search {/if}<input type="image" src="/images/search.png" alt="Search"  title="Search" />
							</td>
						</tr>
					</table>
				</form>
			</td>
			<td width="20"></td>
			<td>
				<a href="/rft/home"><img border="0" width="16" height="16" src="/images/home.png" alt="Home" title="Home" /></a>
			</td>
			<td width="20"></td>
			<td>
				<a target="showSource" href="/showSource"><img border="0" width="16" height="16" src="/images/list.png" title="Show Source" /></a>
			</td>
			<td width="20"></td>
			<td>
				<a href="/rft/help">{if ! $user}HELP {/if}<img border="0" width="16" height="16" src="/images/help.png" alt="Help" title="Help" /></a>
			</td>
		</tr>
	</table>
</div>
<div id="rftMain">
