<?php

namespace Bulbulatory\Recommendations\Model\ResourceModel\Table;

use Bulbulatory\Recommendations\Model\Table;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'bulbulatory_recommendations_table_collection';
    protected $_eventObject = 'table_collection';

    protected function _construct()
    {
        $this->_init(Table::class, \Bulbulatory\Recommendations\Model\ResourceModel\Table::class);
    }
}