<?php

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Table;

use Magento\Backend\App\Action;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param RecommendationRepository $recommendationRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        RecommendationRepository $recommendationRepository,
        LoggerInterface $logger
    )
    {
        $this->recommendationRepository = $recommendationRepository;

        $this->logger = $logger;

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

        if($id = $this->getRequest()->getParam('id'))
        {
            try {
                $recommendation = $this->recommendationRepository->getById($id);
                $this->recommendationRepository->delete($recommendation);
                $this->messageManager->addSuccessMessage(__('Recommendation with ID: ' . $id . ' deleted!'));
            }
            catch (\Throwable $exception) {
                $this->messageManager->addErrorMessage(__($exception->getMessage()));
                $this->messageManager->addErrorMessage(__('Error in deleting row with ID: ' . $id));
                $this->logger->error('Error in deleting row', ['exception' => $exception]);
            }
        } else {
            $this->messageManager->addErrorMessage(__('ID is missing!'));
            $this->logger->error('ID is missing!');
        }

        return $resultRedirect->setPath('bulbulatory_recommendations/table/index');
    }
}