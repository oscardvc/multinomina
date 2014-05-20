<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:nomina="http://www.sat.gob.mx/nomina" xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital">
	<xsl:template match="/">
		<html>
			<head>
				<style type="text/css">
					.title
					{
						font:bold normal normal 2.4mm Arial , sans-serif;
						color:#666
					}

					.normal
					{
						font:normal normal normal 2.4mm Arial , sans-serif;
						color:#999
					}
				</style>
			</head>
			<body style = "padding:0mm; margin:0mm;" oncontextmenu = "return false">

				<xsl:for-each select="Raiz/cfdi:Comprobante">
					<div>
						<xsl:attribute name="style">
							display:block; position:relative; padding:3mm; margin:0mm; border-bottom:0.7mm dotted #ddd; width:209.9mm; height:133mm; background:none; overflow:hidden;
						</xsl:attribute>
						<xsl:call-template name = "Emisor">
							<xsl:with-param name = "index" select = "position()"/>
						</xsl:call-template>
						<xsl:call-template name = "Receptor">
							<xsl:with-param name = "index" select = "position()"/>
						</xsl:call-template>
						<xsl:call-template name = "Concepto">
							<xsl:with-param name = "index" select = "position()"/>
						</xsl:call-template>
						<xsl:call-template name = "TimbreFiscalDigital">
							<xsl:with-param name = "index" select = "position()"/>
						</xsl:call-template>
					</div>
				</xsl:for-each>

			</body>
		</html>
	</xsl:template>

	<xsl:template name = "Emisor">
		<xsl:param name="index" />
		<xsl:variable name="width" select="104.95" />
		<xsl:variable name="height" select="30" />

		<xsl:for-each select = "/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Emisor">
			<div>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:0mm; border-right:0.7mm solid #555; width:<xsl:copy-of select="$width" />mm; height:<xsl:copy-of select="$height" />mm; background:none;
				</xsl:attribute>

				<img>
					<xsl:attribute name="src">
						get_logo.php?empresa=<xsl:value-of select="@rfc"/>&amp;sucursal=<xsl:value-of select="@sucursal"/>&amp;empresa_sucursal=<xsl:value-of select="@rfc"/>&amp;height=1000&amp;width=1000
					</xsl:attribute>

					<xsl:variable name="new_width" select="@width * $height div @height" />

					<xsl:if test="$new_width &gt; $width">
						<xsl:variable name="new_height" select="$height * $width div $new_width" />
						<xsl:attribute name="style">
							display:block; position:relative; padding:0mm; margin:0mm; border:none; width:<xsl:copy-of select="$width" />mm; height:<xsl:copy-of select="$new_height" />mm; background:none; top:<xsl:value-of select="($height - $new_height) div 2" />mm;
						</xsl:attribute>
					</xsl:if>

					<xsl:if test="$new_width &lt;= $width">
						<xsl:attribute name="style">
							display:block; position:relative; padding:0mm; margin:0mm; border:none; width:<xsl:copy-of select="$new_width" />mm; height:<xsl:copy-of select="$height" />mm; background:none; left:<xsl:value-of select="($width - $new_width) div 2" />mm;
						</xsl:attribute>
					</xsl:if>

				</img>
			</div>
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:0mm; border:none; width:<xsl:copy-of select="$width" />mm; height:7mm; background:none; top:<xsl:value-of select="-1 * $height" />mm; left:<xsl:value-of select="$width" />mm; overflow: hidden;
				</xsl:attribute>
				<tr>
					<td class = "title"> RFC: </td><td class = "normal"><xsl:value-of select="@rfc" /></td>
					<td class = "title"> Régimen fiscal: </td><td class = "normal"><xsl:value-of select="cfdi:RegimenFiscal/@Regimen" /></td>
				</tr>
			</table>
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:1mm 0mm 0mm 0mm; border:none; width:<xsl:copy-of select="$width" />mm; height:22mm; background:none; top:<xsl:value-of select="-1 * $height + 1" />mm; left:<xsl:value-of select="$width" />mm; overflow: hidden;
				</xsl:attribute>
				<tr>
					<td class = "title"> Calle: </td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@calle" /></td>
					<td class = "title"> No: </td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@noExterior" /></td>
					<td class = "title">

						<xsl:if test="cfdi:DomicilioFiscal/@noInterior">
							No. int:
						</xsl:if>

					</td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@noInterior" /></td>
				</tr>
				<tr>
					<td class = "title"> Colonia: </td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@colonia" /></td>
					<td class = "title"> Localidad: </td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@localidad" /></td>
					<td class = "title">

						<xsl:if test="cfdi:DomicilioFiscal/@referencia">
							Referencia:
						</xsl:if>

					</td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@referencia" /></td>
				</tr>
				<tr>
					<td class = "title"> Municipio: </td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@municipio" /></td>
					<td class = "title"> Estado: </td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@estado" /></td>
					<td class = "title"> País: </td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@pais" /></td>
					<td class = "title"> C.P: </td>
					<td class = "normal"><xsl:value-of select="cfdi:DomicilioFiscal/@codigoPostal" /></td>
				</tr>
			</table>

		</xsl:for-each>

	</xsl:template>

	<xsl:template  name = "Receptor">
		<xsl:param name="index" />
		<xsl:variable name="width" select="210" />
		<xsl:variable name="height" select="13" />

		<xsl:for-each select = "/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Receptor">
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:1mm 0mm 0mm 0mm; border:0.7mm solid #555; width:<xsl:copy-of select="$width" />mm; height:<xsl:copy-of select="$height" />mm; background:none; top:<xsl:value-of select="-30" />mm; overflow: hidden;
				</xsl:attribute>
				<tr style = "background:#eee;"><td class = "title" colspan = "2"> Datos del trabajador </td><td class = "title" colspan = "2">Datos de expedición</td><td class = "title" colspan = "2">Datos de certificación</td>

					<xsl:choose>

						<xsl:when test="/Raiz/cfdi:Comprobante[position()&#61;$index]/@status = 'Cancelado'">
							<td style = "font:bold normal normal 2.4mm Arial , sans-serif; color:#CC0033; background:#fff">
								CFDI CANCELADO
							</td>
						</xsl:when>
						<xsl:otherwise>
							<td style = "font:bold normal normal 2.4mm Arial , sans-serif; color:#339900; background:#fff">
								CFDI ACTIVO
							</td>
						</xsl:otherwise>

					</xsl:choose>

				</tr>
				<tr>
					<td class = "title"> Nombre: </td>
					<td class = "normal"><xsl:value-of select="@nombre" /></td>
					<td class = "title"> Lugar: </td>
					<td class = "normal"><xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Emisor/cfdi:DomicilioFiscal/@municipio" /></td>
					<td class = "title"> Folio: </td>
					<td class = "normal"><xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/tfd:TimbreFiscalDigital/@UUID" /></td>
				</tr>
				<tr>
					<td class = "title"> RFC: </td>
					<td class = "normal"><xsl:value-of select="@rfc" /></td>
					<td class = "title"> Fecha y Hora: </td>
					<td class = "normal"><xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/@fecha" /></td>
					<td class = "title"> Fecha y hora: </td>
					<td class = "normal"><xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/tfd:TimbreFiscalDigital/@FechaTimbrado" /></td>
				</tr>
			</table>
		</xsl:for-each>

	</xsl:template>

	<xsl:template name="Concepto">
		<xsl:param name="index" />
		<xsl:variable name="width" select="80" />
		<xsl:variable name="height" select="39" />

		<xsl:for-each select = "/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Conceptos/cfdi:Concepto">
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:1mm 0mm 0mm 0mm; border:0.7mm solid #555; width:<xsl:copy-of select="$width" />mm; height:<xsl:copy-of select="$height" />mm; background:none; top:<xsl:value-of select="-30" />mm; overflow: hidden;
				</xsl:attribute>
				<tr style = "background:#eee;">
					<td class = "title">Cantidad</td>
					<td class = "title">Unidad</td>
					<td class = "title">Descripción</td>
					<td class = "title">Valor unitario</td>
					<td class = "title">Importe</td>
				</tr>
				<tr>
					<td class = "normal"><xsl:value-of select="@cantidad" /></td>
					<td class = "normal"><xsl:value-of select="@unidad" /></td>
					<td class = "normal"><xsl:value-of select="@descripcion" /></td>
					<td class = "normal" style = "text-align:right"><xsl:value-of select='format-number(@valorUnitario,"#,###,###.00")' /></td>
					<td class = "normal"><xsl:value-of select='format-number(@importe,"#,###,###.00")' /></td>
				</tr>
				<br/>
				<tr>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "title" style = "text-align:right">Subtotal $</td>
					<td class = "normal" style = "text-align:right"><xsl:value-of select='format-number(/Raiz/cfdi:Comprobante[position()&#61;$index]/@subTotal,"#,###,###.00")' /></td>
				</tr>
				<tr>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "title" style = "text-align:right">Descuento </td>
					<td class = "normal" style = "text-align:right"><xsl:value-of select='format-number(/Raiz/cfdi:Comprobante[position()&#61;$index]/@descuento,"#,###,###.00")' /></td>
				</tr>
				<tr>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "title" style = "text-align:right"></td>
					<td class = "normal" style = "text-align:right;border-top:1px solid #555"><xsl:value-of select='format-number(/Raiz/cfdi:Comprobante[position()&#61;$index]/@subTotal - /Raiz/cfdi:Comprobante[position()&#61;$index]/@descuento,"#,###,###.00")' /></td>
				</tr>
				<tr>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "title" style = "text-align:right"><xsl:value-of select='/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion/@impuesto' /></td>
					<td class = "normal" style = "text-align:right;"><xsl:value-of select='format-number(/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Impuestos/cfdi:Retenciones/cfdi:Retencion/@importe,"#,###,###.00")' /></td>
				</tr>
				<tr>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "normal"></td>
					<td class = "title" style = "text-align:right">Total $</td>
					<td class = "normal" style = "text-align:right;border-top:1px solid #555"><xsl:value-of select='format-number(/Raiz/cfdi:Comprobante[position()&#61;$index]/@total,"#,###,###.00")' /></td>
				</tr>
			</table>
			<xsl:call-template name = "ComplementoNomina">
				<xsl:with-param name = "index" select = "$index"/>
			</xsl:call-template>
		</xsl:for-each>

	</xsl:template>

	<xsl:template name="ComplementoNomina">
		<xsl:param name="index" />
		<xsl:variable name="width" select="128" />
		<xsl:variable name="height" select="39" />

		<xsl:for-each select = "/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina">
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:2mm 0mm 0mm 0mm; border:0.7mm solid #555; width:<xsl:copy-of select="$width" />mm; height:<xsl:copy-of select="$height" />mm; background:none; top:<xsl:value-of select="-71" />mm; left:82mm; overflow: hidden;
				</xsl:attribute>
				<tr style = "background:#eee;"><td class = "title" colspan = "4">
					<xsl:choose>
						<xsl:when test="/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Conceptos/cfdi:Concepto/@descripcion = 'PAGO DE NOMINA'">Complemento Nómina del <xsl:value-of select='@FechaInicialPago' /> al <xsl:value-of select='@FechaFinalPago' />
						</xsl:when>
						<xsl:when test="/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Conceptos/cfdi:Concepto/@descripcion = 'PAGO DE VACACIONES'">Complemento Vacaciones al <xsl:value-of select='@FechaInicialPago' />
						</xsl:when>
					</xsl:choose>
				</td></tr>
				<tr>
					<td class = "title" colspan = "2">Percepciones</td>
					<td class = "title" colspan = "2">Deducciones</td>
				</tr>

				<xsl:choose>
					<xsl:when test="count(nomina:Percepciones/nomina:Percepcion) &gt;= count(nomina:Deducciones/nomina:Deduccion)">
						<xsl:for-each select = "nomina:Percepciones/nomina:Percepcion">
							<xsl:variable name="i" select="position()" />
							<tr>
								<td class = "normal"><xsl:value-of select='@Concepto' /></td>
								<td class = "normal" style = "text-align:right"><xsl:value-of select='format-number(@ImporteGravado + @ImporteExento,"#,###,###.00")' /></td>
								<td class = "normal">

									<xsl:if test = '/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Deducciones/nomina:Deduccion[position() = $i]/@Concepto'>
										<xsl:value-of select='/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Deducciones/nomina:Deduccion[position() = $i]/@Concepto' />
									</xsl:if>

								</td>
								<td class = "normal" style = "text-align:right">

									<xsl:if test = '/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Deducciones/nomina:Deduccion[position() = $i]/@Concepto'>
										<xsl:value-of select='format-number(/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Deducciones/nomina:Deduccion[position() = $i]/@ImporteGravado + /Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Deducciones/nomina:Deduccion[position() = $i]/@ImporteExento,"#,###,###.00")' />
									</xsl:if>

								</td>
							</tr>
						</xsl:for-each>

					</xsl:when>
					<xsl:otherwise>

						<xsl:for-each select = "nomina:Deducciones/nomina:Deduccion">
							<xsl:variable name="i" select="position()" />
							<tr>
								<td class = "normal">

									<xsl:if test = '/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Percepciones/nomina:Percepcion[position() = $i]/@Concepto'>
										<xsl:value-of select='/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Percepciones/nomina:Percepcion[position() = $i]/@Concepto' />
									</xsl:if>

								</td>
								<td class = "normal" style = "text-align:right">

									<xsl:if test = '/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Percepciones/nomina:Percepcion[position() = $i]/@Concepto'>
										<xsl:value-of select='format-number(/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Percepciones/nomina:Percepcion[position() = $i]/@ImporteGravado + /Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/nomina:Nomina/nomina:Percepciones/nomina:Percepcion[position() = $i]/@ImporteExento,"#,###,###.00")' />
									</xsl:if>

								</td>
								<td class = "normal"><xsl:value-of select='@Concepto' /></td>
								<td class = "normal" style = "text-align:right"><xsl:value-of select='format-number(@ImporteGravado + @ImporteExento,"#,###,###.00")' /></td>
							</tr>
						</xsl:for-each>

					</xsl:otherwise>
				</xsl:choose>

				<tr>
					<td></td>
					<td class = "normal" style = "text-align:right; border-top:1px Solid #999">$ <xsl:value-of select='format-number(nomina:Percepciones/@TotalGravado + nomina:Percepciones/@TotalExento,"#,###,###.00")' /></td>
					<td></td>
					<td class = "normal" style = "text-align:right; border-top:1px Solid #999">$ <xsl:value-of select='format-number(nomina:Deducciones/@TotalGravado + nomina:Deducciones/@TotalExento,"#,###,###.00")' /></td>
				</tr>
				<tr>
					<td class = "title">Total</td>
					<td class = "normal" style = "text-align:right;">$ <xsl:value-of select='format-number(nomina:Percepciones/@TotalGravado + nomina:Percepciones/@TotalExento - nomina:Deducciones/@TotalGravado - nomina:Deducciones/@TotalExento,"#,###,###.00")' /></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</xsl:for-each>

	</xsl:template>

	<xsl:template name="TimbreFiscalDigital">
		<xsl:param name="index" />
		<xsl:variable name="width" select="165" />

		<xsl:for-each select = "/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/tfd:TimbreFiscalDigital">
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:2mm 0mm 0mm 0mm; border:none; width:<xsl:copy-of select="$width" />mm; height:20mm; background:none; top:-73mm; overflow: hidden;
				</xsl:attribute>
				<tr><td class = "title">Tipo de comprobante: </td><td class = "normal"><xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/@tipoDeComprobante" /></td><td class = "title">Método de pago: </td><td class = "normal"><xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/@metodoDePago" /></td></tr>
			</table>
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:2mm 0mm 0mm 0mm; border:none; width:<xsl:copy-of select="$width" />mm; height:22mm; background:none; top:-91mm; overflow: hidden;
				</xsl:attribute>
				<tr><td class = "title">Sello digital del SAT</td></tr>
				<tr><td class = "normal" style = "word-wrap:break-word; word-break:break-all;"><xsl:value-of select="@selloSAT" /></td></tr>
				<tr><td class = "title">Sello digital del CFDI</td></tr>
				<tr><td class = "normal" style = "word-wrap:break-word; word-break:break-all;"><xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/@sello" /></td></tr>
			</table>
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:2mm 0mm 0mm 0mm; border:none; width:<xsl:copy-of select="$width" />mm; height:8mm; background:none; top:-95mm; overflow: hidden;
				</xsl:attribute>
				<tr><td class = "title">No. de CSD emisor: </td><td class = "normal"><xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/@noCertificado" /></td></tr>
				<tr><td class = "title">No. de CSD SAT: </td><td class = "normal"><xsl:value-of select="@noCertificadoSAT" /></td></tr>
			</table>
			<table>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:2mm 0mm 0mm 0mm; border:none; width:<xsl:copy-of select="$width" />mm; height:22mm; background:none; top:-97mm; overflow: hidden;
				</xsl:attribute>
				<tr><td class = "title">Cadena original del complemento de certificación digital del SAT</td></tr>
				<tr><td class = "normal" style = "word-wrap:break-word; word-break:break-all;">||<xsl:value-of select="@version" />|<xsl:value-of select="@UUID" />|<xsl:value-of select="@FechaTimbrado" />|<xsl:value-of select="@selloCFD" />|<xsl:value-of select="@noCertificadoSAT" />||</td></tr>
				<tr><td class = "title">*Este documento es una representación impresa de un CFDI</td></tr>
			</table>
			<img>
				<xsl:attribute name="src">
					https://chart.googleapis.com/chart?chs=500x500&amp;cht=qr&amp;chl=%3Fre%3D<xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Emisor/@rfc"/>%26rr%3D<xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Receptor/@rfc"/>%26tt%3D<xsl:value-of select='format-number(/Raiz/cfdi:Comprobante[position()&#61;$index]/@total,"0000000000.000000")'/>%26id%3D<xsl:value-of select="/Raiz/cfdi:Comprobante[position()&#61;$index]/cfdi:Complemento/tfd:TimbreFiscalDigital/@UUID"/>&amp;choe=UTF-8
				</xsl:attribute>
				<xsl:attribute name="style">
					display:block; position:relative; padding:0mm; margin:0mm 0mm 0mm 6mm; border:0.7mm solid #555; width:38mm; height:38mm; background:none; top:-143mm; left:<xsl:copy-of select="$width" />mm;
				</xsl:attribute>
			</img>
		</xsl:for-each>

	</xsl:template>

</xsl:stylesheet>
