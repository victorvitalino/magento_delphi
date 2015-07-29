<?php
require_once('app/Mage.php'); //Path da class do magento Magento
Mage::app();
umask(0);


  /** returns the option id for any attribute code by passing the label
 $attribute_code e.g. 'size','color','article'
 $label e.g. 'M','Red','art_21312'     */
 function getOptionId($attribute_code,$label)
    {
           $attribute_model = Mage::getModel('eav/entity_attribute');
           $attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;
           $attribute_code = $attribute_model->getIdByCode('catalog_product', $attribute_code);
           $attribute = $attribute_model->load($attribute_code);

           $attribute_table = $attribute_options_model->setAttribute($attribute);
           $options = $attribute_options_model->getAllOptions(false);

           foreach($options as $option)
           {
            if ($option['label'] == $label)
            {
                $optionId=$option['value'];
                break;
            }
        }
        return $optionId;
    }
    // $sProduct is the object used for product creation
    $sProduct = Mage::getModel('catalog/product');

    $productData=array(
                  'name' => "MY FIRST PRODUCT",
                  'sku' => "sku_1234",
                  'description' => "A long description that suits yout product including its features",
                  'short_description' => "An one liner for your product",
                  'weight' => 1, // whatever your product weighs
                  'status' => 1, // 1 => enabled, 0 => disabled
                  'visibility' => '4', // 1 => Not Visible Individually, 2 => Catalog, 3 => Search, 4 => Catalog, Search
                  'attribute_set_id' => 4, // default
                  'type_id' => 'simple',
                  'price' => 1999,
                  'special_price' => 1599, // optional
                  'tax_class_id' => 0, // None
                  'page_layout' => 'one_column',
    );
    // traversing through each index of productData
    foreach($productData as $key => $value)
    {
        $sProduct->setData($key,$value);
    }


    $sProduct->setWebsiteIds(array(1));

    $sProduct->setStockData(array(
                            'manage_stock' => 1,
                            'is_in_stock' => 1,
                            'qty' => 10,
                            'use_config_manage_stock' => 0
  ));
  $categoryIds = array(2,3,5); // Use category ids according to your store
  $sProduct->setCategoryIds($categoryIds);
  // use the directory path to images you want to save for the product
  // $mode = array("image");
  // $img = 'full_directory_path_to_image_stored_img_0001.jpg';
  // $sProduct->addImageToMediaGallery($img, $mode, false, false);
  //
  // $mode = array("small_image","thumbnail"."image");
  // $img = 'full_directory_path_to_image_stored_img_0002.jpg';

  // and finally you can call the save method to create the product
  $sProduct->save();
  echo "Pheww, Product saved Hurray :D";

  exit();
