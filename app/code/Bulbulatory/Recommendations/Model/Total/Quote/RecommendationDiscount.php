<?php

namespace Bulbulatory\Recommendations\Model\Total\Quote;

use Bulbulatory\Recommendations\Helper\Discount;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Bulbulatory\Recommendations\Helper\Config;

/**
 * Class RecommendationDiscount
 * @package Bulbulatory\Recommendations\Model\Total\Quote
 */
class RecommendationDiscount extends AbstractTotal
{
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var bool
     */
    private $isModuleEnabled = false;

    /**
     * @var int
     */
    private $discount = 0;

    /**
     * RecommendationDiscount constructor.
     * @param PriceCurrencyInterface $priceCurrency
     * @param Config $configHelper
     * @param Discount $discountHelper
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Config $configHelper,
        Discount $discountHelper
    ){
        $this->priceCurrency = $priceCurrency;

        if($configHelper->isModuleEnabled())
        {
            $this->isModuleEnabled = true;

            $discountPercent = $discountHelper->getRecommendationDiscountPercent();
            $this->discount = $discountPercent/100;
        }
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this|bool
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);

        if($this->isModuleEnabled)
        {
            $baseDiscount = $this->discount * $total->getSubtotal();
            $discount =  $this->priceCurrency->convert($baseDiscount);
            $total->addTotalAmount($this->getCode(), -$discount);
            $total->addBaseTotalAmount($this->getCode(), -$baseDiscount);
            $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseDiscount);
            $quote->setCustomDiscount(-$discount);
        }

        return $this;
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array|null
     */
    public function fetch(
        Quote $quote,
        Total $total
    )
    {
        $discount = $this->discount * $total->getSubtotal();
        return [
            'code' => $this->getCode(),
            'title' => $this->getLabel(),
            'value' => -$discount
        ];
    }
}