<?php
namespace Bulbulatory\Recommendations\Model\Plugin\ResourceModel\Recommendation;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class CustomSearchResult extends SearchResult
{
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()->joinLeft
        (
            ['ce' => $this->getTable('customer_entity')],
            "ce.entity_id = main_table.customer_id",
            ['customer_email' => 'ce.email']
        );

        $this->addFilterToMap('customer_email','ce.email');
        $this->addFilterToMap('email','main_table.email');
        $this->addFilterToMap('hash','main_table.hash');
        $this->addFilterToMap('state','main_table.state');
        $this->addFilterToMap('created_at','main_table.created_at');
        $this->addFilterToMap('confirmation_at','main_table.confirmation_at');

        return $this;
    }
}