<?php

namespace Bulbulatory\Recommendations\Block;

use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Bulbulatory\Recommendations\Model\Recommendation;
use Magento\Customer\Model\Session;
use Bulbulatory\Recommendations\Helper\Discount;

class RecommendationList extends Template
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_LIMIT = 5;

    /**
     * @var Recommendation
     */
    protected $customCollection;

    /**
     * @var
     */
    public $countAllRecommendation;

    /**
     * @var
     */
    public $countConfirmedRecommendation;

    /**
     * @var
     */
    public $discountPercent;

    /**
     * @var
     */
    private $customerId;

    /**
     * @var Discount
     */
    private $discountHelper;

    /**
     * RecommendationList constructor.
     * @param Context $context
     * @param Recommendation $customCollection
     * @param Session $customerSession
     * @param Discount $discountHelper
     */
    public function __construct(
        Context $context,
        Recommendation $customCollection,
        Session $customerSession,
        Discount $discountHelper
    )
    {
        $this->customCollection = $customCollection;
        $this->customerId = $customerSession->getCustomer()->getId();
        $this->discountHelper = $discountHelper;

        $this->getCounters();

        parent::__construct($context);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if($this->getCustomCollection())
        {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'customer.recommendation.pager'
            )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(true)->setCollection(
                    $this->getCustomCollection()
                );
            $this->setChild('pager', $pager);
            $this->getCustomCollection()->load();
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return mixed
     */
    public function getCustomCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : self::DEFAULT_PAGE;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : self::DEFAULT_LIMIT;
        $collection = $this->customCollection->getCollection();
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        return $collection->addFieldToFilter('customer_id', [$this->customerId]);
    }

    private function getCounters(): void
    {
        $this->countAllRecommendation =
            $this->customCollection->getCollection()->
            addFieldToFilter('customer_id', [$this->customerId])->count();

        $this->countConfirmedRecommendation =
            $this->customCollection->getCollection()->
            addFieldToFilter('customer_id', [$this->customerId])->
            addFieldToFilter('state', [Recommendation::STATE_CONFIRMED])->count();

        $this->discountPercent = $this->discountHelper->getRecommendationDiscountPercent();
    }

    /**
     * @param int $state
     * @return string
     */
    public function getStateName(int $state): string
    {
        return RecommendationRepository::getStateTypes()[$state];
    }
}