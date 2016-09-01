<?php

/**
 *
 * @category  NoName
 * @package   NonName_SimpleSeo
 * @author    https://github.com/evgv
 * @version   1.0.0
 *
 */

class NoName_SimpleSeo_Helper_Category extends NoName_SimpleSeo_Helper_Data
{
    protected $_current_category_seo = array();
    protected $_current_category     = false;


    public function __construct()
    {
        parent::__construct();
        $this->_current_category_seo = Mage::registry(NoName_SimpleSeo_Model_Observer::CURRENT_CATEGORY_SEO);
        $this->_current_category    = Mage::registry('current_category');
    }
    
    /**
     * Retrieve current category SEO H1
     * if is empty return category name
     * 
     * @return string
     */
    public function getH1() 
    {
        if(
            $this->_current_category instanceof Mage_Catalog_Model_Category && 
            !empty($this->_current_category_seo)
        ) {
            return isset($this->_current_category_seo['h1']) ? $this->_current_category_seo['h1'] : $this->_current_category->getName();
        } else if ($this->_current_category instanceof Mage_Catalog_Model_Category) {
            return $this->_current_category->getName();
        }
    }
}