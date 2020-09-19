<?php

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Table;

use Bulbulatory\Recommendations\Model\RecommendationFactory;
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Table
 */
class Delete extends Action
{
    /**
     * @var RecommendationFactory
     */
    private $recommendationFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param RecommendationFactory $recommendationFactory
     */
    public function __construct(
        Action\Context $context,
        RecommendationFactory $recommendationFactory
    )
    {
        $this->recommendationFactory = $recommendationFactory;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bulbulatory_Recommendations::bulbulatory_recommendations_table_delete');
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id = $this->getRequest()->getParam('id'))
        {
            try {
                $recommendation = $this->recommendationFactory->create();
                $recommendation->load($id);
                $recommendation->delete();
                $this->messageManager->addSuccessMessage(__('Recommendation with ID: ' . $id . ' deleted!'));
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage(__('Error in deleting row with ID: ' . $id));
            }
        } else {
            $this->messageManager->addErrorMessage(__('Invalid request'));
        }

        return $resultRedirect->setPath('bulbulatory_recommendations/table/index');
    }
}