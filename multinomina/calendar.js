//global variables

cal_days_labels = ['D','L','M','M','J','V','S'];//week days name labels
cal_month_labels = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];//month name labels
cal_days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];//days for each month in order
cal_current_date = new Date();//current date

//constructor
function Calendar(month,year,day)
{
	this.month = (isNaN(month) || month == null) ? cal_current_date.getMonth() : month;//i gess i'm missing validating month
	this.year = (isNaN(year) || year == null) ? cal_current_date.getFullYear() : year;//i gess i'm missing validating year
	this.day = (isNaN(day) || day == null) ? cal_current_date.getDate() : day;//i gess i'm missing validating day
	this.monthLen = cal_days_in_month[this.month];

	if(this.month == 1) //this.month == february
	{

		if((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
		{
			this.monthLen = 29;
		}

	}

	this.html = '';
}

//returns this year
Calendar.prototype.getyear = function ()
{
	return this.year;
}

//returns this month
Calendar.prototype.getmonth = function ()
{
	return this.month;
}

//returns this day
Calendar.prototype.getday = function ()
{
	return this.day;
}

//decrements this year
Calendar.prototype.decYear = function()
{
	this.year = (this.year == 0) ? 2050 : this.year - 1;

	if(this.month == 1) //this.month == february
	{

		if((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
		{
			this.monthLen = 29;
		}

	}

}

//increments this year
Calendar.prototype.incYear = function()
{
	this.year += 1;

	if(this.month == 1) //this.month == february
	{

		if((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
		{
			this.monthLen = 29;
		}

	}

}

//decrements this month
Calendar.prototype.decMonth = function()
{
	this.month = (this.month == 0) ? 11 : this.month - 1;
	this.monthLen = cal_days_in_month[this.month];

	if(this.month == 1) //this.month == february
	{

		if((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
		{
			this.monthLen = 29;
		}

	}

}

//increments this month
Calendar.prototype.incMonth = function()
{
	this.month = (this.month == 11) ? 0 : this.month + 1;
	this.monthLen = cal_days_in_month[this.month];

	if(this.month == 1) //this.month == february
	{

		if((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
		{
			this.monthLen = 29;
		}

	}

}

//decrements this date least one day. It wont modify this object
Calendar.prototype.decDay = function()
{
	var aux = new Date(this.year,this.month,this.day);
	aux.setDate(aux.getDate() - 1);
	var month = (aux.getMonth() + 1) < 10 ? '0' + (aux.getMonth() + 1) : aux.getMonth() + 1;
	var day = aux.getDate() < 10 ? '0' + aux.getDate() : aux.getDate();
	return aux.getFullYear() + '-' + month + '-' + day;
}

//drows the calendar
Calendar.prototype.generateHTML = function()
{
	var firstDayDate = new Date(this.year, this.month, 1);
	var startingDay = firstDayDate.getDay();
	var monthLength = cal_days_in_month[this.month];
	//Compensating for leap year
	if(this.month == 1) //this.month == february
	{

		if((this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0)
		{
			monthLength = 29;
		}

	}

	var monthName = cal_month_labels[this.month];
	var html = '<table>';
	html += '<tr><th colspan="7">';
	html += monthName + "&nbsp;" + this.year;
	html += '</th></tr>';
	html += '<tr>';

	for(var i = 0; i <= 6; i++)
	{
		html += '<td class = "header_day" style = "background:#3399cc;">';
		html += cal_days_labels[i];
		html += '</td>';
	}

	html += '</tr><tr>';

	var day = 1;

	//this loop is for weeks (rows)
	for(var i = 0; i <= 9; i++)
	{
		//this loop is for days (cells)
		for(var j = 0; j <= 6; j++)
		{
			html += '<td ';

			if(day <= monthLength && (i>0 || j >= startingDay))
			{
				html += 'class = "day" onmouseover = "calendar_day_bright(this)" onmouseout = "calendar_day_opaque(this)" onclick = "exit(this.innerHTML)">' + day;
				day++;
			}
			else
			{
				html += '>';
			}

			html += '</td>';
		}

		//stop making rows if we've run out of days
		if(day > monthLength)
		{
			break;
		}
		else
		{
			html += '</tr><tr>';
		}


	}

	html += '<tr></table>';
	this.html = html;
}

//return this html
Calendar.prototype.getHTML = function ()
{
	return this.html;
}

//return this month length
Calendar.prototype.getMonthLen = function ()
{
	return this.monthLen;
}
