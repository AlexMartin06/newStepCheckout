<?php

namespace Elogic\Checkout\Controller\Index;

use Elogic\Checkout\Helper\Data;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Config extends Action
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * Config constructor.
     * @param Context $context
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        Data $helperData
    )
    {
        $this->helperData = $helperData;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        echo $this->helperData->getGeneralConfig('enable');
        echo $this->helperData->getGeneralConfig('step_label');
        echo $this->helperData->getGeneralConfig('categories');
        echo $this->helperData->getGeneralConfig('number_of_products');
        exit();
    }
}
