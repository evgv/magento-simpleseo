<?php

/**
 *
 * @category  NoName
 * @package   NonName_SimpleSeo
 * @author    https://github.com/evgv
 * @version   1.0.0
 *
 */

class NoName_SimpleSeo_Helper_Product extends NoName_SimpleSeo_Helper_Data
{
    protected $_current_product_seo = array();
    protected $_current_product     = false;


    public function __construct()
    {
        parent::__construct();
        $this->_current_product_seo = Mage::registry(NoName_SimpleSeo_Model_Observer::CURRENT_PRODUCT_SEO);
        $this->_current_product     = Mage::registry('current_product');
    }
    
    /**
     * Retrieve current product SEO H1
     * if is empty return product name
     * 
     * @return string
     */
    public function getH1() 
    {
        if(
            $this->_current_product instanceof Mage_Catalog_Model_Product && 
            !empty($this->_current_product_seo)
        ) {
            return isset($this->_current_product_seo['h1']) ? $this->_current_product_seo['h1'] : $this->_current_product->getName();
        } else if ($this->_current_product instanceof Mage_Catalog_Model_Product) {
            return $this->_current_product->getName();
        }
    }
}