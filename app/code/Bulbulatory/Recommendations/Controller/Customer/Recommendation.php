<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Bulbulatory\Recommendations\Helper\Acl;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * Class Recommendation
 * @package Bulbulatory\Recommendations\Controller\Customer
 */
class Recommendation extends Action
{
    /**
     * @var Acl
     */
    private $aclHelper;

    /**
     * Config constructor.
     * @param Context $context
     * @param Acl $aclHelper
     */
    public function __construct(
        Context $context,
        Acl $aclHelper
    ) {
        parent::__construct($context);

        $this->aclHelper = $aclHelper;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        if(!$this->aclHelper->recommendationModuleAccess())
        {
            $this->messageManager->addErrorMessage(__('You have not access for Recommendations!'));
            return $this->resultRedirectFactory->create()->setPath('/');
        }

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}