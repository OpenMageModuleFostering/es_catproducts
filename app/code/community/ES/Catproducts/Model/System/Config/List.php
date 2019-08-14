<?php

class ES_Catproducts_Model_System_Config_List
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'list', 'label'=>Mage::helper('adminhtml')->__('List')),
            array('value' => 'grid', 'label'=>Mage::helper('adminhtml')->__('Grid')),
        );
    }
}