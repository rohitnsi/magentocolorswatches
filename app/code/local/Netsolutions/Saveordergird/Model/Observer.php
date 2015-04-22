<?php
class Netsolutions_Saveordergird_Model_Observer
{

		public function Observer(Varien_Event_Observer $observer)
		{
			$_order = $observers->getEvent()->getOrder();
        $session = Mage::getSingleton('checkout/session');
        $dispatch_date = '2015-04-07 00:00:00';
        if ($dispatch_date != "") {
            try {
                $_order->Setmycolumn($dispatch_date)->save();
            } catch (Exception $e) {
				
                
            }
        }

		}
		
}
