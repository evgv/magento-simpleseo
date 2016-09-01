<?php

/**
 *
 * @category  NoName
 * @package   NonName_SimpleSeo
 * @author    https://github.com/evgv
 * @version   1.0.0
 *
 */

class NoName_SimpleSeo_Helper_Cms extends NoName_SimpleSeo_Helper_Data
{
    protected $_current_cms_seo = array();


    public function __construct()
    {
        parent::__construct();
        $this->_current_cms_seo = Mage::registry(NoName_SimpleSeo_Model_Observer::CURRENT_CMS_SEO);
    }
    
    /**
     * Retrieve current cms SEO H1
     * if is empty return cms name
     * 
     * @return string
     */
    public function getBreadcrumb() 
    {
        if(!empty($this->_current_cms_seo)) {
            return isset($this->_current_cms_seo['breadcrumb']) ? $this->_current_cms_seo['breadcrumb'] : '';
        }
    }
}