<?

namespace Bulbulatory\Recommendations\Controller\Adminhtml\Table;

use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Bulbulatory\Recommendations\Model\RecommendationFactory;

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
     * @var RecommendationFactory
     */
    private $recommendationFactory;

    /**
     * DeleteMass constructor.
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     * @param RecommendationFactory $recommendationFactory
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        Context $context,
        RecommendationFactory $recommendationFactory
    )
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
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

        try {
            $recommendationCollection = $this->filter->getCollection($this->collectionFactory->create());

            foreach ($recommendationCollection as $item) {
                $id = $item->getId();
                $recommendation = $this->recommendationFactory->create();
                $recommendation->load($id);
                $recommendation->delete();
                $this->messageManager->addSuccessMessage(__('Recommendation with ID: ' . $id . ' deleted!'));
            }
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('bulbulatory_recommendations/table/index');
    }
}