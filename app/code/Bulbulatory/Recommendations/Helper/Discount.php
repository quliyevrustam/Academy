<?php

namespace Bulbulatory\Recommendations\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Bulbulatory\Recommendations\Model\Recommendation;
use Magento\Customer\Model\Session;

class Discount extends AbstractHelper
{
    /**
     * @var Recommendation
     */
    protected $customCollection;

    /**
     * @var
     */
    private $customerId;

    /**
     * Discount constructor.
     * @param Context $context
     * @param Recommendation $customCollection
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        Recommendation $customCollection,
        Session $customerSession
    ) {
        $this->customCollection = $customCollection;
        $this->customerId = $customerSession->getCustomer()->getId();

        parent::__construct($context);
    }

    /**
     * @return float|int
     */
    public function getRecommendationDiscountPercent()
    {
        $countConfirmedRecommendation =
            $this->customCollection->getCollection()->
            addFieldToFilter('customer_id', [$this->customerId])->
            addFieldToFilter('state', [Recommendation::STATE_CONFIRMED])->count();

        return intdiv( $countConfirmedRecommendation, 10 ) * 5;
    }
}