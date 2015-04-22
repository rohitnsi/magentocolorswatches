<?php
class Netsolutions_Productxml_Adminhtml_ProductxmlbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Backend Page Title"));
	   $this->renderLayout();
    }
    
    public function generateAction()
    {
		$configValue = Mage::getStoreConfig('nsi_productxml/nsi_productxml/nsi_productxml_enable');
		if ($configValue == 1)
		{
			try {
			$collection = Mage::getModel('catalog/product')
			->getCollection()
			->addAttributeToSelect('*');
			$doc = new DOMDocument('1.0');
			$doc->formatOutput = true;
			
			$mainroot = $doc->createElement('configurable');
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
			
			$doc->save(time().'.xml');
			Mage::getSingleton('adminhtml/session')->addSuccess('Product Data Saved In File -<a target="blank" href='.Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL).time().'.xml'.'>'.' '.time().'.xml</a>');
			
		}

	 catch(Exception $e){
		 Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		
	}
	else
	{
		Mage::getSingleton('adminhtml/session')->addError('Please Enable The Extension.');
	}
	$this->_redirectReferer(); 
	}
	
}

