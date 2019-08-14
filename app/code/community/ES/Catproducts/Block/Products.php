<?php
class ES_Catproducts_Block_Products extends Mage_Catalog_Block_Product_Abstract
{
    protected $_categoryId = null;

    protected $_category = null;

    protected $_products = null;

    private $_cacheCategoryId;

    private $_cacheProductId;

    private $_cacheLifeTime = null;

    private function init()
    {
        if ($this->_categoryId == null) {
            $this->_categoryId = $this->getCategoryId();
            $this->_cacheLifeTime = Mage::getStoreConfig('catproducts/general/lifetime');
            $this->_cacheCategoryId = 'ES_CatProducts_Block_Products_Category_'.$this->_categoryId;
            $this->_cacheProductId = 'ES_CatProducts_Block_Products_Product_'.$this->_categoryId;
        }
    }

    protected function getCategory()
    {
        $this->init();
        if ($this->_category == null) {
            $cachedContent = Mage::app()->loadCache($this->_cacheCategoryId);
            $category = unserialize($cachedContent);
            if (!$category) {
                $category = new Mage_Catalog_Model_Category();
                $category->load($this->_categoryId);
                Mage::app()->saveCache(
                    serialize($category),
                    $this->_cacheCategoryId,
                    array(Mage_Catalog_Model_Category::CACHE_TAG),
                    $this->_cacheLifeTime
                );
            }
            $this->_category = $category;
        }
        return $this->_category;
    }

    protected function getProducts()
    {
        if ($this->_products == null) {
            if ($this->_category == null)
                $this->getCategory();
            $cachedContent = Mage::app()->loadCache($this->_cacheProductId);
            $products = unserialize($cachedContent);
            if (!$products) {
                $products = $this->_category
                    ->getProductCollection()
                    ->addAttributeToSelect('name')
                    ->addAttributeToSelect('short_description')
                    ->addAttributeToSelect('price')
                    ->addAttributeToSelect('special_price')
                    ->addAttributeToSelect('small_image')
                    ->addAttributeToFilter('status', 1)
                    ->addAttributeToFilter('visibility', 4)

                    ->setPageSize(Mage::getStoreConfig('catproducts/general/perblock'));
                if ($products) {
                    foreach ($products as $product)
                        $productList[] = $product;
                }
                Mage::app()->saveCache(
                    serialize($productList),
                    $this->_cacheProductId,
                    array(Mage_Catalog_Model_Product::CACHE_TAG),
                    $this->_cacheLifeTime
                );
            }
            $this->_products = $products;
        }
        return $this->_products;
    }

    public function getMode()
    {
        return Mage::getStoreConfig('catproducts/general/list');
    }
    protected function _afterSave()
    {
        Mage::app()->cleanCache(array(
            Mage_Catalog_Model_Category::CACHE_TAG,
            Mage_Catalog_Model_Product::CACHE_TAG
        ));
    }
}