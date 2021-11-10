<?php

namespace App\Model;

use App\Controller\DBController;

class Product
{
    public $code = false;

    public $msj = false;

    public function add_image(
        String $codigo = '',
        String $name = '',
        String $patch = ''
    )
    {
        $db = DBController::conectar();

        if ($db->IsConnected()) {
            // Begin transaction
            $db->BeginTrans();

            // Get empty recordset
            $record = $db->Execute("SELECT * FROM M_ProductDownload WHERE M_ProductDownload_ID = -1");

            // Default Values
            $date = $db->GetOne("SELECT current_timestamp");

            // Build an array with image data 
            $image = array(
                "M_ProductDownload_ID"  => $db->GetOne("SELECT nextid(1111, 'N')"),
                "AD_Client_ID"          => 1000001,
                "AD_Org_ID"             => 0,
                "IsActive"              => 'Y',
                "Created"               => $date,
                "CreatedBy"             => $_SESSION['user']['ad_user_id'],
                "Updated"               => $date,
                "UpdatedBy"             => $_SESSION['user']['ad_user_id'],
                "M_Product_ID"          => $db->GetOne("SELECT M_Product_ID FROM M_Product WHERE Value = '{$codigo}'"),
                "Name"                  => $name,
                "DownloadURL"           => 'http://catalogo.sumagroups.com' . $patch . '/' . $codigo . '/' . $name,
                "M_ProductDownload_UU"  => $db->GetOne("SELECT uuid_generate_v4()")
            );

            // Generate and execute SQL query
            $this->code = ( $db->Execute( $db->GetInsertSQL($record, $image) ) ) ? $db->CommitTrans() : $db->RollbackTrans();
            
            // Verify the transaction status
            $this->msj  = $this->code ? null : $db->ErrorMsg();

        } else {
            $this->msj = $db->ErrorMsg();
        }

        $db->Close();
    }

    public function delete_image(
        String $image = ''
    )
    {
        $db = DBController::conectar();
        $msj= null;

        if ($db->IsConnected()) {
            // Begin transaction
            $db->BeginTrans();

            // Get empty recordset
            $record = $db->Execute("SELECT * FROM M_ProductDownload WHERE Name = '{$image}'");

            // Build an array with image data 
            $image  = array(
                'IsActive'  => 'N',
                'Updated'   => $db->GetOne("SELECT current_timestamp"),
                'UpdatedBy' => $_SESSION['user']['ad_user_id']
            );

            // Generate and execute SQL query
            $this->code = ( $db->Execute( $db->GetUpdateSQL($record, $image) ) ) ? $db->CommitTrans() : $db->RollbackTrans() ;
                 
            // Verify the transaction status
            $this->msj = $this->code ? null : $db->ErrorMsg();
                
        } else {
            $this->msj = $db->ErrorMsg();
        }

        $db->Close();
    }

    public function get_images(
        String $Value = '',
        Int $limit = 10
    )
    {
        $db = DBController::conectar();
        $msj    = null;

        if ($db->IsConnected()) {
            $rs = $db->Execute(
                "SELECT mpd.*, REPLACE(mpd.Name, '.', '_') as id, mpd.Created::date, mp.Value as Codigo, mp.SKU, sm.Name as marca
                FROM M_ProductDownload mpd 
                JOIN M_Product mp ON mp.M_Product_ID = mpd.M_Product_ID 
                JOIN SM_Marca sm ON sm.SM_Marca_ID = mp.SM_Marca_ID 
                WHERE mpd.IsActive = 'Y' AND mp.Value = '{$Value}'
                LIMIT {$limit}"
            );
        } else { 
            $msj= $db->ErrorMsg();
        }

        $db->Close();
        return is_null($msj) ? $rs : $msj ;
    }

    public function get_product(
        String $Value = '',
        Int $M_PriceList_ID = 0
    )
    {
        $db = DBController::conectar();
        $msj    = null;
        
        if($db->IsConnected()){
            $rs = $db->GetRow(
                "SELECT mp.M_Product_ID AS id, mp.M_Product_ID, mp.Name AS nombre, mp.Value AS codigo, mp.SKU, 
                mp.C_UOM_ID, mp.M_AttributeSetInstance_ID,
                sm.Name AS marca, smp.Name AS modelo, mpc.Name AS categoria, smt.Name AS subcategoria, mp.IsActive,
                mp.Created::date AS creado, auc.Name AS creadopor, mp.Updated::date AS actualizado, auu.Name AS actualizadopor,
                mpp.PriceList::numeric, mpp.PriceStd::numeric, mpp.PriceLimit::numeric, 
                mpp.PriceList::numeric AS priceactual, mpp.PriceList::numeric AS priceentered
                FROM M_Product mp 
                JOIN SM_Marca sm ON sm.SM_Marca_ID = mp.SM_Marca_ID 
                JOIN SM_Modelo_Producto smp ON smp.SM_Modelo_Producto_ID = mp.SM_Modelo_Producto_ID 
                JOIN M_Product_Category mpc ON mpc.M_Product_Category_ID = mp.M_Product_Category_ID
                LEFT JOIN SM_ProducType smt ON smt.SM_ProducType_ID = mp.SM_ProducType_ID
                JOIN M_ProductPrice mpp ON mpp.M_Product_ID = mp.M_Product_ID 
                JOIN M_PriceList_Version mplv ON mplv.M_PriceList_Version_ID = mpp.M_PriceList_Version_ID
                JOIN M_PriceList mpl ON mpl.M_PriceList_ID = mplv.M_PriceList_ID
                JOIN AD_User auc ON auc.AD_User_ID = mp.CreatedBy
                JOIN AD_User auu ON auu.AD_User_ID = mp.UpdatedBy
                WHERE 
                    mp.IsSold = 'Y' AND mp.IsActive = 'Y' AND mplv.IsActive = 'Y' AND mpp.IsActive = 'Y' 
                    AND LOWER(mp.Value) = LOWER('{$Value}') 
                    AND CASE WHEN {$M_PriceList_ID} > 0 THEN mpl.M_PriceList_ID = {$M_PriceList_ID} ELSE true END
                LIMIT 1"
            );
        } else {
            $msj = $db->ErrorMsg();
        }

        return is_null($msj) ? $rs : $msj ;
    }

    public static function get_products(
        String $code = '',
        Int $AD_Org_ID = 0,
        Int $M_PriceList_ID = 0,
        Int $SalesRep_ID = null,
        Int $limit = 5
    )
    {
        $db = DBController::conectar();
        $msj    = null;

        if($db->IsConnected()){
            $SalesRep_ID = $SalesRep_ID ?: $_SESSION['user']['ad_user_id'];
            $rs = $db->Execute(
                "SELECT DISTINCT 
                    mp.M_Product_ID, mp.Name AS product, mp.Value, mp.SKU,
                    sm.Name AS brand, mpc.Name AS category, SUM(ms.QtyOnHand) AS qty, mpp.PriceList::numeric, mp.IsActive,
                    (SELECT mpd.downloadurl FROM M_ProductDownload mpd WHERE mpd.M_Product_ID = mp.M_Product_ID LIMIT 1) AS Image,
                    (SELECT COUNT(mpd.*) FROM M_ProductDownload mpd WHERE mpd.M_Product_ID = mp.M_Product_ID) AS QtyImage
                FROM 
                    M_Product mp 
                    JOIN SM_Marca sm ON sm.SM_marca_ID = mp.SM_marca_ID 
                    JOIN SM_Modelo_Producto smp ON smp.SM_Modelo_Producto_ID = mp.SM_Modelo_Producto_ID 
                    JOIN M_Product_Category mpc ON mpc.M_Product_Category_ID = mp.M_Product_Category_ID
                    LEFT JOIN SM_Productype smt ON smt.SM_ProducType_ID = mp.SM_ProducType_ID
                    JOIN M_Storage ms ON ms.M_Product_ID = mp.M_Product_ID
                    JOIN M_Locator ml ON ml.M_Locator_ID = ms.M_Locator_ID
                    JOIN M_LocatorType mlt ON mlt.M_LocatorType_ID = ml.M_LocatorType_ID
                    JOIN M_ProductPrice mpp ON mpp.M_Product_ID = mp.M_Product_ID 
                    JOIN M_PriceList_Version mplv ON mplv.M_PriceList_Version_ID = mpp.M_PriceList_Version_ID
                    JOIN M_PriceList mpl ON mpl.M_PriceList_ID = mplv.M_PriceList_ID AND mpl.AD_Org_ID = ms.AD_Org_ID
                    -- Acceso a la Org
                    JOIN WS_User_OrgAccess_v wuo ON wuo.AD_Org_ID = ms.AD_Org_ID AND wuo.AD_User_ID = {$SalesRep_ID}
                WHERE 
                    mp.IsSold = 'Y' AND mp.IsActive = 'Y' AND mlt.IsAvailableForShipping = 'Y' AND sm.SM_Marca_ID NOT IN (1000005, 1000007, 1000009, 1000011)
                    AND mpl.IsActive = 'Y' AND mplv.IsActive = 'Y' AND mpp.IsActive = 'Y'
                    AND CASE WHEN '{$code}' != '' 
                        THEN LOWER(mp.Name) LIKE LOWER('%{$code}%') OR LOWER(mp.Value) LIKE LOWER('%{$code}%') OR LOWER(mp.SKU) LIKE LOWER('%{$code}%')
                        ELSE true 
                    END 
                    AND CASE WHEN {$AD_Org_ID} > 0 THEN ms.AD_Org_ID = {$AD_Org_ID} ELSE true END
                    AND CASE WHEN {$M_PriceList_ID} > 0 THEN mpl.M_PriceList_ID = {$M_PriceList_ID} ELSE true END
                GROUP BY mp.M_Product_ID, mp.Name, mp.Value, mp.SKU, sm.Name, mpc.Name, mpp.PriceList, mp.IsActive
                ORDER BY mp.M_Product_ID DESC
                LIMIT {$limit}"
            );
        } else {
            $msj = $db->ErrorMsg();
        }

        $db->Close();
        return is_null($msj) ? $rs : $msj ;
    }

    /**
     * Obtiene la cantidad disponible de un 
     * producto segun una organizacion
     * 
     * @param int $AD_Org_ID Identificador de la organizacion
     * @param int $M_Warehouse_ID Identificador del almacen
     * @param int $M_Product_ID Identificador del producto
     * 
     * @return int Cantidad disponible
     */
    public function get_storage(
        Int $AD_Org_ID,
        Int $M_Warehouse_ID,
        Int $M_Product_ID
    )
    {
        $db = DBController::conectar();
        $msj    = null;

        if($db->IsConnected())
            $qty = $db->GetOne(
                "SELECT SUM(ms.QtyOnHand) 
                FROM M_Storage ms 
                JOIN M_Locator ml ON ml.M_Locator_ID = ms.M_Locator_ID 
                JOIN M_LocatorType mlt ON mlt.M_LocatorType_ID = ml.M_LocatorType_ID  
                WHERE mlt.IsAvailableForShipping = 'Y' AND ms.IsActive = 'Y' AND ms.AD_Org_ID = {$AD_Org_ID} AND ml.M_Warehouse_ID = {$M_Warehouse_ID} AND ms.M_Product_ID = {$M_Product_ID}"
            );
        else
            $msj = $db->ErrorMsg();

        return $msj ?? $qty;
    }

    public static function get_tax(
        Int $M_Product_ID = 0
    )
    {
        $db = DBController::conectar();
        $msj    = null;

        if($db->IsConnected()){
            $tax = $db->GetOne(
                "SELECT ct.C_Tax_ID 
                FROM C_Tax ct 
                JOIN M_Product mp ON mp.C_TaxCategory_ID = ct.C_TaxCategory_ID 
                WHERE ct.IsActive = 'Y' AND ct.IsDefault = 'Y' AND mp.M_Product_ID = {$M_Product_ID}
                LIMIT 1"
            );
        } else {
            $msj = $db->ErrorMsg();
        }

        return $msj ?? $tax;
    }

    /**
     * Obtiene los productos que no poseen un
     * precio para la venta en el sistema
     * 
     * @param int $M_Product_ID Identificador del producto
     * 
     * @return string|ADORecordSet Mensaje de error | Productos
     */
    public function get_without_price(
        Int $M_Product_ID = 0
    )
    {
        $db = DBController::conectar();
        $msj    = null;

        if($db->IsConnected()){
            $products = $db->Execute(
                "SELECT 
                    sm.Name AS Brand, ao.Name AS Organization, 
                    cd.Name AS DocType, co.DocumentNo AS Order, co.DocStatus AS OrderStatus, co.SM_Preform, co.Description AS OrderDescription,
                    co.SM_ContainerQty, co.Totallines, co.Grandtotal,
                    co.C_Order_ID, co.DateOrdered::date, co.DatePromised::date, 
                    cb.C_BPartner_ID, cb.Name AS BPartner,
                    mp.M_Product_ID, mp.Value, mp.SKU, mp.Description,
                    col.C_Orderline_ID, col.QtyOrdered, col.QtyReserved,
                    mil.M_Inoutline_ID, mi.M_Inout_ID, mi.DocumentNo AS Receipt, mi.DateAcct::date AS ReceiptDateAcct, mi.SM_Fecha_ETA, mi.SM_Fecha_ETD, mi.DocStatus AS ReceiptStatus, coalesce(mil.qtyentered, 0) AS QtyReceipt, mi.SM_InvoiceDocumentNo, mi.SM_InvoiceFiles,
                    milc.M_InoutlineConfirm_ID, mic.M_InoutConfirm_ID, mic.DocumentNo AS Confirm, mic.DocStatus AS ConfirmStatus,
                    cil.C_Invoiceline_ID, ci.C_Invoice_ID, ci.DocumentNo AS Invoice, ci.DateAcct::date AS InvoiceDateAcct, ci.DocStatus AS InvoiceStatus, coalesce(cil.QtyEntered, 0) AS QtyInvoiced,
                    CASE WHEN np.IsNew = 'Y' THEN 'Nuevo' WHEN np.IsResupply = 'Y' THEN 'Reabastecimiento' ELSE '' END AS Status, np.por_llegar AS IsComing
                FROM SM_RV_NewProduct_v np
                JOIN SM_Marca sm ON sm.SM_Marca_ID = np.SM_Marca_ID
                JOIN AD_Org ao ON ao.AD_Org_ID = np.AD_Org_ID
                JOIN C_Order co ON co.C_Order_ID = np.C_Order_ID
                JOIN C_Orderline col ON col.C_Orderline_ID = np.C_Orderline_ID
                LEFT JOIN M_InOutLine mil ON mil.C_Orderline_ID = col.C_Orderline_ID
                LEFT JOIN M_Inout mi ON mi.M_Inout_ID = mil.M_Inout_ID
                LEFT JOIN M_InoutlineConfirm milc ON milc.M_Inoutline_ID = mil.M_Inoutline_ID
                LEFT JOIN M_InoutConfirm mic ON mic.M_InoutConfirm_ID = milc.M_InoutConfirm_ID
                LEFT JOIN C_Invoiceline cil ON cil.M_Inoutline_ID = mil.M_Inoutline_ID or cil.C_Orderline_ID = col.C_Orderline_ID
                LEFT JOIN C_Invoice ci ON ci.C_Invoice_ID = cil.C_Invoice_ID
                JOIN C_BPartner cb ON cb.C_BPartner_ID = co.C_BPartner_ID
                JOIN M_Product mp ON mp.M_Product_ID = np.M_Product_ID
                JOIN C_DocType cd ON cd.C_DocType_ID = co.C_DocType_ID
                WHERE co.IsSOTrx = 'N' AND CASE WHEN $M_Product_ID > 0 THEN np.M_Product_ID = $M_Product_ID ELSE true END"
            );
        } else {
            $msj = $db->ErrorMsg();
        }

        return $msj ?? $products;
    }

    /**
     * Obtiene la cantidad de productos registrados 
     * por organizacion
     * 
     * @param int $AD_Org_ID Identificador de la organizacion
     * 
     * @return int Cantidad de productos
     */
    public static function qty_products(
        Int $AD_Org_ID = null
    )
    {
        $db = DBController::conectar();
        $products = null;

        if($db->IsConnected()){
            $AD_Org_ID = $AD_Org_ID ?: $_SESSION['organization']['ad_org_id'];

            $products = $db->GetOne(
                "SELECT 
                    COUNT(mp.*) 
                FROM 
                    M_Product mp
                    JOIN M_Storage ms ON ms.M_Product_ID = mp.M_Product_ID
                    JOIN M_Locator ml ON ml.M_Locator_ID = ms.M_Locator_ID
                    JOIN M_LocatorType mlt ON mlt.M_LocatorType_ID = ml.M_LocatorType_ID
                WHERE 
                    mp.IsActive = 'Y' AND mp.IsSold = 'Y' AND mp.SM_Marca_ID NOT IN (1000005, 1000007, 1000009, 1000011)
                    AND CASE WHEN {$AD_Org_ID} > 0 THEN ms.AD_Org_ID = {$AD_Org_ID} ELSE true END
                    AND mlt.IsAvailableForShipping = 'Y'"
            );
        } 

        $db->Close();
        return $products ?? 0;
    }
}

?>