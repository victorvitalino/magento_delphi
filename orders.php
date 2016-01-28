<?php
require_once('app/Mage.php');
Mage::app('admin');
Mage::register('isSecureArea',true);
$collection = Mage::getResourceModel('sales/order_collection')
->addAttributeToSelect('*');
//->addFieldToFilter('hpc_order_id', array('neq' => '',))
//->addFieldToFilter('hpc_order_from', array('eq' => 'ebay',)));
//->addFieldToFilter('status', 'complete')->load(); //pending,complete
//$collection->addAttributeToSort('created_at', 'desc');
$itens = array();
foreach ($collection as $col) {
    $id = $col->getIncrementId();
    $order = Mage::getModel('sales/order')->loadByIncrementId($id);
    $address = Mage::getModel('sales/order_address')->load($order[entity_id]);
    echo "<br/>";
    echo "<br/>";
    echo "<br/>";
    $itens = array("ID_PEDIDO"=>"","CPF_NOTA"=>"TRUE","NOME_CLIENTE"=> $order[customer_firstname]." ".$order[customer_lastname],"CPF_CLIENTE"=>"00000000000",
    "ENDERECO"=>$address[street],"COMPLEMENTO"=>"","BAIRRO"=>"","CIDADE"=>$address[city],"UF"=>"","CEP"=>$address[postcode],"EMAIL"=>$address[email],"COD_IBGE"=>"5300108",
    "TELEFONE"=>$address[telephone],"ACRESCIMO"=>"2.50", "DATA_PEDIDO"=>$order[created_at], "OBSERVACAO"=>"","FORMA_PAGTO"=>"");
       echo "<br/>";
       echo "<br/>";
      //echo json_encode($order);
    //  print_r($order->getAllItems());
    $orderValue = number_format ($order->getGrandTotal(), 2, '.' , $thousands_sep = '');
    $items = $order->getAllItems();
    $orderItems = $order->getItemsCollection();
    foreach ($orderItems as $item){
      $product_id = $item->product_id;
      $product_sku = $item->sku;
      $itens["PRODUTOS"] = array();
      $product_name = $item->getName();
      $_product = Mage::getModel('catalog/product')->load($product_id);
      $cats = $_product->getCategoryIds();
      $category_id = $cats[0];
      $category = Mage::getModel('catalog/category')->load($category_id);
      $category_name = $category->getName();

      foreach ($items as $itemId => $item)
      {
          array_push($itens["PRODUTOS"], $item->getName(), $item->getPrice());
          // echo $item->getName();
          // echo $item->getPrice();
          // echo $item->getSku();
          // echo $item->getProductId();
          // echo $item->getQtyOrdered();
          //echo $item->getQtyToInvoice();
      }

    }
          echo json_encode($itens);
}
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
</head>
<body>

<script type="text/javascript">
jQuery(document).ready(function(){
var itens = '<?php echo json_encode($itens); ?>';
$.ajax({
        type: "GET",
        dataType: "json",
      //  url: "http://agille.brasilia.me:1515/datasnap/rest/TDMFOOD/ReceberPedidoStr/",
      url: "http://agille.brasilia.me:1515/datasnap/rest/TDMFOOD/ReceberPedidoStr/"+'<?php echo json_encode($itens); ?>',
        data: itens,
        success: function(data){
            alert('Enviado');
        },
        error: function(e){
            console.log(e.message);
        }
});
});

</script>

</body>
</html>
