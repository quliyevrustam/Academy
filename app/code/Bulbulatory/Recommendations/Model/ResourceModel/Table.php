<?php

namespace Bulbulatory\Recommendations\Model\ResourceModel;

class Table extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('bulbulatory_recommendations', 'id');
    }
}