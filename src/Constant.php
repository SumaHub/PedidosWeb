<?php

namespace App;

class Constant
{
    /**
     * Grupo Empresarial por defecto
     * @return int Grupo SUMA
     */
    public const AD_Client_ID = 1000001;

    /**
     * Tercero por defecto
     * @return int Estandar
     */
    public const C_BPartner_ID = 1000000;

    /**
     * Direccion del Tercero por defecto
     * @return int Valencia
     */
    public const C_BPartner_Location_ID = 1000000;

    /**
     * Direccion del Tercero por defecto
     * @return int Valencia
     */
    public const C_Location_ID = 1000000;

    /**
     * Tasa de Conversion por defecto 
     * @return int USD
     */
    public const C_ConversionType_ID = 1000001;

    /**
     * Moneda por defecto 
     * @return int USD
     */
    public const C_Currency_ID = 100;

    /**
     * Documento por defecto
     * @return int Orden Ventas Nacionales
     */
    public const C_DocType_ID = 1000068;

    /**
     * Documento por defecto
     * @return int Orden Ventas Nacionales
     */
    public const C_DocTypeTarget_ID = 1000068;

    /**
     * Termino de Pago por defecto
     * @return int CONTADO
     */
    public const C_PaymentTerm_ID = 1000005;

    /**
     * Estatus de Documento por defecto
     * @return int CONTADO
     */
    public const DocStatus = 'DR';
}
