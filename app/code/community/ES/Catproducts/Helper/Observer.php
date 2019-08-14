<?php

class ES_CatProducts_Model_Observer
{
    public function customer_address_save_before($observer)
    {
        exit('observer :)');
        $address = $observer->getCustomerAddress();
//echo "<pre>"; print_r($address->getData()); exit;
// do something here
    }
}