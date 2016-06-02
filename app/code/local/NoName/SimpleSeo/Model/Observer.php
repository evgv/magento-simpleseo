<?php

/**
 *
 * @category  NoName
 * @package   NonName_SimpleSeo
 * @author    https://github.com/evgv
 * @version   1.0.0
 *
 */

class NoName_SimpleSeo_Model_Observer
{
    /* @var $_helper NoName_SimpleSeo_Helper_Data */
    protected $_helper;
    
    /* init array of tags for replace*/
    protected $_tags = array(
                        '{product.name}'                => '', 
                        '{product.description}'         => '',
                        '{product.manufacturer}'        => '',
                        '{category.name}'               => '',
                        '{category.sort_description}'   => '',
                        '{category.description}'        => '',
                        '{manufacturer.name}'           => '',
                        '{page.title}'                  => ''
                        );
    
    /* init array of replace param on page*/
    protected $_seo = array(
                            'title'         => '', 
                            'description'   => '',
                            'keys'          => '',
                            'h1'            => ''
                        );


    public function __construct()
    {
        $this->_helper = Mage::helper('simpleseo');
    }
    
    /**
     * Replace tags in string
     * 
     * @param string $string
     * @return boolean
     */
    protected function replaceTags($string)
    {
        if($string){
            foreach ($this->_tags as $key => $value) {
                if(!$value){continue;}
                $string = str_replace($key, $value , $string);
            }
            return $string;
        }
        return false;
    }
    
    /**
     * Retrieve and replace meta information for product page
     * Observe event: catalog_controller_product_view
     *
     * @param Varien_Event_Observer $observer
     * @return NoName_SimpleSeo_Model_Observer|boolean
     */
    public function catalogControllerProductView(Varien_Event_Observer $observer)
    {
        /**
         * Check if module enabled
         */
        if(!$this->_helper->isEnabled()){
            return false;
        }
        
        /**
         * Check if set product meta enabled
         */
        if(!$this->_helper->isEnabled('product')){
            return false;
        }
        
        $_product = $observer->getEvent()->getProduct();
        
        if($_product instanceof Mage_Catalog_Model_Product){
            $this->_tags['{product.name}']         = $_product->getName();
            $this->_tags['{product.price}']        = $_product->getFinalPrice();
            $this->_tags['{product.manufacturer}'] = Mage::getModel('catalog/product')->load($_product->getId())->getAttributeText('manufacturer');
            
            $this->_seo['title']       = $this->replaceTags($this->_helper->getConfigTitle('product'));
            $this->_seo['description'] = $this->replaceTags($this->_helper->getConfigDescription('product'));
            $this->_seo['keys']        = $this->replaceTags($this->_helper->getConfigKeys('product'));

            if (!$_product->getMetaTitle() && $this->_seo['title']){
                $_product->setMetaTitle($this->_seo['title']);
            }

            if (!$_product->getMetaDescription() && $this->_seo['description']){
                $_product->setMetaDescription($this->_seo['description']);
            }

            if (!$_product->getMetaKeywords() && $this->_seo['keys']){
                $_product->setMetaKeyword($this->_seo['keys']);
            }
        }
        
        
        return $this;
    }
    
    /**
     * Retrieve and replace meta information for category page
     * Observe event: catalog_controller_category_init_after
     * 
     * @param Varien_Event_Observer $observer
     * @return NoName_SimpleSeo_Model_Observer|boolean
     */
    public function catalogControllerCategoryInitAfter(Varien_Event_Observer $observer)
    {
        /**
         * Check if module enabled
         */
        if(!$this->_helper->isEnabled()){
            return false;
        }

        /**
         * Check if set category with|without manufacturer filter meta enabled
         */
        if(!$this->_helper->isEnabled('category') || !$this->_helper->isEnabled('manufacturer')){
            return false;
        }
        
        $_category = $observer->getEvent()->getCategory();
        $seo_key = '';
        
        if($_category instanceof Mage_Catalog_Model_Category){
            $labels            = array();
            $_attributes_array = array(
                                    'manufacturer'   => Mage::app()->getRequest()->getParam('manufacturer'),
                                );
            foreach ($_attributes_array as $key => $value) {
                if($value){
                    $product = Mage::getModel('catalog/product')->setStoreId(Mage::app()->getStore()->getStoreId())->setData($key,$value);
                    $labels[] = $product->getAttributeText($key);
                }
            }
            
            $this->_tags['{category.name}']                 = $_category->getName();
            $this->_tags['{category.short_description}']    = $_category->getShortDescription();
            $this->_tags['{category.description}']          = $_category->getDescription();
            $this->_tags['{manufacturer.name}']             = implode(',', $labels);
            
            if($this->_helper->isEnabled('category') && !$this->_tags['{manufacturer.name}']){
                $seo_key = 'category';
            }else if($this->_helper->isEnabled('manufacturer') && $this->_tags['{manufacturer.name}']){
                $seo_key = 'manufacturer';
            }

            $this->_seo['title']       = $this->replaceTags($this->_helper->getConfigTitle($seo_key));
            $this->_seo['description'] = $this->replaceTags($this->_helper->getConfigDescription($seo_key));
            $this->_seo['keys']        = $this->replaceTags($this->_helper->getConfigKeys($seo_key));


            if (!$_category->getMetaTitle() && $this->_seo['title']){
                $_category->setMetaTitle($this->_seo['title']);
            }

            if (!$_category->getMetaDescription() && $this->_seo['description']){
                $_category->setMetaDescription($this->_seo['description']);
            }

            if (!$_category->getMetaKeywords() && $this->_seo['keys']){
                $_category->setMetaKeywords($this->_seo['keys']);
            }
        }
        
        return $this;
    }
    
    /**
     * Retrieve and replace meta information for all static pages exept Home page
     * Observe event: cms_page_render
     * 
     * @param Varien_Event_Observer $observer
     * @return NoName_SimpleSeo_Model_Observer|boolean
     */
    public function cmsPageRender(Varien_Event_Observer $observer)
    {
        /**
         * Check if module enabled
         */
        if(!$this->_helper->isEnabled()){
            return false;
        }
        
        /**
         * Check if set product meta enabled
         */
        if(!$this->_helper->isEnabled('cms')){
            return false;
        }
        
        $_page = $observer->getEvent()->getPage();
        
        if($_page instanceof  Mage_Cms_Model_Page && $_page->getIdentifier() !== Mage::getStoreConfig('web/default/cms_home_page')){
            
            $this->_tags['{page.title}'] = $_page->getTitle();
            
            $this->_seo['title']       = $this->replaceTags($this->_helper->getConfigTitle('cms'));
            $this->_seo['description'] = $this->replaceTags($this->_helper->getConfigDescription('cms'));
            $this->_seo['keys']        = $this->replaceTags($this->_helper->getConfigKeys('cms'));
            
            if ($this->_seo['title']){
                $_page->setTitle($this->_seo['title']);
            }

            if ($this->_seo['description']){
                $_page->setMetaDescription($this->_seo['description']);
            }

            if ($this->_seo['keys']){
                $_page->setMetaKeywords($this->_seo['keys']);
            }
        }
        
        return $this;
    }
}