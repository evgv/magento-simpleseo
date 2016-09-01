<?php

/**
 *
 * @category  NoName
 * @package   NonName_SimpleSeo
 * @author    https://github.com/evgv
 * @version   1.0.0
 *
 */

class NoName_SimpleSeo_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    protected $_store_id;
    
    protected $_enable               = 'simpleseo/%s/active';
    
    protected $_breadcrumb_path      = 'simpleseo/%s/breadcrumb';
    protected $_title_path           = 'simpleseo/%s/title';
    protected $_description_path     = 'simpleseo/%s/description';
    protected $_keys_path            = 'simpleseo/%s/keys';
    protected $_h1_path              = 'simpleseo/%s/h1';

    public function __construct()
    {
        $this->_store_id = Mage::app()->getStore()->getId();
    }
    
    /**
     * Retrieve store config enable by variable $section for module and tags
     * $section values:
     *                  general
     *                  product
     *                  category
     *                  manufacturer
     *                  cms
     *
     * @param string $section
     * @return boolean
     */
    public function isEnabled($section = 'general')
    {
        return $this->configReplace($this->_enable, $section);
    }
    
    /**
     * Retrieve store config path by variable $section if var is empty by default is "general"
     * $section values:
     *                  general
     *                  product
     *                  category
     *                  manufacturer
     *                  cms
     * 
     * @param string $config
     * @param string $section
     * @return string
     */
    protected function configReplace($config, $section = 'general') 
    {
        return str_replace('%s', $section, $config);
    }
    
    /**
     * Retrieve config title by variable $section
     * $section values:
     *                general
     *                product
     *                category
     *                manufacturer
     *                cms
     * 
     * @param string $section
     * @return string | boolean
     */
    public function getConfigBreadcrumb($section) 
    {
        if($section){
            if($this->isEnabled($section)){
                return Mage::getStoreConfig($this->configReplace($this->_breadcrumb_path, $section), $this->_store_id);
            }
        }
        
        return false;
    }
    
    /**
     * Retrieve config title by variable $section
     * $section values:
     *                general
     *                product
     *                category
     *                manufacturer
     *                cms
     * 
     * @param string $section
     * @return string | boolean
     */
    public function getConfigTitle($section) 
    {
        if($section){
            if($this->isEnabled($section)){
                return Mage::getStoreConfig($this->configReplace($this->_title_path, $section), $this->_store_id);
            }
        }
        
        return false;
    }
    
    /**
     * Retrieve config meta decription by variable $section
     * $section values:
     *                general
     *                product
     *                category
     *                manufacturer
     *                cms
     * 
     * @param string $section
     * @return string | boolean
     */
    public function getConfigDescription($section) 
    {
        if($section){
            if($this->isEnabled($section)){
                return Mage::getStoreConfig($this->configReplace($this->_description_path, $section), $this->_store_id);
            }
        }
        
        return false;
    }
    
    /** 
     * Retrieve config meta keys by variable $section
     * $section values:
     *                general
     *                product
     *                category
     *                manufacturer
     *                cms
     * 
     * @param string $section
     * @return string | boolean
     */
    public function getConfigKeys($section) 
    {
        if($section){
            if($this->isEnabled($section)){
                return Mage::getStoreConfig($this->configReplace($this->_keys_path, $section), $this->_store_id);
            }
        }
        
        return false;
    }
    
    /** 
     * Retrieve config h1 by variable $section
     * $section values:
     *                general
     *                product
     *                category
     *                manufacturer
     *                cms
     * 
     * @param string $section
     * @return string | boolean
     */
    public function getConfigH1($section) 
    {
        if($section){
            if($this->isEnabled($section)){
                return Mage::getStoreConfig($this->configReplace($this->_h1_path, $section), $this->_store_id);
            }
        }
        
        return false;
    }
}