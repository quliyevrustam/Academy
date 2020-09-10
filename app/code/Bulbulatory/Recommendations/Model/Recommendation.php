<?php

namespace Bulbulatory\Recommendations\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Recommendation extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'bulbulatory_recommendations_table';

    protected $_cacheTag = 'bulbulatory_recommendations_table';
    protected $_eventPrefix = 'bulbulatory_recommendations_table';

    protected function _construct()
    {
        $this->_init(\Bulbulatory\Recommendations\Model\ResourceModel\Recommendation::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId() ];
    }

    public function getDefaultValues()
    {
        return [];
    }
}