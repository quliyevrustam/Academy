<?php

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Table;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory = false;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory)
    {
        parent::__construct($context);

        $this->resultPageFactory = $resultPageFactory;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bulbulatory_Recommendations::bulbulatory_recommendations_table');
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Customer Recommendations List')));

        return $resultPage;
    }
}