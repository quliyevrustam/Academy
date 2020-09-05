<?php

namespace Bulbulatory\Recommendations\Model\ResourceModel\Table;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'bulbulatory_recommendations_table_collection';
    protected $_eventObject = 'table_collection';

    protected function _construct()
    {
        $this->_init('Bulbulatory\Recommendations\Model\Table', 'Bulbulatory\Recommendations\Model\ResourceModel\Table');
    }
}