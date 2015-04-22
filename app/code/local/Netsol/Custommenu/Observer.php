<?php
public function addToTopmenu(Varien_Event_Observer $observer)
{
    $menu = $observer->getMenu();
    $tree = $menu->getTree();
 
    $node = new Varien_Data_Tree_Node(array(
            'name'   => 'Categories',
            'id'     => 'categories',
            'url'    => Mage::getUrl(), // point somewhere
    ), 'id', $tree, $menu);
 
    $menu->addChild($node);
 
    // Children menu items
    $collection = Mage::getResourceModel('catalog/category_collection')
            ->setStore(Mage::app()->getStore())
            ->addIsActiveFilter()
            ->addNameToResult()
            ->setPageSize(3);
 
    foreach ($collection as $category) {
        $tree = $node->getTree();
        $data = array(
            'name'   => $category->getName(),
            'id'     => 'category-node-'.$category->getId(),
            'url'    => $category->getUrl(),
        );
 
        $subNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
        $node->addChild($subNode);
    }
}
?>
