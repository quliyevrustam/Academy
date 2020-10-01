<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Exception;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Psr\Log\LoggerInterface;

class ConfirmRecommendation extends Action
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
     */
    public function __construct(
        Context $context,
        RecommendationRepository $recommendationRepository,
        LoggerInterface $logger
    )
    {
        $this->recommendationsRepository = $recommendationRepository;

        $this->logger = $logger;

        parent::__construct($context);
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
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
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
            throw new Exception(__('Empty hash!'));

        if(!preg_match('/^[a-zA-Z0-9]+$/', $this->getRequest()->getParam('hash')))
            throw new Exception(__('Wrong hash!'));

        $this->hash = $this->getRequest()->getParam('hash');
    }
}