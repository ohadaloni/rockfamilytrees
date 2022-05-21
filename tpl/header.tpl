<!DOCTYPE html>
<html>
<head>
	<title>
		{if $title}
			{$title|htmlspecialchars} - Rock Family Trees
		{elseif $user}
			Rock Family Trees - {$user.nickname|htmlspecialchars}
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
	<link rel="stylesheet" type="text/css" href="/js/jquery-tooltip/jquery.tooltip.css"></link>
	<script type="text/javascript" src="/js/rft.js"></script>
</head>
<body>
<div id="headerTpl" align="center">
	<table>
		<tr>
			<td>
				<a target="_blank" href="http://theora.com/"><img title="theora.com" height="50" style="border: 0px" src="/images/cortXR22.jpg" /></a>
				<a href="/rft/home"><img title="Home" height="50" src="/images/home.jpg" /></a>
				<a target="_blank" href="http://en.wikipedia.org/wiki/Pete_Frame"><img id="rftImage" title="Rock Family Trees" height="50" src="/images/RockFamilyTrees.jpg" /></a>
			</td>
		</tr>
	</table>
</div>
<div class="headerAndFooter">
	<b>Rock Family Trees</b><br />
</div>
<div class="topPane">
	<table width="100%">
		<tr>
			<td>
				<form method="post" action="/rft/switchId">
					<table>
						<tr>
							<td>
								Id: <input type="text" name="nickname" value="{$rftId}" />
							</td>
							<td width="10"></td>
							<td>
								<img src="/images/lock.png" title="{$user.passwd}" />
								Password:
								<input type="password" name="passwd" />
							</td>
							<td width="5"></td>
							<td>
								<input type="image" src="/images/switchUser.png" title="Switch User" />
							</td>
						</tr>
					</table>
				</form>
			</td>
			{if $user}
				<td width="20"></td>
				<td>
					<form method="post" action="/rft/changeNickname">
						<table>
							<tr>
								<td>
									nickname: <input type="text" name="nickname" value="{$user.nickname|htmlspecialchars}" />
								</td>
								<td>
									<input type ="checkbox" name="ok" />
								</td>
								<td>
									<input type="image" src="/images/changeAvatar.png" title="Change my Nickname" />
								</td>
							</tr>
						</table>
					</form>
				</td>
			{/if}
			<td width="100"></td>
			<td>
				<form method="post" id="searchForm" action="/rft/search">
					<table>
						<tr>
							<td>
								<input type="text" name="searchTerm" value="{$smarty.request.searchTerm}" />
							</td>
							<td>
								{if ! $user}Search {/if}<input type="image" src="/images/search.png" title="Search" />
							</td>
						</tr>
					</table>
				</form>
			</td>
			<td width="20"></td>
			<td>
				<a href="/rft/home"><img src="/images/home.png" title="Home" /></a>
			</td>
			<td width="20"></td>
			<td>
				<a target="showSource" href="/showSource"><img src="/images/list.png" title="Show Source" /></a>
			</td>
			<td width="20"></td>
		</tr>
	</table>
</div>
<div id="rftMain">
