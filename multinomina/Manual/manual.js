function _go(obj)
{
	var _element = document.getElementById(obj.innerHTML);
	document.body.scrollTop = _element.offsetTop;
}

function _marck(obj)
{
	obj.style.color = '#99cccc';
}

function _unmarck(obj)
{
	obj.style.color = '#666';
}
