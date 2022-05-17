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
}
/*------------------------------------------------------------*/
