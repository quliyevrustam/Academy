<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Exception;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Psr\Log\LoggerInterface;
use Bulbulatory\Recommendations\Helper\Config;

class ConfirmRecommendation extends RecommendationAbstract
{
    /**
     * @var RecommendationRepository
     */
    private $recommendationsRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var
     */
    private $hash;

    /**
     * ConfirmRecommendation constructor.
     * @param Context $context
     * @param RecommendationRepository $recommendationRepository
     * @param LoggerInterface $logger
     * @param Config $configHelper
     */
    public function __construct(
        Context $context,
        RecommendationRepository $recommendationRepository,
        LoggerInterface $logger,
        Config $configHelper
    )
    {
        parent::__construct($context, $configHelper);

        $this->recommendationsRepository = $recommendationRepository;
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            $this->validateHash();
            $this->recommendationsRepository->confirm($this->hash);
            $this->messageManager->addSuccessMessage(__('Thank you for visiting to Bulbulatory.pl!'));
        }
        catch (\Throwable $exception)
        {
            if($exception instanceof \InvalidArgumentException)
            {
                $this->messageManager->addErrorMessage(__($exception->getMessage()));
            }
            else{
                $this->messageManager->addErrorMessage(__('There was an error while trying to confirm recommendation!'));
            }

            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('/');
    }

    /**
     * @throws Exception
     */
    private function validateHash()
    {
        if(empty($this->getRequest()->getParam('hash')))
        {
            throw new \InvalidArgumentException(__('Empty hash!'));
        }

        if(!preg_match('/^[a-zA-Z0-9]+$/', $this->getRequest()->getParam('hash')))
        {
            throw new \InvalidArgumentException(__('Wrong hash!'));
        }

        $this->hash = $this->getRequest()->getParam('hash');
    }
}