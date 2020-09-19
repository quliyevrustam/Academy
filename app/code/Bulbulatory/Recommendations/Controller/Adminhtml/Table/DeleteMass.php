<?

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Table;

use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Bulbulatory\Recommendations\Model\RecommendationRepository;

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
     * DeleteMass constructor.
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     * @param RecommendationRepository $recommendationRepository
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        Context $context,
        RecommendationRepository $recommendationRepository
    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
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

        try {
            $recommendationCollection = $this->filter->getCollection($this->collectionFactory->create());

            foreach ($recommendationCollection as $item)
            {
                $id = $item->getId();
                $recommendation = $this->recommendationRepository->getById($id);
                $recommendation->delete();
                $this->messageManager->addSuccessMessage(__('Recommendation with ID: ' . $id . ' deleted!'));
            }
        }
        catch (\Throwable $e)
        {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('bulbulatory_recommendations/table/index');
    }
}