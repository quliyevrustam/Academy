<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Bulbulatory\Recommendations\Helper\Config;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;

class Recommendation extends RecommendationAbstract
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Recommendation constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Config $configHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Config $configHelper
    )
    {
        parent::__construct($context, $configHelper);
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