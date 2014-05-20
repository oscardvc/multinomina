<?php //This page is called by a javascript function named cred_menu at servicio.js

	if(!isset($_SESSION))
		session_start();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>

	<head>
		<style type='text/css'>

			.cred
			{
			    -moz-transform:rotate(270deg);
			    -moz-transform-origin: left bottom;
			    -webkit-transform: rotate(270deg);
			    -webkit-transform-origin: left bottom;
			    -o-transform: rotate(270deg);
			    -o-transform-origin:  left bottom;
			    filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=1);
			}

		</style>
		<script type="text/javascript">
			if( typeof( window.innerWidth ) == 'number' )
			{ 
				//Non-IE
				window_width = window.innerWidth;
				window_height = window.innerHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}
			else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
			{
				//IE 6+ in 'standards compliant mode'
				window_width = document.documentElement.clientWidth; 
				window_height = document.documentElement.clientHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}
			else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
			{
				//IE 4 compatible
				window_width = document.body.clientWidth;
				window_height = document.body.clientHeight;
				window.moveTo(0,0);
				window.resizeTo(screen.availWidth, screen.availHeight);
			}

			var font_size = 2.3;
			var font = 'normal normal normal ' + font_size + 'mm Arial , sans-serif'; //weight, style, variant, size, family name, generic family
			var title_font = 'bold normal normal ' + font_size + 'mm Arial , sans-serif';

			function _sheets(data, company, c_address, phone, position, _color, usr, wlogo, hlogo, wsign, hsign)
			{
				var info = data.split('<<');
				var container_height = 356;//355.6
				var container_width = 216;//215.9

				for (var i=0, chunk=10; i<info.length; i+=chunk)
				{
					var container = document.createElement('div');
					document.body.appendChild(container);
					container.style.display = 'block';
					container.style.position = 'relative';
					container.style.padding = '0mm';
					container.style.margin = '0mm';
					container.style.border = 'none';
					container.style.background = '#fff';
					container.style.width = container_width + 'mm';
					container.style.height = container_height + 'mm';
					container.style.overflow = 'hidden';
					_rows(info.slice(i,i+chunk), company, c_address, phone, position, container, _color, usr, wlogo, hlogo, wsign, hsign);
				}

			}

			function _rows(data, company, c_address, phone, position, sheet, _color, usr, wlogo, hlogo, wsign, hsign)
			{
				var container_height = 64.6;
				var init_margin = -4.5;

				for(var i=0, chunk = 2; i<data.length; i+=chunk)
				{
					var container = document.createElement('div');
					sheet.appendChild(container);
					container.style.display = 'block';
					container.style.position = 'relative';
					container.style.padding = '0mm';
					container.style.margin = '0mm';
					container.style.border = 'none';
					container.style.background = '#fff';
					container.style.width = sheet.offsetWidth + 'px';
					container.style.height = (i == 0 ? init_margin : 0) + container_height + 'mm';
					container.style.overflow = 'hidden';
					_cols(data.slice(i,i+chunk), company, c_address, phone, position, container, _color, usr, wlogo, hlogo, wsign, hsign);
				}

			}

			function _cols(data, company, c_address, phone, position, row, _color, usr, wlogo, hlogo, wsign, hsign)
			{
				var container_width = 11 + 86;
				var init_margin = 2.5;
				var border_radius = 4;
				colors = _color.match(/[a-z0-9]{2}/g);

				for(var i=0; i<data.length; i++)
				{
					var info = data[i].split('>>');
					var rfc = info[0];
					var name = info[1];
					var imss = info[2];
					var birth = info[3];
					var blood = info[4];
					var w_address = info[5];
					var key = info[6];
					var avisar = info[7];
					var border_width = 0.4;
					var textarea_color = '#555';
					var border_width_px = mm2px(border_width);
					var _padding = 0.5;
					var _padding_px = mm2px(_padding);
					var cred_width = 54;
					var cred_height = 86;
					var vd = 1.3;
					var vd_px = mm2px(vd);
					var _background = 'rgba(' + parseInt(colors[0],16) + ',' + parseInt(colors[1],16) + ',' + parseInt(colors[2],16) + ',0.95)';
					var border_color = '#' + _color;
					var _left = 1;
					var _left_px = mm2px(_left);
					var container = document.createElement('div');
					row.appendChild(container);
					container.style.display = 'block';
					container.style.position = 'relative';
					container.style.padding = '0mm';
					container.style.margin = '0mm';
					container.style.border = 'none';
					container.style.background = '#fff';
					container.style.width = (i == 0 ? init_margin : 0) + container_width + 'mm';
					container.style.height = row.offsetHeight + 'px';
					container.style.overflow = 'hidden';
					container.style.top = (i == 0 ? 0 : -row.offsetHeight) + 'px';
					container.style.left = (i == 0 ? 0 : container.previousSibling.offsetWidth) + 'px';
					//credential background
					var credential_background = document.createElement('div');
					container.appendChild(credential_background);
					credential_background.style.display = 'block';
					credential_background.style.position = 'relative';
					credential_background.style.padding = '0mm';
					credential_background.style.margin = '0mm';
					credential_background.style.border = 'none';
					credential_background.style.width = cred_width + 'mm';
					credential_background.style.height = cred_height + 'mm';
					credential_background.style.backgroundImage = "url('get_logo.php?empresa=" + company + "&height=" + (credential_background.offsetHeight / 4) + "&width=" + (credential_background.offsetWidth / 2) + "')";
					credential_background.style.backgroundRepeat = "repeat";
					credential_background.style.top = container.offsetHeight - credential_background.offsetHeight + 'px';
					credential_background.style.left = container.offsetWidth + 'px';
					credential_background.style.overflow = 'hidden';
					credential_background.style.borderRadius = border_radius + 'mm';
					credential_background.style.MozBorderRadius = border_radius + 'mm';
					credential_background.style.WebkitBorderRadius = border_radius +  + 'mm';
					credential_background.setAttribute('class','cred');
					//credential
					var credential = document.createElement('div');
					container.appendChild(credential);
					credential.setAttribute('class','cred');
					credential.style.display = 'block';
					credential.style.position = 'relative';
					credential.style.padding = '0mm';
					credential.style.margin = '0mm';
					credential.style.border = 'none';
					credential.style.background = 'rgba(255, 255, 255, 0.7)';
					credential.style.width = cred_width + 'mm';
					credential.style.height = cred_height + 'mm';
					credential.style.top = container.offsetHeight - credential.offsetHeight - credential_background.offsetHeight + 'px';
					credential.style.left = container.offsetWidth + 'px';
					credential.style.overflow = 'hidden';
					credential.style.borderRadius = border_radius + 'mm';
					credential.style.MozBorderRadius = border_radius + 'mm';
					credential.style.WebkitBorderRadius = border_radius +  + 'mm';

					if(position == 'front')
					{
						//logo
						var nwlogomm = 54 - vd * 2;
						var nwlogopx = mm2px(nwlogomm);
						var nhlogomm = 19 - vd * 2;
						var nhlogopx = mm2px(nhlogomm);

						if(wlogo > hlogo)
						{
							new_width = wlogo > nwlogopx ? nwlogopx : wlogo;
							ratio = new_width / wlogo;
							new_height = hlogo * ratio;

							if(new_height > nhlogopx)
							{
								ratio = nhlogopx / new_height;
								new_height = nhlogopx;
								new_width = new_width * ratio;
							}

						}
						else
						{
							new_height = hlogo > nhlogopx ? nhlogopx : hlogo;
							ratio = new_height / hlogo;
							new_width = wlogo * ratio;

							if(new_width > nwlogopx)
							{
								ratio = nwlogopx / new_width;
								new_width = nwlogopx;
								new_height = new_height * ratio;
							}

						}

						var logo = document.createElement('img');
						credential.appendChild(logo);
						logo.style.display = 'block';
						logo.style.position = 'relative';
						logo.style.padding = '0mm';
						logo.style.margin = '0mm';
						logo.style.border = 'none';
						logo.style.background = 'none';

						if(new_width > credential.offsetWidth)
							logo.style.width = credential.offsetWidth + 'px';
						else
							logo.style.width = new_width + 'px';

						logo.style.height = new_height + 'px';
						logo.src = 'get_logo.php?empresa=' + company + '&height=10000&width=10000';
						logo.style.top = (mm2px(19) - new_height) / 2 + 'px';
						logo.style.left = (mm2px(54) - new_width) / 2 + 'px';

						if(logo.offsetLeft + logo.offsetWidth > logo.parentNode.offsetWidth)
							logo.style.left = '0px';

						//photo
						var photo = document.createElement('img');
						credential.appendChild(photo);
						photo.style.display = 'block';
						photo.style.position = 'relative';
						photo.style.top = '0mm';
						photo.style.width = '25mm';
						photo.style.height = '30mm';
						photo.style.padding = '0mm';
						photo.style.margin = '0mm';
						photo.style.border = border_width + 'mm solid ' +  textarea_color;
						photo.style.background = 'none';
						photo.style.top = vd * 2 + 'mm';
						photo.style.left = parseInt((credential.offsetWidth - photo.offsetWidth) / 2) + 'px';
						photo.src = 'get_photo.php?rfc=' + rfc;
						//Name label
						var name_label = document.createElement('div');
						credential.appendChild(name_label);
						name_label.innerHTML = 'NOMBRE';
						name_label.style.display = 'block';
						name_label.style.position = 'relative';
						name_label.style.padding = _padding + 'mm';
						name_label.style.margin = '0mm';
						name_label.style.border = 'none';
						name_label.style.background = _background;
						name_label.style.height = font_size + 'mm';
						name_label.style.width = '47mm';
						name_label.style.font = title_font;
						name_label.style.top = vd * 3 + 'mm';
						name_label.style.left = (credential.offsetWidth - name_label.offsetWidth) / 2 - border_width_px + 'px';
						name_label.style.textAlign = 'center';
						name_label.style.color = '#fff';
						name_label.style.borderTopRightRadius = border_radius + 'mm';
						name_label.style.borderTopLeftRadius = border_radius + 'mm';
						name_label.style.MozBorderRadiusTopright = border_radius + 'mm';
						name_label.style.MozBorderRadiusTopleft = border_radius + 'mm';
						name_label.style.WebkitBorderTopRightRadius = border_radius + 'mm';
						name_label.style.WebkitBorderTopLefttRadius = border_radius + 'mm';
						//Name textarea
						var name_textarea = document.createElement('textarea');
						credential.appendChild(name_textarea);
						name_textarea.innerHTML = name;
						name_textarea.style.display = 'block';
						name_textarea.style.position = 'relative';
						name_textarea.style.padding = _padding + 'mm';
						name_textarea.style.margin = '0mm';
						name_textarea.style.border = 'none';
						name_textarea.style.borderRight = border_width + 'mm solid ' + border_color;
						name_textarea.style.borderBottom = border_width + 'mm solid ' + border_color;
						name_textarea.style.borderLeft = border_width + 'mm solid ' + border_color;
						name_textarea.style.background = 'none';
						name_textarea.style.height = (font_size * 2) + 1 + 'mm';
						name_textarea.style.width = name_label.offsetWidth - border_width_px * 2 - _padding_px * 2 + 'px';
						name_textarea.style.font = font;
						name_textarea.style.top = vd * 3 + 'mm';
						name_textarea.style.left = name_label.offsetLeft + 1 + 'px';
						name_textarea.style.textAlign = 'center';
						name_textarea.style.color = textarea_color;
						name_textarea.style.borderBottomRightRadius = border_radius + 'mm';
						name_textarea.style.borderBottomLeftRadius = border_radius + 'mm';
						name_textarea.style.MozBorderRadiusBottomright = border_radius + 'mm';
						name_textarea.style.MozBorderRadiusBottomleft = border_radius + 'mm';
						name_textarea.style.WebkitBorderBottomRightRadius = border_radius + 'mm';
						name_textarea.style.WebkitBorderBottomLefttRadius = border_radius + 'mm';
						//imss label
						var imss_label = document.createElement('label');
						credential.appendChild(imss_label);
						imss_label.innerHTML = 'No. IMSS';
						imss_label.style.display = 'block';
						imss_label.style.position = 'relative';
						imss_label.style.padding = _padding + border_width + 'mm';
						imss_label.style.margin = '0mm';
						imss_label.style.border = 'none';
						imss_label.style.background = _background;
						imss_label.style.height = font_size + 'mm';
						imss_label.style.width = '12mm';
						imss_label.style.font = title_font;
						imss_label.style.top = vd * 4 + 'mm';
						imss_label.style.left = _left + 'mm';
						imss_label.style.color = '#fff';
						imss_label.style.borderTopLeftRadius = border_radius + 'mm';
						imss_label.style.borderBottomLeftRadius = border_radius + 'mm';
						imss_label.style.MozBorderRadiusTopleft = border_radius + 'mm';
						imss_label.style.MozBorderRadiusBottomleft = border_radius + 'mm';
						imss_label.style.WebkitBorderTopLeftRadius = border_radius + 'mm';
						imss_label.style.WebkitBorderBottomLeftRadius = border_radius + 'mm';
						//imss textarea
						var imss_textarea = document.createElement('textarea');
						credential.appendChild(imss_textarea);
						imss_textarea.innerHTML = imss;
						imss_textarea.style.display = 'block';
						imss_textarea.style.position = 'relative';
						imss_textarea.style.padding = _padding + 'mm';
						imss_textarea.style.margin = '0mm';
						imss_textarea.style.border = border_width + 'mm solid ' + border_color;
						imss_textarea.style.background = 'none';
						imss_textarea.style.height = font_size + 'mm';
						imss_textarea.style.width = '36mm';
						imss_textarea.style.font = font;
						imss_textarea.style.top = vd_px * 4 - imss_label.offsetHeight + 'px';
						imss_textarea.style.left = imss_label.offsetLeft + imss_label.offsetWidth - border_width_px + 'px';
						imss_textarea.style.textAlign = 'left';
						imss_textarea.style.color = textarea_color;
						imss_textarea.style.borderTopRightRadius = border_radius + 'mm';
						imss_textarea.style.borderBottomRightRadius = border_radius + 'mm';
						imss_textarea.style.MozBorderRadiusTopright = border_radius + 'mm';
						imss_textarea.style.MozBorderRadiusBottomright = border_radius + 'mm';
						imss_textarea.style.WebkitBorderTopRightRadius = border_radius + 'mm';
						imss_textarea.style.WebkitBorderBottomRightRadius = border_radius + 'mm';
						//key label
						var key_label = document.createElement('label');
						credential.appendChild(key_label);
						key_label.innerHTML = 'CLAVE';
						key_label.style.display = 'block';
						key_label.style.position = 'relative';
						key_label.style.padding = _padding + border_width + 'mm';
						key_label.style.margin = '0mm';
						key_label.style.border = 'none';
						key_label.style.background = _background;
						key_label.style.height = font_size + 'mm';
						key_label.style.width = '10mm';
						key_label.style.font = title_font;
						key_label.style.top = vd * 2 + 'mm';
						key_label.style.left = _left + 'mm';
						key_label.style.color = '#fff';
						key_label.style.borderTopLeftRadius = border_radius + 'mm';
						key_label.style.borderBottomLeftRadius = border_radius + 'mm';
						key_label.style.MozBorderRadiusTopleft = border_radius + 'mm';
						key_label.style.MozBorderRadiusBottomleft = border_radius + 'mm';
						key_label.style.WebkitBorderTopLeftRadius = border_radius + 'mm';
						key_label.style.WebkitBorderBottomLeftRadius = border_radius + 'mm';
						//key textarea
						var key_textarea = document.createElement('textarea');
						credential.appendChild(key_textarea);
						key_textarea.innerHTML = key;
						key_textarea.style.display = 'block';
						key_textarea.style.position = 'relative';
						key_textarea.style.padding = _padding + 'mm';
						key_textarea.style.margin = '0mm';
						key_textarea.style.border = border_width + 'mm solid ' + border_color;
						key_textarea.style.background = 'none';
						key_textarea.style.height = font_size + 'mm';
						key_textarea.style.width = '38mm';
						key_textarea.style.font = font;
						key_textarea.style.top = vd_px * 2 - imss_label.offsetHeight + 'px';
						key_textarea.style.left = key_label.offsetLeft + key_label.offsetWidth - border_width_px + 'px';
						key_textarea.style.textAlign = 'left';
						key_textarea.style.color = textarea_color;
						key_textarea.style.borderTopRightRadius = border_radius + 'mm';
						key_textarea.style.borderBottomRightRadius = border_radius + 'mm';
						key_textarea.style.MozBorderRadiusTopright = border_radius + 'mm';
						key_textarea.style.MozBorderRadiusBottomright = border_radius + 'mm';
						key_textarea.style.WebkitBorderTopRightRadius = border_radius + 'mm';
						key_textarea.style.WebkitBorderBottomRightRadius = border_radius + 'mm';
						//sign
						var nwsignmm = 54 - vd * 2;
						var nwsignpx = mm2px(nwsignmm);
						var nhsignmm = 12;
						var nhsignpx = mm2px(nhsignmm);

						if(wsign > hsign)
						{
							new_width = wsign > nwsignpx ? nwsignpx : wsign;
							ratio = new_width / wsign;
							new_height = hsign * ratio;

							if(new_height > nhsignpx)
							{
								ratio = nhsignpx / new_height;
								new_height = nhsignpx;
								new_width = new_width * ratio;
							}
						}
						else
						{
							new_height = hsign > nhsignpx ? nhsignpx : hsign;
							ratio = new_height / hsign;
							new_width = wsign * ratio;

							if(new_width > nwsignpx)
							{
								ratio = nwsignpx / new_width;
								new_width = nwsignpx;
								new_height = new_height * ratio;
							}

						}

						var sign = document.createElement('img');
						credential.appendChild(sign);
						sign.style.display = 'block';
						sign.style.position = 'relative';
						sign.style.padding = '0mm';
						sign.style.margin = '0mm';
						sign.style.border = 'none';
						sign.style.background = 'none';
						sign.style.width = new_width + 'px';
						sign.style.height = new_height + 'px';
						sign.src = 'get_sign.php?usuario=' + usr + '&height=10000&width=10000';
						sign.style.top = - vd * 3 + 'mm';
						sign.style.left = (mm2px(54) - new_width) / 2 + 'px';
						//authorization
						var _authorization = document.createElement('div');
						credential.appendChild(_authorization);
						_authorization.innerHTML = 'AUTORIZACIÓN';
						_authorization.style.display = 'block';
						_authorization.style.position = 'relative';
						_authorization.style.padding = _padding + 'mm';
						_authorization.style.margin = '0mm';
						_authorization.style.border = 'none';
						_authorization.style.borderTop = border_width + 'mm solid ' + textarea_color;
						_authorization.style.background = 'rgba(255,255,255,0)';//none
						_authorization.style.height = font_size + 'mm';
						_authorization.style.width = '39mm';
						_authorization.style.font = title_font;
						_authorization.style.top = - vd * 4 + 'mm';
						_authorization.style.left = parseInt((credential.offsetWidth - _authorization.offsetWidth) / 2) + 'px';
						_authorization.style.textAlign = 'center';
						_authorization.style.color = textarea_color;
					}
					else
					{
						//birth label
						var birth_label = document.createElement('label');
						credential.appendChild(birth_label);
						birth_label.innerHTML = 'FECHA DE NACIMIENTO';
						birth_label.style.display = 'block';
						birth_label.style.position = 'relative';
						birth_label.style.padding = _padding + border_width + 'mm';
						birth_label.style.margin = '0mm';
						birth_label.style.border = 'none';
						birth_label.style.background = _background;
						birth_label.style.height = font_size + 'mm';
						birth_label.style.width = '31mm';
						birth_label.style.font = title_font;
						birth_label.style.top = vd + border_width * 2 + 'mm';
						birth_label.style.left = _left + 'mm';
						birth_label.style.color = '#fff';
						birth_label.style.borderTopLeftRadius = border_radius + 'mm';
						birth_label.style.borderBottomLeftRadius = border_radius + 'mm';
						birth_label.style.MozBorderRadiusTopleft = border_radius + 'mm';
						birth_label.style.MozBorderRadiusBottomleft = border_radius + 'mm';
						birth_label.style.WebkitBorderTopLeftRadius = border_radius + 'mm';
						birth_label.style.WebkitBorderBottomLeftRadius = border_radius + 'mm';
						//birth textarea
						var birth_textarea = document.createElement('textarea');
						credential.appendChild(birth_textarea);
						birth_textarea.innerHTML = birth;
						birth_textarea.style.display = 'block';
						birth_textarea.style.position = 'relative';
						birth_textarea.style.padding = _padding + 'mm';
						birth_textarea.style.margin = '0mm';
						birth_textarea.style.border = border_width + 'mm solid ' + border_color;
						birth_textarea.style.background = 'none';
						birth_textarea.style.height = font_size + 'mm';
						birth_textarea.style.width = '17mm';
						birth_textarea.style.font = font;
						birth_textarea.style.top = vd - _padding * 2 - font_size + 'mm';
						birth_textarea.style.left = birth_label.offsetLeft + birth_label.offsetWidth - border_width_px + 'px';
						birth_textarea.style.textAlign = 'left';
						birth_textarea.style.color = textarea_color;
						birth_textarea.style.borderTopRightRadius = border_radius + 'mm';
						birth_textarea.style.borderBottomRightRadius = border_radius + 'mm';
						birth_textarea.style.MozBorderRadiusTopright = border_radius + 'mm';
						birth_textarea.style.MozBorderRadiusBottomright = border_radius + 'mm';
						birth_textarea.style.WebkitBorderTopRightRadius = border_radius + 'mm';
						birth_textarea.style.WebkitBorderBottomRightRadius = border_radius + 'mm';
						//blood label
						var blood_label = document.createElement('label');
						credential.appendChild(blood_label);
						blood_label.innerHTML = 'TIPO DE SANGRE';
						blood_label.style.display = 'block';
						blood_label.style.position = 'relative';
						blood_label.style.padding = _padding + border_width + 'mm';
						blood_label.style.margin = '0mm';
						blood_label.style.border = 'none';
						blood_label.style.background = _background;
						blood_label.style.height = font_size + 'mm';
						blood_label.style.width = '23mm';
						blood_label.style.font = title_font;
						blood_label.style.top = vd - font_size + 'mm';
						blood_label.style.left = _left + 'mm';
						blood_label.style.color = '#fff';
						blood_label.style.borderTopLeftRadius = border_radius + 'mm';
						blood_label.style.borderBottomLeftRadius = border_radius + 'mm';
						blood_label.style.MozBorderRadiusTopleft = border_radius + 'mm';
						blood_label.style.MozBorderRadiusBottomleft = border_radius + 'mm';
						blood_label.style.WebkitBorderTopLeftRadius = border_radius + 'mm';
						blood_label.style.WebkitBorderBottomLeftRadius = border_radius + 'mm';
						//blood textarea
						var blood_textarea = document.createElement('textarea');
						credential.appendChild(blood_textarea);
						blood_textarea.innerHTML = blood;
						blood_textarea.style.display = 'block';
						blood_textarea.style.position = 'relative';
						blood_textarea.style.padding = _padding + 'mm';
						blood_textarea.style.margin = '0mm';
						blood_textarea.style.border = border_width + 'mm solid ' + border_color;
						blood_textarea.style.background = 'none';
						blood_textarea.style.height = font_size + 'mm';
						blood_textarea.style.width = '25mm';
						blood_textarea.style.font = font;
						blood_textarea.style.top = vd * 2 - font_size * 3 - border_width + 'mm';
						blood_textarea.style.left = blood_label.offsetLeft + blood_label.offsetWidth - border_width_px + 'px';
						blood_textarea.style.textAlign = 'left';
						blood_textarea.style.color = textarea_color;
						blood_textarea.style.borderTopRightRadius = border_radius + 'mm';
						blood_textarea.style.borderBottomRightRadius = border_radius + 'mm';
						blood_textarea.style.MozBorderRadiusTopright = border_radius + 'mm';
						blood_textarea.style.MozBorderRadiusBottomright = border_radius + 'mm';
						blood_textarea.style.WebkitBorderTopRightRadius = border_radius + 'mm';
						blood_textarea.style.WebkitBorderBottomRightRadius = border_radius + 'mm';
						//avisar label
						var avisar_label = document.createElement('label');
						credential.appendChild(avisar_label);
						avisar_label.innerHTML = 'AVISAR A';
						avisar_label.style.display = 'block';
						avisar_label.style.position = 'relative';
						avisar_label.style.padding = _padding + 'mm';
						avisar_label.style.margin = '0mm';
						avisar_label.style.border = 'none';
						avisar_label.style.background = _background;
						avisar_label.style.height = font_size + 'mm';
						avisar_label.style.width = '47mm';
						avisar_label.style.font = title_font;
						avisar_label.style.top = vd_px * 2 - avisar_label.offsetHeight * 2 + 'px';
						avisar_label.style.left = (credential.offsetWidth - avisar_label.offsetWidth) / 2 - border_width_px + 'px';
						avisar_label.style.textAlign = 'center';
						avisar_label.style.color = '#fff';
						avisar_label.style.borderTopRightRadius = border_radius + 'mm';
						avisar_label.style.borderTopLeftRadius = border_radius + 'mm';
						avisar_label.style.MozBorderRadiusTopright = border_radius + 'mm';
						avisar_label.style.MozBorderRadiusTopleft = border_radius + 'mm';
						avisar_label.style.WebkitBorderTopRightRadius = border_radius + 'mm';
						avisar_label.style.WebkitBorderTopLefttRadius = border_radius + 'mm';
						//avisar textarea
						var avisar_textarea = document.createElement('textarea');
						credential.appendChild(avisar_textarea);
						avisar_textarea.innerHTML = avisar;
						avisar_textarea.style.display = 'block';
						avisar_textarea.style.position = 'relative';
						avisar_textarea.style.padding = _padding + 'mm';
						avisar_textarea.style.margin = '0mm';
						avisar_textarea.style.border = 'none';
						avisar_textarea.style.borderRight = border_width + 'mm solid ' + border_color;
						avisar_textarea.style.borderBottom = border_width + 'mm solid ' + border_color;
						avisar_textarea.style.borderLeft = border_width + 'mm solid ' + border_color;
						avisar_textarea.style.background = 'none';
						avisar_textarea.style.height = font_size * 3 + 'mm';
						avisar_textarea.style.width = avisar_label.offsetWidth - border_width_px * 2 - _padding_px * 2 + 'px';
						avisar_textarea.style.font = font;
						avisar_textarea.style.top = vd_px * 2 - avisar_label.offsetHeight * 2 + 'px';
						avisar_textarea.style.left = avisar_label.offsetLeft + 1 + 'px';
						avisar_textarea.style.textAlign = 'left';
						avisar_textarea.style.color = textarea_color;
						avisar_textarea.style.overflow = 'hidden';
						avisar_textarea.style.borderBottomRightRadius = border_radius + 'mm';
						avisar_textarea.style.borderBottomLeftRadius = border_radius + 'mm';
						avisar_textarea.style.MozBorderRadiusBottomright = border_radius + 'mm';
						avisar_textarea.style.MozBorderRadiusBottomleft = border_radius + 'mm';
						avisar_textarea.style.WebkitBorderBottomRightRadius = border_radius + 'mm';
						avisar_textarea.style.WebkitBorderBottomLefttRadius = border_radius + 'mm';
						//w_addres_label
						var w_address_label = document.createElement('label');
						credential.appendChild(w_address_label);
						w_address_label.innerHTML = 'DOMICILIO';
						w_address_label.style.display = 'block';
						w_address_label.style.position = 'relative';
						w_address_label.style.padding = _padding + 'mm';
						w_address_label.style.margin = '0mm';
						w_address_label.style.border = 'none';
						w_address_label.style.background = _background;
						w_address_label.style.height = font_size + 'mm';
						w_address_label.style.width = '47mm';
						w_address_label.style.font = title_font;
						w_address_label.style.top = vd_px * 5 - avisar_label.offsetHeight * 3 + 'px';
						w_address_label.style.left = (credential.offsetWidth - w_address_label.offsetWidth) / 2 - border_width_px + 'px';
						w_address_label.style.textAlign = 'center';
						w_address_label.style.color = '#fff';
						w_address_label.style.borderTopRightRadius = border_radius + 'mm';
						w_address_label.style.borderTopLeftRadius = border_radius + 'mm';
						w_address_label.style.MozBorderRadiusTopright = border_radius + 'mm';
						w_address_label.style.MozBorderRadiusTopleft = border_radius + 'mm';
						w_address_label.style.WebkitBorderTopRightRadius = border_radius + 'mm';
						w_address_label.style.WebkitBorderTopLefttRadius = border_radius + 'mm';
						//w addres textarea
						var w_address_textarea = document.createElement('textarea');
						credential.appendChild(w_address_textarea);
						w_address_textarea.innerHTML = w_address;
						w_address_textarea.style.display = 'block';
						w_address_textarea.style.position = 'relative';
						w_address_textarea.style.padding = _padding + 'mm';
						w_address_textarea.style.margin = '0mm';
						w_address_textarea.style.border = 'none';
						w_address_textarea.style.borderRight = border_width + 'mm solid ' + border_color;
						w_address_textarea.style.borderBottom = border_width + 'mm solid ' + border_color;
						w_address_textarea.style.borderLeft = border_width + 'mm solid ' + border_color;
						w_address_textarea.style.background = 'none';
						w_address_textarea.style.height = font_size * 8 + 'mm';
						w_address_textarea.style.width = w_address_label.offsetWidth - border_width_px * 2 - _padding_px * 2 + 'px';
						w_address_textarea.style.font = font;
						w_address_textarea.style.top = vd_px * 5 - avisar_label.offsetHeight * 3 + 'px';
						w_address_textarea.style.left = w_address_label.offsetLeft + 1 + 'px';
						w_address_textarea.style.textAlign = 'left';
						w_address_textarea.style.color = textarea_color;
						w_address_textarea.style.overflow = 'hidden';
						w_address_textarea.style.borderBottomRightRadius = border_radius + 'mm';
						w_address_textarea.style.borderBottomLeftRadius = border_radius + 'mm';
						w_address_textarea.style.MozBorderRadiusBottomright = border_radius + 'mm';
						w_address_textarea.style.MozBorderRadiusBottomleft = border_radius + 'mm';
						w_address_textarea.style.WebkitBorderBottomRightRadius = border_radius + 'mm';
						w_address_textarea.style.WebkitBorderBottomLefttRadius = border_radius + 'mm';
						//sign div
						var sign_div = document.createElement('div');
						credential.appendChild(sign_div);
						sign_div.style.display = 'block';
						sign_div.style.position = 'relative';
						sign_div.style.top = vd_px * 7 - avisar_label.offsetHeight * 4 + 'px';
						sign_div.style.width = '50mm';
						sign_div.style.height = '15mm';
						sign_div.style.padding = '0mm';
						sign_div.style.margin = '0mm';
						sign_div.style.border = 'none';
						sign_div.style.background = 'rgba(255,255,255,0)';//none
						sign_div.style.left = parseInt((credential.offsetWidth - sign_div.offsetWidth) / 2) + 'px';
						//sign label
						var sign_label = document.createElement('div');
						credential.appendChild(sign_label);
						sign_label.innerHTML = 'FIRMA DEL TRABAJADOR';
						sign_label.style.display = 'block';
						sign_label.style.position = 'relative';
						sign_label.style.padding = '0mm';
						sign_label.style.margin = '0mm';
						sign_label.style.border = 'none';
						sign_label.style.borderTop = border_width_px + 'px solid ' + textarea_color;
						sign_label.style.background = 'none';
						sign_label.style.height = font_size + 'mm';
						sign_label.style.width = '40mm';
						sign_label.style.font = font;
						sign_label.style.top = vd_px * 4 - avisar_label.offsetHeight * 3 + 'px';
						sign_label.style.left = parseInt((credential.offsetWidth - sign_label.offsetWidth) / 2) + 'px';
						sign_label.style.textAlign = 'center';
						sign_label.style.color = textarea_color;
						//c_address
						var _c_address = document.createElement('div');
						credential.appendChild(_c_address);
						_c_address.innerHTML = c_address + "<br/ >TELÉFONO: " + phone;
						_c_address.style.display = 'block';
						_c_address.style.position = 'relative';
						_c_address.style.padding = '2mm';
						_c_address.style.margin = '0mm';
						_c_address.style.border = 'none';
						_c_address.style.background = _background;
						_c_address.style.height = '16.5mm';
						_c_address.style.width = '50mm';
						_c_address.style.font = font;
						_c_address.style.top = vd_px * 5 - avisar_label.offsetHeight * 3 + 'px';
						_c_address.style.left = '0px';
						_c_address.style.textAlign = 'left';
						_c_address.style.color = '#fff';
						_c_address.style.borderBottomRightRadius = border_radius + 'mm';
						_c_address.style.borderBottomLeftRadius = border_radius + 'mm';
						_c_address.style.MozBorderRadiusBottomright = border_radius + 'mm';
						_c_address.style.MozBorderRadiusBottomleft = border_radius + 'mm';
						_c_address.style.WebkitBorderBottomRightRadius = border_radius + 'mm';
						_c_address.style.WebkitBorderBottomLeftRadius = border_radius + 'mm';
					}

				}

			}

			function mm2px(n)
			{
				var _div = document.createElement('div');
				document.body.appendChild(_div);
				_div.style.display = 'block';
				_div.style.position = 'absolute';
				_div.style.padding = '0';
				_div.style.margin = '0';
				_div.style.border = 'none';
				_div.style.background = '#fff';
				_div.style.top = '0';
				_div.style.left = '0';
				_div.style.width = '1mm';
				_div.style.height = '0mm';
				var val = n * _div.offsetWidth;
				document.body.removeChild(_div);
				return val;
			}

		</script>
	</head>

	<body>
		<?php
			include_once('connection.php');
			$service = $_GET['service'];
			$position = $_GET['position'];
			$date = $_GET['date'];
			$worker = explode(',',$_GET['worker']);
			$color = $_GET['color'];
			$conn = new Connection();
			$result = $conn->query("SELECT Registro_patronal.Empresa, Registro_patronal.Sucursal, Registro_patronal.Empresa_sucursal FROM Servicio_Registro_patronal LEFT JOIN Registro_patronal ON Servicio_Registro_patronal.Registro_patronal = Registro_patronal.Numero WHERE Servicio_Registro_patronal.Servicio = '$service' AND Servicio_Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND Registro_patronal.Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$date', Servicio_Registro_patronal.Fecha_de_asignacion) >= 0 ORDER BY Servicio_Registro_patronal.Fecha_de_asignacion DESC LIMIT 1");
			list($company, $branch, $company_branch) = $conn->fetchRow($result);
			$conn->freeResult($result);

			if(isset($company_branch) && $company_branch != '')
				$company = $company_branch;

			if($branch != '')
				$result = $conn->query("SELECT Calle, Numero_ext, Numero_int, Colonia, Municipio, Estado, Telefono FROM Sucursal WHERE Nombre = '$branch' AND Empresa = '$company' AND Cuenta = '{$_SESSION['cuenta']}'");
			else
				$result = $conn->query("SELECT Calle, Numero_ext, Numero_int, Colonia, Municipio, Estado, Telefono FROM Empresa WHERE RFC = '$company' AND Cuenta = '{$_SESSION['cuenta']}'");

			list($calle, $numeroExt, $numeroInt, $colonia, $municipio, $estado, $phone) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$c_address = "Calle: $calle<br/>No ext: $numeroExt" . ($numeroInt != '' ? " No int: $numeroInt" : "") . " Colonia: $colonia<br/>Municipio: $municipio" . "<br/>Estado: $estado";
			$result = $conn->query("SELECT Empresa FROM Servicio_Empresa WHERE Servicio = '$service' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$date', Fecha_de_asignacion) >= 0 ORDER BY Fecha_de_asignacion DESC LIMIT 1");
			list($client) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$result = $conn->query("SELECT Width, Height FROM Logo WHERE Empresa = '$company' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($wlogo,$hlogo) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$result = $conn->query("SELECT Width, Height FROM Sign WHERE Usuario = '{$_SESSION['usuario']}' AND Cuenta = '{$_SESSION['cuenta']}'");
			list($wsign,$hsign) = $conn->fetchRow($result);
			$conn->freeResult($result);
			$len = count($worker);
			$workers = '';

			for($i=0; $i<$len; $i++)
			{
				$result = $conn->query("SELECT Trabajador.RFC, Trabajador.Nombre, Trabajador.Numero_IMSS, Trabajador.Fecha_de_nacimiento, Trabajador.Tipo_de_sangre, Trabajador.Domicilio_particular, Trabajador.Avisar_a FROM Trabajador LEFT JOIN Servicio_Trabajador ON Trabajador.RFC = Servicio_Trabajador.Trabajador LEFT JOIN Tipo ON (Trabajador.RFC = Tipo.Trabajador AND Servicio_Trabajador.Servicio = Tipo.Servicio) WHERE Servicio_Trabajador.Servicio = '$service' AND DATEDIFF('$date', Servicio_Trabajador.Fecha_de_ingreso_cliente) >= 0 AND DATEDIFF('$date', Tipo.Fecha) >= 0 AND Trabajador.RFC = '{$worker[$i]}' AND Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Servicio_Trabajador.Cuenta = '{$_SESSION['cuenta']}' AND Tipo.Cuenta = '{$_SESSION['cuenta']}'");
				list($rfc,$name,$imss,$birth,$blood,$w_address,$avisar) = $conn->fetchRow($result);
				$result1 = $conn->query("SELECT id FROM Baja WHERE Trabajador = '$rfc' AND Servicio = '$service' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$date', Fecha_de_baja) >= 0 AND (DATEDIFF(Fecha_de_reingreso, '$date') > 0 OR Fecha_de_reingreso  = '0000-00-00')");
				$n = $conn->num_rows($result1);
				$conn->freeResult($result1);

				if($n == 0 && $name != '')
				{
					$result1 = $conn->query("SELECT Nombre FROM Trabajador_Sucursal WHERE Trabajador = '$rfc' AND Servicio = '$service' AND Cuenta = '{$_SESSION['cuenta']}' AND DATEDIFF('$date', Fecha_de_ingreso) >= 0");
					list($client_branch) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					$result1 = $conn->query("SELECT Nombre FROM Sucursal WHERE Nombre = '$client_branch' AND Empresa = '$client' AND Cuenta = '{$_SESSION['cuenta']}'");
					list($k2) = $conn->fetchRow($result1);
					$conn->freeResult($result1);
					$key = substr($client, 0, 4) . substr($k2, 0, 3);
					$name = str_replace ( "\n" , " " , $name);
					$imss = str_replace ( "\n" , " " , $imss);
					$birth = str_replace ( "\n" , " " , $birth);
					$blood = str_replace ( "\n" , " " , $blood);
					$w_address = str_replace ( "\n" , " " , $w_address);
					$workers .= $workers != '' ? "<<$rfc>>$name>>$imss>>$birth>>$blood>>$w_address>>$key>>$avisar" : "$rfc>>$name>>$imss>>$birth>>$blood>>$w_address>>$key>>$avisar";
				}

			}

			if(!isset($wlogo) || !isset($hlogo))
			{
				$result = $conn->query("SELECT Nombre FROM Empresa WHERE RFC = '$company' AND Cuenta = '{$_SESSION['cuenta']}'");
				list($name) = $conn->fetchRow($result);
				$conn->freeResult($result);
				echo "<span style=\"font:bold normal normal 4.5mm Arial , sans-serif;color:#555\">La empresa $name no tiene un logo asignado</span>";
			}
			else
			{
				echo "<script type='text/javascript'>
					//body settings
					document.body.style.padding = '0mm';
					document.body.style.margin = '0mm';
					document.body.style.border = 'none';
					_sheets('$workers','$company','$c_address', '$phone', '$position', '$color', '{$_SESSION['usuario']}', $wlogo, $hlogo, $wsign, $hsign);
				      </script>";
			}

		?>
	</body>
</html>
