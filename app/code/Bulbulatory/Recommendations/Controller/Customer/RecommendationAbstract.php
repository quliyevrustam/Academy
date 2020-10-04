<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Bulbulatory\Recommendations\Helper\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

abstract class RecommendationAbstract extends Action
{
    /**
     * Config constructor.
     * @param Context $context
     * @param Config $configHelper
     */
    public function __construct(
        Context $context,
        Config $configHelper
    )
    {
        parent::__construct($context);

        if(!$configHelper->isModuleEnabled())
        {
            return $this->resultRedirectFactory->create()->setPath('/');
        }
    }

    /**
     * @return mixed
     */
    abstract public function execute();
}