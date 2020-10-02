<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Bulbulatory\Recommendations\Helper\Acl;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;

class Recommendation extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Acl
     */
    private $aclHelper;

    /**
     * Recommendation constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Acl $aclHeler
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Acl $aclHeler
    )
    {

        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->aclHelper = $aclHeler;
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

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Recommendations'));
        return $resultPage;
    }
}