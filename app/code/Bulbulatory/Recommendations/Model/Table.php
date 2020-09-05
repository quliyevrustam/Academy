<?php

namespace Bulbulatory\Recommendations\Model;

class Table extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'bulbulatory_recommendations_table';

    protected $_cacheTag = 'bulbulatory_recommendations_table';
    protected $_eventPrefix = 'bulbulatory_recommendations_table';

    protected function _construct()
    {
        $this->_init('Bulbulatory\Recommendations\Model\ResourceModel\Table');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId() ];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }
}