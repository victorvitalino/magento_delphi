<?php
require_once('app/Mage.php'); //Path da class do magento Magento
Mage::app();
//$product_delete = ['00000001']; // Exemplo para o delete
//$product_sku = ['00000001']; // Exemplo  para o edit
//$valores = ['10','20'];// Exemplo para o edit
$i = '0';
$acao = $_GET['acao']; // Ação

/* Exemplos para o create com parâmetros vindos da URL.*/
$nome1 = $_GET['nome'];
$sku1 = $_GET['id'];
$descricao1 = $_GET['descricao'];
$desccurta1 = $_GET['desccurta'];
$peso1 = $_GET['peso'];
$preco1 = $_GET['preco'];

/* Exemplos para o delete com parâmetros vindos da URL.*/
$product_delete = [$_GET['id']];

/* Exemplos para o edit com parâmetros vindos da URL.*/
$product_sku = [$_GET['id']];
$valores = [$_GET['preco']];// Exemplo para o edit

Mage::register("isSecureArea", 1); //Setar que essa bosta ta no modo seguro 2 dias pra descobrir isso :s
umask(0);
/* Exemplos para o create com parâmetros fixos.*/
/*
$nome1 = 'teste';
$sku1 = '00000001';
$descricao1 = 'Exemplo de Descrição';
$desccurta1 = 'Exemplo Descrição Curta';
$peso1 = '500';
$preco1 = '5000';
 */

/*
Função de Create -- passa como params os valores do produto.
*/
function create($nome, $sku, $descricao, $desccurta, $peso, $preco) {
    try{
     $sProduct = Mage::getModel('catalog/product');
     $productData=array(
                   'name' => $nome,
                   'sku' => $sku,
                   'description' => $descricao,
                   'short_description' => $desccurta,
                   'weight' => $peso,
                   'status' => 1, // 1 => enabled, 0 => disabled
                   'visibility' => '4', // 1 => Not Visible Individually, 2 => Catalog, 3 => Search, 4 => Catalog, Search
                   'attribute_set_id' => 4, // default
                   'type_id' => 'simple',
                   'price' => $preco,
                   'tax_class_id' => 0, // Sem taxa
                   'page_layout' => 'one_column',
     );
     foreach($productData as $key => $value)
     {
         $sProduct->setData($key,$value);
     }
     $sProduct->setWebsiteIds(array(1));
     $sProduct->setStockData(array(
                             'manage_stock' => 1,
                             'is_in_stock' => 1,
                             'qty' => 100000,
                             'use_config_manage_stock' => 0
   ));
   $categoryIds = array(2,3,5);
   $sProduct->setCategoryIds($categoryIds);
   $sProduct->save();
   echo 'Produto criado';
 } catch (Exception $e){
   Mage::logException($e);
   echo 'erro';
   var_dump($e);
 }
}
/*
Função de Update -- passa como params o sku do produto e os valores.
*/
function updates($price, $product_id)
    {
        try {
            $product = Mage::getModel('catalog/product');
            $productId = $product_id;
            $product->load($productId);
            if ($product && $product->getId()) {
                $product->setPrice(strval($price));
                $product->save();
                echo 'produto '.$product_id .' editado <br/>';
            }
        } catch (Exception $e) {
            Mage::logException($e);
            echo 'produto '.$product_id .' apresentou erro';
        }
    }


/*
Função de Delete -- passa como params o sku do produto.
*/
function deletes($product_id) {
    	try {
    		$product = Mage::getModel('catalog/product');
    		$productId = $product_id;
    		$product->load($productId);
    		if ($product && $product->getId()) {
    			$product->delete();
    			echo 'produto '.$product_id .' deletado <br/>';
    		}
    	} catch (Exception $e) {
    		Mage::logException($e);
    		echo 'produto '.$product_id .' apresentou erro';
    	}
    }


/*
 *
 * AÇÕES
 *
 */

/*
Ação de Edição
*/
if($acao =='edit'){
  foreach ($product_sku as $sku){
      $product_id = Mage::getModel("catalog/product")->getIdBySku( $sku );
      updates($valores[$i], $product_id);
      $i++;
    }
}
/*
 Exemplo de edit
 https://consultoria7.com/alimentacao/functions.php?acao=edit&id=222222&preco=31.00
 */

/*
Ação de Delete
*/
if($acao == 'delete'){
  foreach ($product_delete as $sku){
      $product_id = Mage::getModel("catalog/product")->getIdBySku( $sku );
      deletes($product_id);
    }
}
/*
 Exemplo de delete simples
 https://consultoria7.com/alimentacao/functions.php?acao=delete&id=222222

 Exemplo de delete composto
 https://consultoria7.com/alimentacao/functions.php?acao=delete&id=222222&id=333333
 */

/*
Ação de Create
*/
if($acao == 'create'){
  create($nome1, $sku1, $descricao1, $desccurta1, $peso1, $preco1);
}
/*
Exemplo de create
https://consultoria7.com/alimentacao/functions.php?acao=create&nome=Filet Mignon&id=222222&descricao=Corte Alto&desccurta=Estilo Tornedor&peso=2&preco=3100
https://consultoria7.com/alimentacao/functions.php?acao=create&nome=Quibe Frito&id=333333&descricao=Frito em Óleo Vegetal&desccurta=Sem conservantes&peso=1&preco=150
https://consultoria7.com/alimentacao/functions.php?acao=create&nome=Risoto&id=444444&descricao=Ristoto de Calabresa&desccurta=Com alho poró&peso=2000&preco=45.00
 */
?>
