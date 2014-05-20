minimal_height = 600;
minimal_width = 1024;

proportional = false;

if( typeof( window.innerWidth ) == 'number' )
{ 
	//Non-IE
	window_width = window.innerWidth;
	window_height = window.innerHeight;

	if(window_height < minimal_height || window_width < minimal_width)
		proportional = true;//sizes have to be proportional

	if(!proportional && (window.innerHeight < minimal_height || window.innerWidth < minimal_width))
	{
		window.moveTo(0,0);
		window.resizeTo(screen.availWidth, screen.availHeight);
	}

}
else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
{
	//IE 6+ in 'standards compliant mode'
	window_width = document.documentElement.clientWidth; 
	window_height = document.documentElement.clientHeight;

	if(window_height < minimal_height || window_width < minimal_width)
		proportional = true;//sizes have to be proportional

	if(!proportional && (document.documentElement.clientHeight < minimal_height || document.documentElement.clientWidth < minimal_width))
	{
		window.moveTo(0,0);
		window.resizeTo(screen.availWidth, screen.availHeight);
	}

}
else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
{
	//IE 4 compatible
	window_width = document.body.clientWidth;
	window_height = document.body.clientHeight;

	if(window_height < minimal_height || window_width < minimal_width)
		proportional = true;//sizes have to be proportional

	if(!proportional && (document.body.clientWidth < minimal_height || document.body.clientHeight < minimal_width))
	{
		window.moveTo(0,0);
		window.resizeTo(screen.availWidth, screen.availHeight);
	}

}

if(screen.width > screen.height)
	var font_size = parseInt(screen.width * 0.01);
else
	var font_size = parseInt(screen.height * 0.01);

if(font_size > 12)
	font_size = 12;

var font = 'normal normal normal ' + font_size + 'px'  + ' Arial , sans-serif'; //weight, style, variant, size, family name, generic family
var title_font = 'bold normal normal ' + font_size + 'px'  + ' Arial , sans-serif';

function fit_screen()
{
	document.body.style.font = font;
}
