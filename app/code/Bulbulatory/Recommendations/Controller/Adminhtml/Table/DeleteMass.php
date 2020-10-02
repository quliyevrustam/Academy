<?

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Table;

use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Psr\Log\LoggerInterface;

/**
 * Class DeleteMass
 * @package Bulbulatory\Recommendations\Controller\Adminhtml\Table
 */
class DeleteMass extends Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var RecommendationRepository
     */
    protected $recommendationRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DeleteMass constructor.
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     * @param RecommendationRepository $recommendationRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        Context $context,
        RecommendationRepository $recommendationRepository,
        LoggerInterface $logger
    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
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
        $recommendationCollection = $this->filter->getCollection($this->collectionFactory->create());

        $successfulDeleteIds = $failedDeleteIds = [];

        foreach ($recommendationCollection as $item)
        {
            try {
                $id = $item->getId();

                $recommendation = $this->recommendationRepository->getById($id);
                $this->recommendationRepository->delete($recommendation);

                $successfulDeleteIds[] = $id;
            } catch (\Throwable $e) {
                $failedDeleteIds[] = $item->getId();
                $this->logger->error($e->getMessage());
                continue;
            }
        }

        if(count($successfulDeleteIds) > 0)
        {
            $this->messageManager->addSuccessMessage(__(count($successfulDeleteIds).' recommendations with ID: ' . implode(',', $successfulDeleteIds) . ' deleted!'));
        }

        if(count($failedDeleteIds) > 0)
        {
            $this->messageManager->addErrorMessage(__(count($failedDeleteIds).' recommendations with ID: ' . implode(',', $failedDeleteIds) . ' failed to delete!'));
        }

        return $resultRedirect->setPath('bulbulatory_recommendations/table/index');
    }
}