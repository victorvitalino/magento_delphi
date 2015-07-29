<?php
require_once('app/Mage.php'); //Path da class do magento Magento
Mage::app();
$product_sku = '0001';
$product_id = Mage::getModel("catalog/product")->getIdBySku( $product_sku );


function delete($sku) {

}

function create() {

}

function updates($price, $product_id)
    {
        try {
            $product = Mage::getModel('catalog/product');
            $productId = $product_id;
            $product->load($productId);
            if ($product && $product->getId()) {
                $product->setPrice(strval($price));
                $product->save();
                echo 'foi salvo ';
            }
        } catch (Exception $e) {
            Mage::logException($e);
            echo 'fudeu';
        }

        echo 'aeee porra!!!';
    }

updates('10,50', $product_id)
?>
