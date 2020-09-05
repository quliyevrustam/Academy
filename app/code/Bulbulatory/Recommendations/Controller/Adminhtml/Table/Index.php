<?php
namespace Bulbulatory\Recommendations\Controller\Adminhtml\Table;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        //$resultPage->setActiveMenu('Bulbulatory_Recommendations::bulbulatory_recommendations_table');
        $resultPage->getConfig()->getTitle()->prepend((__('Table')));
        return $resultPage;
    }
}