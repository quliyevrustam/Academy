<?php

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Table;

use Magento\Backend\App\Action;
use Bulbulatory\Recommendations\Model\RecommendationRepository;

/**
 * Class Delete
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Table
 */
class Delete extends Action
{
    /**
     * @var RecommendationRepository
     */
    protected $recommendationRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param RecommendationRepository $recommendationRepository
     */
    public function __construct(
        Action\Context $context,
        RecommendationRepository $recommendationRepository
    )
    {
        $this->recommendationRepository = $recommendationRepository;

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
                $recommendation = $this->recommendationRepository->getById($id);
                $recommendation->delete();
                $this->messageManager->addSuccessMessage(__('Recommendation with ID: ' . $id . ' deleted!'));
            }
            catch (\Throwable $exception) {
                $this->messageManager->addErrorMessage(__('Error in deleting row with ID: ' . $id));
            }
        } else {
            $this->messageManager->addErrorMessage(__('Invalid request'));
        }

        return $resultRedirect->setPath('bulbulatory_recommendations/table/index');
    }
}