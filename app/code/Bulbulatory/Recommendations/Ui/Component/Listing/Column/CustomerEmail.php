<?php
namespace Bulbulatory\Recommendations\Ui\Component\Listing\Column;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;

class CustomerEmail extends Column
{
    protected $escaper;

    protected $customerRepository;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerRepository $customerRepository,
        Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
        $this->escaper = $escaper;
        $this->customerRepository = $customerRepository;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items']))
        {
            foreach ($dataSource['data']['items'] as & $item)
            {
                try {
                    $customer = $this->customerRepository->getById($item["customer_id"]);
                    $item[$this->getData('name')] = $customer->getEmail();
                }
                catch(NoSuchEntityException $e)
                {
                    $item[$this->getData('name')] = 'Customer does not exist';
                }
            }
        }

        return $dataSource;
    }
}