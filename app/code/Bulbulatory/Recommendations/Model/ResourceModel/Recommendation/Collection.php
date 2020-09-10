<?php

namespace Bulbulatory\Recommendations\Model\ResourceModel\Recommendation;

use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'bulbulatory_recommendations_recommendation_collection';
    protected $_eventObject = 'recommendation_collection';

    protected function _construct()
    {
        $this->_init(\Bulbulatory\Recommendations\Model\Recommendation::class, Recommendation::class);
    }
}