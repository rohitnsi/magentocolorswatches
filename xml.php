<?php
ini_set('display_errors', 1);
require_once 'app/Mage.php';
Mage::app();

try {
		$collection = Mage::getModel('catalog/product')
		->getCollection()
		->addAttributeToSelect('*');
		$doc = new DOMDocument('1.0');
		$doc->formatOutput = true;

				$mainroot = $doc->createElement('main');
				$mainroot = $doc->appendChild($mainroot);
		foreach ($collection as $product)
		{
			if($product->getTypeId() == "configurable")
			{
				$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
				$simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();



				/* Main Configurable product data */

				$root = $doc->createElement('product');
				$root = $mainroot->appendChild($root);

				$title = $doc->createElement('name');
				$title = $root->appendChild($title);

				$text = $doc->createTextNode($product->getName());
				$text = $title->appendChild($text);

				$title = $doc->createElement('product_id');
				$title = $root->appendChild($title);

				$text = $doc->createTextNode($product->getId());
				$text = $title->appendChild($text);

				$title = $doc->createElement('url_path');
				$title = $root->appendChild($title);

				$text = $doc->createTextNode($product->getUrlKey());
				$text = $title->appendChild($text);
				
				$title = $doc->createElement('sku');
				$title = $root->appendChild($title);

				$text = $doc->createTextNode($product->getSku());
				$text = $title->appendChild($text);

				$childids = array();

				foreach($simple_collection as $simple_product)
				{
					$childids[] = $simple_product->getId();
				}
				$childfinalids = implode(",",$childids);
				$title = $doc->createElement('child_product_ids');
				$title = $root->appendChild($title);
				$text = $doc->createTextNode($childfinalids);
				$text = $title->appendChild($text);
				/* End Main Configurable product data */

				/*	Child Product data */
				foreach($simple_collection as $simple_product)
				{
					$childids[] = $simple_product->getId();
					$newroot = $doc->createElement('childproduct');
					$newroot = $root->appendChild($newroot);

					$title = $doc->createElement('id');
					$title = $newroot->appendChild($title);
					$text = $doc->createTextNode($simple_product->getId());
					$text = $title->appendChild($text);

					$title = $doc->createElement('name');
					$title = $newroot->appendChild($title);
					$text = $doc->createTextNode($simple_product->getName());
					$text = $title->appendChild($text);

					$title = $doc->createElement('price');
					$title = $newroot->appendChild($title);
					$text = $doc->createTextNode($simple_product->getPrice());
					$text = $title->appendChild($text);
					
					$title = $doc->createElement('sku');
					$title = $newroot->appendChild($title);
					$text = $doc->createTextNode($simple_product->getSku());
					$text = $title->appendChild($text);
					
					$title = $doc->createElement('stock');
					$title = $newroot->appendChild($title);
					$text = $doc->createTextNode(round($simple_product->getStockItem()->getQty()));
					$text = $title->appendChild($text);
				}
				/*	End Child Product data */
				$doc->saveXML() . "\n";
				$doc->saveXML($title);
			}
		}
		$doc->save('new.xml');
		echo 'Product Data Saved!';
	}

 catch(Exception $e){
	 echo 'Message: ' .$e->getMessage();
	}
?>
