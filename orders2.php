<?php
require_once('app/Mage.php');
Mage::app('admin');
Mage::register('isSecureArea',true);

$orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
$order = Mage::getSingleton('sales/order')->loadByIncrementId($orderId);


echo "order subtotal: ".$order->getSubtotal()."<br>";
echo "shipping: ".$order->getShippingAmount()."<br>";
echo "discount: ".$order->getDiscountAmount()."<br>";
echo "tax: ".$order->getTaxAmount()."<br>";
echo "grand total".$order->getGrandTotal()."<br><br><br>";
echo "Complete Order detail:<br>".print_r($order->debug(),true)."<br>";

$orderItems = array();
foreach($order->getItemsCollection() as $item)
{
    //$product = Mage::getModel('catalog/product')->load($item->getProductId());
    $row=array();
    $row['sku'] = $item->getSku();
    $row['original_price'] = $item->getOriginalPrice();
    $row['price'] = $item->getPrice();
    $row['qty_ordered']= (int)$item->getQtyOrdered();
    $row['subtotal']= $item->getSubtotal();
    $row['tax_amount']= $item->getTaxAmount();
    $row['tax_percent']= $item->getTaxPercent();
    $row['discount_amount']= $item->getDiscountAmount();
    $row['row_total']= $item->getRowTotal();
    $orderItems[]=$row;
}
