<?php

namespace Bulbulatory\Recommendations\Controller\Customer;

use Bulbulatory\Recommendations\Exception\RecommendationException;
use Bulbulatory\Recommendations\Helper\Acl;
use Exception;
use http\Exception\InvalidArgumentException;
use Throwable;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Bulbulatory\Recommendations\Model\RecommendationRepository;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\Session;
use Bulbulatory\Recommendations\Helper\Email;

class SendRecommendation extends Action
{
    const EMAIL_TEMPLATE  = 'recommendations/general/recommendation_email';

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
     * @var Acl
     */
    private $aclHelper;

    /**
     * SendRecommendation constructor.
     * @param Context $context
     * @param RecommendationRepository $recommendationRepository
     * @param LoggerInterface $logger
     * @param Session $customerSession
     * @param Email $emailHelper
     * @param Acl $aclHelper
     */
    public function __construct(
        Context $context,
        RecommendationRepository $recommendationRepository,
        LoggerInterface $logger,
        Session $customerSession,
        Email $emailHelper,
        Acl $aclHelper
    )
    {
        parent::__construct($context);

        $this->recommendationsRepository = $recommendationRepository;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
        $this->emailHelper = $emailHelper;
        $this->aclHelper = $aclHelper;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        if(!$this->aclHelper->recommendationModuleAccess())
        {
            $this->messageManager->addErrorMessage(__('You have not access for send recommendation!'));
            return $this->resultRedirectFactory->create()->setPath('/');
        }

        try {
            $this->setEmail($this->getRequest()->getParam('email'));
            $this->saveRecommendation();
            $this->sendRecommendation();

            $this->messageManager->addSuccessMessage(__('Recommendation send to email: %1', $this->email));
        }
        catch (Throwable $exception)
        {
            if($exception instanceof \InvalidArgumentException)
            {
                $this->messageManager->addErrorMessage(__($exception->getMessage()));
            }
            else{
                $this->messageManager->addErrorMessage(__('There was an error while trying to send recommendation!'));
            }

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
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            throw new \InvalidArgumentException(__('Wrong email: "%1"', $email));
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

        $this->emailHelper->sendMail(self::EMAIL_TEMPLATE, $templateVars, $senderInfo, $receiverInfo);
    }
}