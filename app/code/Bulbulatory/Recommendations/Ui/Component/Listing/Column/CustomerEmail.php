<?php
namespace Bulbulatory\Recommendations\Ui\Component\Listing\Column;

use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Psr\Log\LoggerInterface;

class CustomerEmail extends Column
{
    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CustomerEmail constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CustomerRepository $customerRepository
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     * @param LoggerInterface $logger
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CustomerRepository $customerRepository,
        Escaper $escaper,
        array $components = [],
        array $data = [],
        LoggerInterface $logger
    ) {
        $this->escaper = $escaper;
        $this->customerRepository = $customerRepository;
        $this->logger = $logger;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items']))
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
                    $this->logger->error('Customer with id '.$item["customer_id"].' does not exist');
                }
            }
        }

        return $dataSource;
    }
}