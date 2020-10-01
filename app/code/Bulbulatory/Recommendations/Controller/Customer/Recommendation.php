<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

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
     * Recommendation constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {

        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Recommendations'));
        return $resultPage;
    }
}