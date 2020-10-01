<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Magento\Framework\App\Action\Action;

/**
 * Class Recommendation
 * @package Bulbulatory\Recommendations\Controller\Customer
 */
class Recommendation extends Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}