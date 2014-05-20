alter table CFDI_Trabajador add Nomina bigint unsigned after Receptor;
alter table nomina_asalariados add Status varchar(700) after Forma_de_pago;
alter table nomina_asimilables add Status varchar(700) after Forma_de_pago;
update nomina_asalariados set Status = 'Sin timbrar';
update nomina_asimilables set Status = 'Sin timbrar';
alter table CFDI_Trabajador add Tipo varchar(100) after Receptor;
alter table CFDI_Trabajador drop primary key, add primary key(id,Cuenta);
-- Actividad (ready)
-- Aguinaldo
alter table Aguinaldo add Cuenta varchar(100);
update Aguinaldo set Cuenta = 'multiasesoria';
alter table Aguinaldo add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Aguinaldo drop primary key, add primary key(id, Cuenta);
-- aguinaldo_asalariados
alter table aguinaldo_asalariados add Cuenta varchar(100);
update aguinaldo_asalariados set Cuenta = 'multiasesoria';
alter table aguinaldo_asalariados add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Apoderado
alter table Apoderado add Cuenta varchar(100);
update Apoderado set Cuenta = 'multiasesoria';
alter table Apoderado add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Apoderado drop primary key, add primary key(Nombre, Empresa, Cuenta);
-- Aportacion del trabajador al fondo de ahorro
alter table Aportacion_del_trabajador_al_fondo_de_ahorro add Cuenta varchar(100);
update Aportacion_del_trabajador_al_fondo_de_ahorro set Cuenta = 'multiasesoria';
alter table Aportacion_del_trabajador_al_fondo_de_ahorro add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Aportacion_del_trabajador_al_fondo_de_ahorro drop primary key, add primary key(id, Cuenta);
-- Archivo digital
alter table Archivo_digital add Cuenta varchar(100);
update Archivo_digital set Cuenta = 'multiasesoria';
alter table Archivo_digital add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Archivo_digital drop primary key, add primary key(Nombre, Cuenta);
-- Baja
alter table Baja add Cuenta varchar(100);
update Baja set Cuenta = 'multiasesoria';
alter table Baja add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Baja drop primary key, add primary key(id, Cuenta);
-- Banco
alter table Banco add Cuenta varchar(100);
update Banco set Cuenta = 'multiasesoria';
alter table Banco add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Banco drop primary key, add primary key(id, Cuenta);
-- Base
alter table Base add Cuenta varchar(100);
update Base set Cuenta = 'multiasesoria';
alter table Base add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Base drop primary key, add primary key(id, Cuenta);
-- CFDI_Trabajador (ready)
-- Contrato
alter table Contrato add Cuenta varchar(100);
update Contrato set Cuenta = 'multiasesoria';
alter table Contrato add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Contrato drop primary key, add primary key(id, Cuenta);
-- cuotas_IMSS
alter table cuotas_IMSS add Cuenta varchar(100);
update cuotas_IMSS set Cuenta = 'multiasesoria';
alter table cuotas_IMSS add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Delegacion_IMSS Subdelegacion_IMSS
delete from Subdelegacion_IMSS;
drop table Subdelegacion_IMSS;
delete from Delegacion_IMSS;
drop table Delegacion_IMSS;
-- Descuento_pendiente
alter table Descuento_pendiente add Cuenta varchar(100) after Trabajador;
update Descuento_pendiente set Cuenta = 'multiasesoria';
alter table Descuento_pendiente add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Descuento_pendiente drop primary key, add primary key(Nomina, Retencion, id, Trabajador, Cuenta);
-- Empresa
alter table Empresa add Cuenta varchar(100);
update Empresa set Cuenta = 'multiasesoria';
alter table Empresa add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Empresa drop primary key, add primary key(RFC, Cuenta);
-- Factor_de_descuento
alter table Factor_de_descuento add Cuenta varchar(100);
update Factor_de_descuento set Cuenta = 'multiasesoria';
alter table Factor_de_descuento add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Factor_de_descuento drop primary key, add primary key(id, Cuenta);
-- Finiquito
alter table Finiquito add Cuenta varchar(100);
update Finiquito set Cuenta = 'multiasesoria';
alter table Finiquito add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Finiquito drop primary key, add primary key(id, Cuenta);
-- Fondo_de_garantia
alter table Fondo_de_garantia add Cuenta varchar(100);
update Fondo_de_garantia set Cuenta = 'multiasesoria';
alter table Fondo_de_garantia add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Fondo_de_garantia drop primary key, add primary key(id, Cuenta);
-- Incapacidad
alter table Incapacidad add Cuenta varchar(100);
update Incapacidad set Cuenta = 'multiasesoria';
alter table Incapacidad add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Incapacidad drop primary key, add primary key(id, Cuenta);
-- incidencias
alter table incidencias add Cuenta varchar(100);
update incidencias set Cuenta = 'multiasesoria';
alter table incidencias add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Instrumento_notarial
alter table Instrumento_notarial add Cuenta varchar(100);
update Instrumento_notarial set Cuenta = 'multiasesoria';
alter table Instrumento_notarial add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Instrumento_notarial drop primary key, add primary key(Numero_de_instrumento, Empresa, Cuenta);
-- ISRasalariados
alter table ISRasalariados add Cuenta varchar(100);
update ISRasalariados set Cuenta = 'multiasesoria';
alter table ISRasalariados add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- ISRasimilables
alter table ISRasimilables add Cuenta varchar(100);
update ISRasimilables set Cuenta = 'multiasesoria';
alter table ISRasimilables add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- ISRaguinaldo
alter table ISRaguinaldo add Cuenta varchar(100);
update ISRaguinaldo set Cuenta = 'multiasesoria';
alter table ISRaguinaldo add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Logo
alter table Logo add Cuenta varchar(100);
update Logo set Cuenta = 'multiasesoria';
alter table Logo add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Logo drop primary key, add primary key(Empresa, Cuenta);
-- Monto_fijo_mensual
alter table Monto_fijo_mensual add Cuenta varchar(100);
update Monto_fijo_mensual set Cuenta = 'multiasesoria';
alter table Monto_fijo_mensual add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Monto_fijo_mensual drop primary key, add primary key(id, Cuenta);
-- Multinomina
drop table Multinomina;
-- Nomina
alter table Nomina add Cuenta varchar(100) after id;
update Nomina set Cuenta = 'multiasesoria';
alter table Nomina add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Nomina drop primary key, add primary key(id, Cuenta);
-- nomina_asalariados
alter table nomina_asalariados add Cuenta varchar(100);
update nomina_asalariados set Cuenta = 'multiasesoria';
alter table nomina_asalariados add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- nomina_asimilables
alter table nomina_asimilables add Cuenta varchar(100);
update nomina_asimilables set Cuenta = 'multiasesoria';
alter table nomina_asimilables add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Pago_por_seguro_de_vida
alter table Pago_por_seguro_de_vida add Cuenta varchar(100);
update Pago_por_seguro_de_vida set Cuenta = 'multiasesoria';
alter table Pago_por_seguro_de_vida add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Pago_por_seguro_de_vida drop primary key, add primary key(id, Cuenta);
-- Pension_alimenticia
alter table Pension_alimenticia add Cuenta varchar(100);
update Pension_alimenticia set Cuenta = 'multiasesoria';
alter table Pension_alimenticia add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Pension_alimenticia drop primary key, add primary key(id, Cuenta);
-- Photo
alter table Photo add Cuenta varchar(100);
update Photo set Cuenta = 'multiasesoria';
alter table Photo add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Photo drop primary key, add primary key(Trabajador, Cuenta);
-- Porcentaje_de_descuento
alter table Porcentaje_de_descuento add Cuenta varchar(100);
update Porcentaje_de_descuento set Cuenta = 'multiasesoria';
alter table Porcentaje_de_descuento add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Porcentaje_de_descuento drop primary key, add primary key(id, Cuenta);
-- prestaciones
alter table prestaciones add Cuenta varchar(100);
update prestaciones set Cuenta = 'multiasesoria';
alter table prestaciones add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Prestamo_administradora
alter table Prestamo_administradora add Cuenta varchar(100) after Numero_de_descuento;
update Prestamo_administradora set Cuenta = 'multiasesoria';
alter table Prestamo_administradora add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Prestamo_administradora drop primary key, add primary key(Numero_de_prestamo, Numero_de_descuento, Cuenta);
-- Prestamo_caja
alter table Prestamo_caja add Cuenta varchar(100) after Numero_de_descuento;
update Prestamo_caja set Cuenta = 'multiasesoria';
alter table Prestamo_caja add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Prestamo_caja drop primary key, add primary key(Numero_de_prestamo, Numero_de_descuento, Cuenta);
-- Prestamo_cliente
alter table Prestamo_cliente add Cuenta varchar(100) after Numero_de_descuento;
update Prestamo_cliente set Cuenta = 'multiasesoria';
alter table Prestamo_cliente add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Prestamo_cliente drop primary key, add primary key(Numero_de_prestamo, Numero_de_descuento, Cuenta);
-- Prestamo_del_fondo_de_ahorro
alter table Prestamo_del_fondo_de_ahorro add Cuenta varchar(100) after Numero_de_descuento;
update Prestamo_del_fondo_de_ahorro set Cuenta = 'multiasesoria';
alter table Prestamo_del_fondo_de_ahorro add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Prestamo_del_fondo_de_ahorro drop primary key, add primary key(Numero_de_prestamo, Numero_de_descuento, Cuenta);
-- Prevision_social
drop table Servicio_Prevision_social;
drop table Prevision_social;
-- Prima
alter table Prima add Cuenta varchar(100);
update Prima set Cuenta = 'multiasesoria';
alter table Prima add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Prima drop primary key, add primary key(id, Cuenta);
-- Recibo_de_vacaciones
alter table Recibo_de_vacaciones add Cuenta varchar(100) after id;
update Recibo_de_vacaciones set Cuenta = 'multiasesoria';
alter table Recibo_de_vacaciones add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Recibo_de_vacaciones drop primary key, add primary key(id, Cuenta);
-- Regimen_fiscal
alter table Regimen_fiscal add Cuenta varchar(100);
update Regimen_fiscal set Cuenta = 'multiasesoria';
alter table Regimen_fiscal add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Regimen_fiscal drop primary key, add primary key(id, Empresa, Cuenta);
-- Registro_patronal
alter table Registro_patronal add Cuenta varchar(100) after Numero;
update Registro_patronal set Cuenta = 'multiasesoria';
alter table Registro_patronal add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Registro_patronal drop primary key, add primary key(Numero, Cuenta);
-- Representante_legal
alter table Representante_legal add Cuenta varchar(100);
update Representante_legal set Cuenta = 'multiasesoria';
alter table Representante_legal add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Representante_legal drop primary key, add primary key(Nombre, Empresa, Cuenta);
-- Resumen
alter table Resumen add Cuenta varchar(100);
update Resumen set Cuenta = 'multiasesoria';
alter table Resumen add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Resumen_aguinaldo
alter table Resumen_aguinaldo add Cuenta varchar(100);
update Resumen_aguinaldo set Cuenta = 'multiasesoria';
alter table Resumen_aguinaldo add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Retencion_FONACOT
alter table Retencion_FONACOT add Cuenta varchar(100);
update Retencion_FONACOT set Cuenta = 'multiasesoria';
alter table Retencion_FONACOT add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Retencion_FONACOT drop primary key, add primary key(id, Cuenta);
-- Retencion_INFONAVIT
alter table Retencion_INFONAVIT add Cuenta varchar(100);
update Retencion_INFONAVIT set Cuenta = 'multiasesoria';
alter table Retencion_INFONAVIT add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Retencion_INFONAVIT drop primary key, add primary key(id, Cuenta);
-- Salario_diario
alter table Salario_diario add Cuenta varchar(100);
update Salario_diario set Cuenta = 'multiasesoria';
alter table Salario_diario add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Salario_diario drop primary key, add primary key(id, Cuenta);
-- Salario_minimo
alter table Salario_minimo add Cuenta varchar(100);
update Salario_minimo set Cuenta = 'multiasesoria';
alter table Salario_minimo add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Salario_minimo drop primary key, add primary key(id, Cuenta);
-- Sello_digital
alter table Sello_digital add Cuenta varchar(100);
update Sello_digital set Cuenta = 'multiasesoria';
alter table Sello_digital add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Sello_digital drop primary key, add primary key(Numero_de_certificado, Cuenta);
-- Servicio
alter table Servicio add Cuenta varchar(100) after id;
update Servicio set Cuenta = 'multiasesoria';
alter table Servicio add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Servicio drop primary key, add primary key(id, Cuenta);
-- Servicio_adicional
alter table Servicio_adicional add Cuenta varchar(100);
update Servicio_adicional set Cuenta = 'multiasesoria';
alter table Servicio_adicional add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Servicio_adicional drop primary key, add primary key(id, Servicio, Cuenta);
-- Servicio_Empresa
alter table Servicio_Empresa add Cuenta varchar(100) after Empresa;
update Servicio_Empresa set Cuenta = 'multiasesoria';
alter table Servicio_Empresa add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Servicio_Empresa drop primary key, add primary key(Servicio, Empresa, Cuenta);
-- Servicio_Registro_patronal
alter table Servicio_Registro_patronal add Cuenta varchar(100) after Registro_patronal;
update Servicio_Registro_patronal set Cuenta = 'multiasesoria';
alter table Servicio_Registro_patronal add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Servicio_Registro_patronal drop primary key, add primary key(Servicio, Registro_patronal, Cuenta);
-- Servicio_Trabajador
alter table Servicio_Trabajador add Cuenta varchar(100) after Trabajador;
update Servicio_Trabajador set Cuenta = 'multiasesoria';
alter table Servicio_Trabajador add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Servicio_Trabajador drop primary key, add primary key(Servicio, Trabajador, Cuenta);
-- Sign
alter table Sign add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
-- Socio
alter table Socio add Cuenta varchar(100);
update Socio set Cuenta = 'multiasesoria';
alter table Socio add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Socio drop primary key, add primary key(Nombre, Empresa, Cuenta);
-- Sucursal
alter table Sucursal add Cuenta varchar(100);
update Sucursal set Cuenta = 'multiasesoria';
alter table Sucursal add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Sucursal drop primary key, add primary key(Nombre, Empresa, Cuenta);
-- Tabla_de_prestamo
drop table Tabla_de_prestamo;
-- Tipo
alter table Tipo add Cuenta varchar(100);
update Tipo set Cuenta = 'multiasesoria';
alter table Tipo add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Tipo drop primary key, add primary key(id, Cuenta);
-- Trabajador
alter table Trabajador add Cuenta varchar(100);
update Trabajador set Cuenta = 'multiasesoria';
alter table Trabajador add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Trabajador drop primary key, add primary key(RFC, Cuenta);
-- Trabajador_Prestamo_administradora
alter table Trabajador_Prestamo_administradora add Cuenta varchar(100);
update Trabajador_Prestamo_administradora set Cuenta = 'multiasesoria';
alter table Trabajador_Prestamo_administradora add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Trabajador_Prestamo_administradora drop primary key, add primary key(Prestamo_administradora, Cuenta);
-- Trabajador_Prestamo_caja
alter table Trabajador_Prestamo_caja add Cuenta varchar(100);
update Trabajador_Prestamo_caja set Cuenta = 'multiasesoria';
alter table Trabajador_Prestamo_caja add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Trabajador_Prestamo_caja drop primary key, add primary key(Prestamo_caja, Cuenta);
-- Trabajador_Prestamo_cliente
alter table Trabajador_Prestamo_cliente add Cuenta varchar(100);
update Trabajador_Prestamo_cliente set Cuenta = 'multiasesoria';
alter table Trabajador_Prestamo_cliente add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Trabajador_Prestamo_cliente drop primary key, add primary key(Prestamo_cliente, Cuenta);
-- Trabajador_Prestamo_del_fondo_de_ahorro
alter table Trabajador_Prestamo_del_fondo_de_ahorro add Cuenta varchar(100);
update Trabajador_Prestamo_del_fondo_de_ahorro set Cuenta = 'multiasesoria';
alter table Trabajador_Prestamo_del_fondo_de_ahorro add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Trabajador_Prestamo_del_fondo_de_ahorro drop primary key, add primary key(Prestamo_del_fondo_de_ahorro, Cuenta);
-- Trabajador_Salario_minimo
alter table Trabajador_Salario_minimo add Cuenta varchar(100);
update Trabajador_Salario_minimo set Cuenta = 'multiasesoria';
alter table Trabajador_Salario_minimo add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Trabajador_Salario_minimo drop primary key, add primary key(Trabajador, Servicio, Fecha, Cuenta);
-- Trabajador_Sucursal
alter table Trabajador_Sucursal add Cuenta varchar(100);
update Trabajador_Sucursal set Cuenta = 'multiasesoria';
alter table Trabajador_Sucursal add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Trabajador_Sucursal drop primary key, add primary key(Trabajador, Nombre, Empresa, Cuenta);
-- UMF
alter table UMF add Cuenta varchar(100);
update UMF set Cuenta = 'multiasesoria';
alter table UMF add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table UMF drop primary key, add primary key(id, Cuenta);
-- Usuario (ready)
-- Vacaciones
alter table Vacaciones add Cuenta varchar(100);
update Vacaciones set Cuenta = 'multiasesoria';
alter table Vacaciones add foreign key(Cuenta) references Cuenta(Nombre) on delete cascade on update cascade;
alter table Vacaciones drop primary key, add primary key(id, Cuenta);
-- FOREIGN KEYS
-- Aguinaldo
alter table Aguinaldo drop foreign key Aguinaldo_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- aguinaldo_asalariados
alter table aguinaldo_asalariados drop foreign key aguinaldo_asalariados_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table aguinaldo_asalariados drop foreign key aguinaldo_asalariados_ibfk_2, add foreign key (Aguinaldo, Cuenta) references Aguinaldo(id, Cuenta) on delete cascade on update cascade;
-- Apoderado
alter table Apoderado drop foreign key Apoderado_ibfk_1, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Aportacion_del_trabajador_al_fondo_de_ahorro
alter table Aportacion_del_trabajador_al_fondo_de_ahorro drop foreign key Aportacion_del_trabajador_al_fondo_de_ahorro_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Aportacion_del_trabajador_al_fondo_de_ahorro drop foreign key Aportacion_del_trabajador_al_fondo_de_ahorro_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
-- Archivo_digital
alter table Archivo_digital drop foreign key Archivo_digital_ibfk_3, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
alter table Archivo_digital drop foreign key Archivo_digital_ibfk_4, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
-- Baja
alter table Baja drop foreign key Baja_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Baja drop foreign key Baja_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
-- Banco
alter table Banco drop foreign key Banco_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Banco drop foreign key Banco_ibfk_2, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Base
alter table Base drop foreign key Base_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Base drop foreign key Base_ibfk_2, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- CFDI_Trabajador
alter table CFDI_Trabajador drop foreign key CFDI_Trabajador_ibfk_1, add foreign key (Emisor, Cuenta) references Empresa(RFC, Cuenta) on delete restrict on update cascade;
alter table CFDI_Trabajador drop foreign key CFDI_Trabajador_ibfk_2, add foreign key (Receptor, Cuenta) references Trabajador(RFC, Cuenta) on delete restrict on update cascade;
-- Contrato
alter table Contrato drop foreign key Contrato_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Contrato drop foreign key Contrato_ibfk_2, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- cuotas_IMSS
alter table cuotas_IMSS drop foreign key cuotas_IMSS_ibfk_2, add foreign key (Nomina, Cuenta) references Nomina(id, Cuenta) on delete cascade on update cascade;
alter table cuotas_IMSS drop foreign key cuotas_IMSS_ibfk_3, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
-- Descuento_pendiente (ready)
-- Empresa (ready)
-- Factor_de_descuento
alter table Factor_de_descuento drop foreign key Factor_de_descuento_ibfk_1, add foreign key (Retencion_INFONAVIT, Cuenta) references Retencion_INFONAVIT(id, Cuenta) on delete cascade on update cascade;
-- Finiquito
alter table Finiquito drop foreign key Finiquito_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Finiquito drop foreign key Finiquito_ibfk_2, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Fondo_de_garantia
alter table Fondo_de_garantia drop foreign key Fondo_de_garantia_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta);
alter table Fondo_de_garantia drop foreign key Fondo_de_garantia_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta);
-- Incapacidad
alter table Incapacidad drop foreign key Incapacidad_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Incapacidad drop foreign key Incapacidad_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- incidencias
alter table incidencias drop foreign key incidencias_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table incidencias drop foreign key incidencias_ibfk_1, add foreign key (Nomina, Cuenta) references Nomina(id, Cuenta) on delete cascade on update cascade;
-- Instrumento_notarial
alter table Instrumento_notarial drop foreign key Instrumento_notarial_ibfk_2, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- ISRaguinaldo
alter table ISRaguinaldo drop foreign key ISRaguinaldo_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table ISRaguinaldo drop foreign key ISRaguinaldo_ibfk_2, add foreign key (Aguinaldo, Cuenta) references Aguinaldo(id, Cuenta) on delete cascade on update cascade;
-- ISRasalariados
alter table ISRasalariados drop foreign key ISRasalariados_ibfk_3, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table ISRasalariados drop foreign key ISRasalariados_ibfk_2, add foreign key (Nomina, Cuenta) references Nomina(id, Cuenta) on delete cascade on update cascade;
-- ISRasimilables
alter table ISRasimilables drop foreign key ISRasimilables_ibfk_3, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table ISRasimilables drop foreign key ISRasimilables_ibfk_2, add foreign key (Nomina, Cuenta) references Nomina(id, Cuenta) on delete cascade on update cascade;
-- Logo
alter table Logo drop foreign key Logo_ibfk_1, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Monto_fijo_mensual
alter table Monto_fijo_mensual drop foreign key Monto_fijo_mensual_ibfk_1, add foreign key (Retencion_INFONAVIT, Cuenta) references Retencion_INFONAVIT(id, Cuenta) on delete cascade on update cascade;
-- Nomina
alter table Nomina drop foreign key Nomina_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- nomina_asalariados
alter table nomina_asalariados drop foreign key nomina_asalariados_ibfk_3, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table nomina_asalariados drop foreign key nomina_asalariados_ibfk_2, add foreign key (Nomina, Cuenta) references Nomina(id, Cuenta) on delete cascade on update cascade;
-- nomina_asimilables
alter table nomina_asimilables drop foreign key nomina_asimilables_ibfk_3, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table nomina_asimilables drop foreign key nomina_asimilables_ibfk_2, add foreign key (Nomina, Cuenta) references Nomina(id, Cuenta) on delete cascade on update cascade;
-- Pago_por_seguro_de_vida
alter table Pago_por_seguro_de_vida drop foreign key Pago_por_seguro_de_vida_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Pago_por_seguro_de_vida drop foreign key Pago_por_seguro_de_vida_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Pension_alimenticia
alter table Pension_alimenticia drop foreign key Pension_alimenticia_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Pension_alimenticia drop foreign key Pension_alimenticia_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Photo
alter table Photo drop foreign key Photo_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
-- Porcentaje_de_descuento
alter table Porcentaje_de_descuento drop foreign key Porcentaje_de_descuento_ibfk_1, add foreign key (Retencion_INFONAVIT, Cuenta) references Retencion_INFONAVIT(id, Cuenta) on delete cascade on update cascade;
-- prestaciones
alter table prestaciones drop foreign key prestaciones_ibfk_3, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table prestaciones drop foreign key prestaciones_ibfk_2, add foreign key (Nomina, Cuenta) references Nomina(id, Cuenta) on delete cascade on update cascade;
-- Prestamo_administradora (ready)
-- Prestamo_caja (ready)
-- Prestamo_cliente (ready)
-- Prestamo_del_fondo_de_ahorro (ready)
-- Prima
alter table Prima drop foreign key Prima_ibfk_1, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Recibo_de_vacaciones (ready)
-- Regimen_fiscal
alter table Regimen_fiscal drop foreign key Regimen_fiscal_ibfk_1, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Registro_patronal
alter table Registro_patronal drop foreign key Registro_patronal_ibfk_3;
alter table Registro_patronal drop foreign key Registro_patronal_ibfk_4;
alter table Registro_patronal add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
alter table Registro_patronal add foreign key (Sucursal, Empresa, Cuenta) references Sucursal(Nombre, Empresa, Cuenta) on delete cascade on update cascade;
-- Representante_legal
alter table Representante_legal drop foreign key Representante_legal_ibfk_1, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Resumen
alter table Resumen drop foreign key Resumen_ibfk_1, add foreign key (Nomina, Cuenta) references Nomina(id, Cuenta) on delete cascade on update cascade;
-- Resumen_aguinaldo
alter table Resumen_aguinaldo drop foreign key Resumen_aguinaldo_ibfk_1, add foreign key (Aguinaldo, Cuenta) references Aguinaldo(id, Cuenta) on delete cascade on update cascade;
-- Retencion_FONACOT
alter table Retencion_FONACOT drop foreign key Retencion_FONACOT_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Retencion_FONACOT drop foreign key Retencion_FONACOT_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Retencion_INFONAVIT
alter table Retencion_INFONAVIT drop foreign key Retencion_INFONAVIT_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Retencion_INFONAVIT drop foreign key Retencion_INFONAVIT_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Salario_diario
alter table Salario_diario drop foreign key Salario_diario_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Salario_diario drop foreign key Salario_diario_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Salario_minimo (ready)
-- Sello_digital
alter table Sello_digital drop foreign key Sello_digital_ibfk_1, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Servicio (ready)
-- Servicio_adicional
alter table Servicio_adicional drop foreign key Servicio_adicional_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Servicio_Empresa
alter table Servicio_Empresa add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Servicio_Empresa add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Servicio_Registro_patronal
alter table Servicio_Registro_patronal drop foreign key Servicio_Registro_patronal_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Servicio_Registro_patronal drop foreign key Servicio_Registro_patronal_ibfk_2, add foreign key (Registro_patronal, Cuenta) references Registro_patronal(Numero, Cuenta) on delete cascade on update cascade;
-- Servicio_Trabajador
alter table Servicio_Trabajador drop foreign key Servicio_Trabajador_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Servicio_Trabajador drop foreign key Servicio_Trabajador_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
-- Sign
alter table Sign drop foreign key Sign_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
-- Socio
alter table Socio drop foreign key Socio_ibfk_1, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Sucursal
alter table Sucursal drop foreign key Sucursal_ibfk_1, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- Tipo
alter table Tipo drop foreign key Tipo_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Tipo drop foreign key Tipo_ibfk_2, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Trabajador (ready)
-- Trabajador_Prestamo_administradora
alter table Trabajador_Prestamo_administradora drop foreign key Trabajador_Prestamo_administradora_ibfk_4, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Prestamo_administradora drop foreign key Trabajador_Prestamo_administradora_ibfk_3, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Prestamo_administradora drop foreign key Trabajador_Prestamo_administradora_ibfk_2;
-- Trabajador_Prestamo_caja
alter table Trabajador_Prestamo_caja drop foreign key Trabajador_Prestamo_caja_ibfk_4, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Prestamo_caja drop foreign key Trabajador_Prestamo_caja_ibfk_3, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Prestamo_caja drop foreign key Trabajador_Prestamo_caja_ibfk_2;
-- Trabajador_Prestamo_cliente
alter table Trabajador_Prestamo_cliente drop foreign key Trabajador_Prestamo_cliente_ibfk_4, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Prestamo_cliente drop foreign key Trabajador_Prestamo_cliente_ibfk_3, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Prestamo_cliente drop foreign key Trabajador_Prestamo_cliente_ibfk_2;
-- Trabajador_Prestamo_del_fondo_de_ahorro
alter table Trabajador_Prestamo_del_fondo_de_ahorro drop foreign key Trabajador_Prestamo_del_fondo_de_ahorro_ibfk_3, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Prestamo_del_fondo_de_ahorro drop foreign key Trabajador_Prestamo_del_fondo_de_ahorro_ibfk_2, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Prestamo_del_fondo_de_ahorro drop foreign key Trabajador_Prestamo_del_fondo_de_ahorro_ibfk_1;
-- Trabajador_Salario_minimo
alter table Trabajador_Salario_minimo drop foreign key Trabajador_Salario_minimo_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Salario_minimo drop foreign key Trabajador_Salario_minimo_ibfk_2, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Trabajador_Sucursal
alter table Trabajador_Sucursal drop foreign key Trabajador_Sucursal_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Sucursal drop foreign key Trabajador_Sucursal_ibfk_2, add foreign key (Nombre, Empresa, Cuenta) references Sucursal(Nombre, Empresa, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Sucursal drop foreign key Trabajador_Sucursal_ibfk_4, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
alter table Trabajador_Sucursal drop foreign key Trabajador_Sucursal_ibfk_5, add foreign key (Empresa, Cuenta) references Empresa(RFC, Cuenta) on delete cascade on update cascade;
-- UMF
alter table UMF drop foreign key UMF_ibfk_1, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table UMF drop foreign key UMF_ibfk_2, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Usuario (ready)
-- Vacaciones
alter table Vacaciones drop foreign key Vacaciones_ibfk_2, add foreign key (Trabajador, Cuenta) references Trabajador(RFC, Cuenta) on delete cascade on update cascade;
alter table Vacaciones drop foreign key Vacaciones_ibfk_1, add foreign key (Servicio, Cuenta) references Servicio(id, Cuenta) on delete cascade on update cascade;
-- Events
