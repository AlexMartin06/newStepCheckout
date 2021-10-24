<?php

namespace Elogic\Checkout\Model;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use \Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\Config\Scope;

class AdditionalConfigVars implements ConfigProviderInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    protected $listProduct;

    /**
     * AdditionalConfigVars constructor.
     * @param CollectionFactory $productCollectionFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param ListProduct $listProduct
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        ScopeConfigInterface $scopeConfig,
        ListProduct $listProduct
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->listProduct = $listProduct;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $postProductArray = [];
        $productUrls = [];
        $numberOfProducts = $this->scopeConfig->getValue('customstep/general/number_of_products', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $stepNameConfig = $this->scopeConfig->getValue('customstep/general/step_label', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $selectedCategories = $this->scopeConfig->getValue('customstep/general/categories', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $productCollection = $this->getProductCollectionByCategories($selectedCategories, $numberOfProducts);

         foreach ($productCollection as $product)
         {
             array_push($postProductArray, $this->listProduct->getAddToCartPostParams($product));
             array_push($productUrls, $product->getProductUrl());
         }

        $additionalVariables['newStep']['products'] = $productCollection->toArray();
        $additionalVariables['newStep']['customStepName'] = $stepNameConfig;
        $additionalVariables['newStep']['productsPostData'] = $postProductArray;
        $additionalVariables['newStep']['productUrls'] = $productUrls;

        return $additionalVariables;
    }

    /**
     * @param $ids
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollectionByCategories($ids, $numberOfProducts)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $ids]);
        $collection->setPageSize($numberOfProducts);
        return $collection;
    }
}
