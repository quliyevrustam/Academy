<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Bulbulatory\Recommendations\Helper\Acl;
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
     * @var Acl
     */
    private $aclHelper;

    /**
     * ConfirmRecommendation constructor.
     * @param Context $context
     * @param RecommendationRepository $recommendationRepository
     * @param LoggerInterface $logger
     * @param Acl $aclHelper
     */
    public function __construct(
        Context $context,
        RecommendationRepository $recommendationRepository,
        LoggerInterface $logger,
        Acl $aclHelper
    )
    {
        parent::__construct($context);

        $this->recommendationsRepository = $recommendationRepository;
        $this->logger = $logger;
        $this->aclHelper = $aclHelper;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        if(!$this->aclHelper->recommendationModuleAccess())
        {
            $this->messageManager->addErrorMessage(__('You have not access for confirm recommendation!'));
            return $this->resultRedirectFactory->create()->setPath('/');
        }

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