<?php

namespace Elogic\Checkout\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Category implements ArrayInterface
{
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * Category constructor.
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    )
    {
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * @param bool $isActive
     * @param false $level
     * @param false $sortBy
     * @param false $pageSize
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        if ($isActive) {
            $collection->addIsActiveFilter();
        }

        return $collection;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $arr = $this->_toArray();
        $ret = [];

        foreach ($arr as $key => $value)
        {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function _toArray()
    {
        $categories = $this->getCategoryCollection(true, false, false, false);

        $catagoryList = array();
        foreach ($categories as $category)
        {
            $catagoryList[$category->getEntityId()] = __($this->_getParentName($category->getPath()) . $category->getName());
        }

        return $catagoryList;
    }

    /**
     * @param string $path
     * @return string
     */
    private function _getParentName($path = '')
    {
        $parentName = '';
        $rootCats = array(1,2);

        $catTree = explode("/", $path);

        array_pop($catTree);

        if($catTree && (count($catTree) > count($rootCats)))
        {
            foreach ($catTree as $catId)
            {
                if(!in_array($catId, $rootCats))
                {
                    $category = $this->_categoryFactory->create()->load($catId);
                    $categoryName = $category->getName();
                    $parentName .= $categoryName . ' -> ';
                }
            }
        }

        return $parentName;
    }
}
