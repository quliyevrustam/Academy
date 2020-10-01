<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Exception;
use Throwable;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\Session;
use Bulbulatory\Recommendations\Helper\Email;

class SendRecommendation extends Action
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
     * @var Session
     */
    private $customerSession;

    /**
     * @var string
     */
    private $email;

    /**
     * @var
     */
    private $hash;

    /**
     * @var Email
     */
    private $emailHelper;

    /**
     * SendRecommendation constructor.
     * @param Context $context
     * @param RecommendationRepository $recommendationRepository
     * @param LoggerInterface $logger
     * @param Session $customerSession
     * @param Email $emailHelper
     */
    public function __construct(
        Context $context,
        RecommendationRepository $recommendationRepository,
        LoggerInterface $logger,
        Session $customerSession,
        Email $emailHelper
    )
    {
        $this->recommendationsRepository = $recommendationRepository;

        $this->logger = $logger;

        $this->customerSession = $customerSession;

        $this->emailHelper = $emailHelper;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        try {
            $this->setEmail($this->getRequest()->getParam('email'));
            $this->saveRecommendation();
            $this->sendRecommendation();

            $this->messageManager->addSuccessMessage(__('Recommendation send to email: %1', $this->email));
        }
        catch (Throwable $exception)
        {
            $this->messageManager->addErrorMessage(__($exception->getMessage()));
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('bulbulatory_recommendations/customer/recommendation');
    }

    /**
     * @param string $email
     * @throws Exception
     */
    private function setEmail(string $email): void
    {
        // Validate Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new Exception(__('Wrong email: "%1"', $email));
        }
        else
        {
            $this->email = $email;
        }
    }

    /**
     * Save Recommendation to DataBase
     *
     * @throws Exception
     * @throws Throwable
     */
    private function saveRecommendation(): void
    {
        // Get Customer Id from Session
        $customerId = $this->customerSession->getCustomer()->getId();

        $data = [
            'customer_id'   => $customerId,
            'email'         => $this->email
        ];

        $recommendation = $this->recommendationsRepository->create($data);

        $this->hash = $recommendation->getHash();
    }

    /**
     * Send Recommendation Email
     */
    private function sendRecommendation(): void
    {
        $receiverInfo = [
            'email' => $this->email,
            'name'  => $this->email,
        ];

        $senderInfo = [
            'name'  => 'Bulbulatory Recommendation',
            'email' => 'recomdation@bulbulatory.com',
        ];

        $templateVars = [
            'hash' => $this->hash
        ];

        $this->emailHelper->sendMail($templateVars, $senderInfo, $receiverInfo);
    }
}