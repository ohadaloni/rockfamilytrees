/*------------------------------------------------------------*/
$(function() {
	rftBindAll(document);
	$("#rftImage").imgToolTip(500);
});
/*------------------------------------------------------------*/
function rftBind()
{
	rftBindAll(this);
}
/*------------------------------------------------------------*/
function paintRows(context)
{
	$(".rftRow", context).hoverClass("hilite");
	$(".rftFormRow", context).hoverClass("hilite");
	$(".rftHeaderRow", context).addClass("zebra0");
	$(".rftFormRow:nth-child(odd)", context).addClass("zebra1");
	$(".rftFormRow:nth-child(even)", context).addClass("zebra2");
	$(".rftRow:nth-child(odd)", context).addClass("zebra1");
	$(".rftRow:nth-child(even)", context).addClass("zebra2");
}
/*------------------------------*/
function rftBindAll(context)
{
	if ( ! context )
		context = document;

	paintRows(context);

	$(".rftRow", context).click(function(){
		$(".rftRow").not(this).removeClass("keepHilited");
		$(".rftFormRow").not(this).removeClass("keepHilited");
		$(this).addClass("keepHilited");
	});
	$(".rftFormRow", context).click(function(){
		$(".rftRow").not(this).removeClass("keepHilited");
		$(".rftFormRow").not(this).removeClass("keepHilited");
		$(this).addClass("keepHilited");
	});

	$(".validateForm", context).validate({errorClass: "validateErrors"});
	/* must bind the validation separetaly to forms appearing on the same page */
	/* or the plugin gets confused */
	$("#switchUserForm", context).validate({errorClass: "validateErrors"});
	$("#changeAvatarForm", context).validate({errorClass: "validateErrors"});
	$("#searchForm", context).validate({errorClass: "validateErrors"});
	$("#newArtistForm", context).validate({errorClass: "validateErrors"});
	$("#changeArtistForm", context).validate({errorClass: "validateErrors"});

	$(".androidArtist", context).click(function() {
		url = "?action=androidArtist&artistId=" + this.id ;
		document.location = url;
	});

	$(".androidBand", context).click(function() {
		url = "?action=androidBand&bandId=" + this.id ;
		document.location = url;
	});
}
/*------------------------------------------------------------*/
function verifyCaptcha() {
	// if the captcha is not set then there is a trusted user in session
	if ( typeof(captchaSet) == 'undefined' )
		return(true);
	captcha = prompt("First time user. Please enter " + captchaSet + " to verify your are human" );
	if ( captcha == null || captcha == "" )
		return(false);
	if ( captcha != captchaSet ) {
		alert("captcha not entered correctly. Sorry.");
		return(false);
	}
	return(true);
}
/*------------------------------------------------------------*/
function captchaArg() {
	if ( typeof(captchaSet) == 'undefined' )
		return("");
	return("&captchaEntered=" + captchaSet);
}
/*------------------------------------------------------------*/
function addBand() {
	band = prompt("Band Name:");
	if ( band == null || band == "" )
		return;
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=addBand&bandName=" + escape(band) + captchaArg();
}
/*------------------------------------------------------------*/
function addArtist() {
	artist = prompt("Musician Name:");
	if ( artist == null || artist == "" )
		return;
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=addArtist&artistName=" + escape(artist) + captchaArg();
}
/*------------------------------------------------------------*/
function addArtistToBand(bandId, bandName) {
	artistName = prompt("Add a member to " + bandName + ". Member Name:");
	if ( artistName == null || artistName == "" )
		return;
	if ( typeof(captchaSet) == 'undefined' ) {
		// if the captcha is not set then there is a trusted user in session
		// ajax the result to the bandArtists div
		hrf = "?action=addArtistToBand&bandId=" + bandId + "&artistName=" + escape(artistName);
		$("#bandArtists").html('<img border="0" src="images/ajax-loader.gif" />');
		$("#bandArtists").load(hrf, rftBind);
		return;
	}
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=addArtistToBand&bandId=" + bandId + "&artistName=" + escape(artistName) + captchaArg();
}
/*------------------------------------------------------------*/
function addBandToArtist(artistId, artistName) {
	bandName = prompt("Add a Band to " + artistName + ". Band Name:");
	if ( bandName == null || bandName == "" )
		return;
	if ( typeof(captchaSet) == 'undefined' ) {
		// if the captcha is not set then there is a trusted user in session
		// ajax the result to the artistBands div
		hrf = "?action=addBandToArtist&artistId=" + artistId + "&bandName=" + escape(bandName);
		$("#artistBands").html('<img border="0" src="images/ajax-loader.gif" />');
		$("#artistBands").load(hrf, rftBind);
		return;
	}

	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=addBandToArtist&artistId=" + artistId + "&bandName=" + escape(bandName) + captchaArg();
}
/*------------------------------------------------------------*/
function unBandArtist(bandId, artistId, page) {
	if ( ! verifyCaptcha() )
		return;
	hrf = "?action=unBandArtist&bandId=" + bandId + "&artistId=" + artistId + "&page=" + page;
	if ( page == 'band' )
		selector = "#bandArtists";
	else
		selector = "#artistBands" ;
		
		$(selector).html('<img border="0" src="images/ajax-loader.gif" />');
		$(selector).load(hrf, rftBind);
}
/*------------------------------------------------------------*/
function follow(userId) {
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=follow&userId=" + userId + captchaArg();
}
/*------------------------------------------------------------*/
function addArtistToFavorites(artistId) {
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=addArtistToFavorites&artistId=" + artistId + captchaArg() ;
}
/*------------------------------------------------------------*/
function addBandToFavorites(bandId) {
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=addBandToFavorites&bandId=" + bandId + captchaArg() ;
}
/*------------------------------------------------------------*/
function changeBand(bandId, previousName) {
	newName = prompt("Change Band Name", previousName);
	if ( ! newName )
		return;
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=changeBand&bandId=" + bandId + "&bandName=" + escape(newName) ;
}
/*------------------------------------------------------------*/
function changeArtist(artistId, previousName) {
	newName = prompt("Change Artist Name", previousName);
	if ( ! newName )
		return;
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=changeArtist&artistId=" + artistId + "&artistName=" + escape(newName) ;
}
/*------------------------------------------------------------*/
function deleteBand(bandId) {
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=deleteBand&bandId=" + bandId;
}
/*------------------------------------------------------------*/
function deleteArtist(artistId) {
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=deleteArtist&artistId=" + artistId;
}
/*------------------------------------------------------------*/
function invertStatus(userId) {
	if ( ! verifyCaptcha() )
		return;
	window.location.href = "?action=invertStatus&userId=" + userId;
}
/*------------------------------------------------------------*/
