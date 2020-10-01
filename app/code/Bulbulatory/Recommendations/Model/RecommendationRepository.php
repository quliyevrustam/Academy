<?php

namespace Bulbulatory\Recommendations\Model;

use Exception;
use Throwable;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Bulbulatory\Recommendations\Api\RecommendationRepositoryInterface;
use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\CollectionFactory as RecommendationCollectionFactory;
use Magento\Framework\Math\Random;

class RecommendationRepository implements RecommendationRepositoryInterface
{
    const STATE_UNCONFIRMED = 0;
    const STATE_CONFIRMED   = 1;

    /**
     * @var RecommendationFactory
     */
    private $recommendationFactory;

    /**
     * @var RecommendationCollectionFactory
     */
    private $recommendationCollectionFactory;

    /**
     * @var Random
     */
    private $mathRandom;

    /**
     * RecommendationRepository constructor.
     * @param RecommendationFactory $recommendationFactory
     * @param RecommendationCollectionFactory $recommendationCollectionFactory
     * @param Random $mathRandom
     */
    public function __construct(
        RecommendationFactory $recommendationFactory,
        RecommendationCollectionFactory $recommendationCollectionFactory,
        Random $mathRandom
    ) {
        $this->recommendationFactory = $recommendationFactory;
        $this->recommendationCollectionFactory = $recommendationCollectionFactory;
        $this->mathRandom = $mathRandom;
    }

    /**
     * @param int $id
     * @return Recommendation
     */
    public function getById($id)
    {
        $recommendation = $this->recommendationFactory->create();
        $recommendation->getResource()->load($recommendation, $id);

        if (! $recommendation->getId()) {
            throw new NoSuchEntityException(__('Unable to find recommendation with ID "%1"', $id));
        }

        return $recommendation;
    }

    /**
     * @param string $email
     * @return mixed
     */
    private function getByEmail(string $email)
    {
        $recommendation = $this->recommendationFactory->create();
        $recommendation->load($email, 'email');

        if (! $recommendation->getId()) {
            throw new NoSuchEntityException(__('Unable to find recommendation with email "%1"', $email));
        }

        return $recommendation;
    }

    /**
     * @param string $hash
     * @return mixed
     */
    private function getByHash(string $hash)
    {
        $recommendation = $this->recommendationFactory->create();
        $recommendation->load($hash, 'hash');

        if (! $recommendation->getId()) {
            throw new NoSuchEntityException(__('Unable to find recommendation with hash "%1"', $hash));
        }

        return $recommendation;
    }

    /**
     * @param Recommendation $recommendation
     * @return Recommendation
     */
    public function save(Recommendation $recommendation)
    {
        $recommendation->getResource()->save($recommendation);
        return $recommendation;
    }

    /**
     * @param Recommendation $recommendation
     */
    public function delete(Recommendation $recommendation)
    {
        $recommendation->getResource()->delete($recommendation);
    }

    /**
     * @param array $data
     * @return int
     * @throws Exception
     * @throws Throwable
     */
    public function create(array $data)
    {
        // Validate data for create Recommendation
        if(empty($data['customer_id'])) throw new Exception(__('Customer Id is empty!'));
        if(empty($data['email'])) throw new Exception(__('Email is empty!'));

        // Check Recommendation by email.
        // If Email exist - return Error
        // If Email does not exist - continue
        try {
            $recommendation = $this->getByEmail($data['email']);

            if($recommendation instanceof Recommendation) throw new Exception(__('Recommendation already send!'));
        }
        catch (Throwable $exception)
        {
            if(!($exception instanceof NoSuchEntityException)) throw $exception;
        }

        $data['hash'] = $this->generateHash();

        $recommendation = $this->recommendationFactory->create();
        $recommendation->setData($data);
        $recommendation->getResource()->save($recommendation);

        if (! $recommendation->getId()) {
            throw new NoSuchEntityException(__('Recommendation not save to Data Base'));
        }

        return $recommendation;
    }

    /**
     * @return string
     */
    private function generateHash(): string
    {
        $prefixSymbolCount = $this->mathRandom->getRandomNumber(2, 8);
        return $this->mathRandom->getRandomString($prefixSymbolCount).$this->mathRandom->getRandomString(32);
    }

    /**
     * @param string $hash
     * @throws Exception
     */
    public function confirm(string $hash): void
    {
        $recommendation = $this->getByHash($hash);

        // Check State
        if($recommendation->getState() == self::STATE_CONFIRMED)
            throw new Exception(__('Recommendation already confirmed!'));

        $recommendation->setState(self::STATE_CONFIRMED);
        $recommendation->setConfirmationAt(date('Y-m-d H:i:s'));
        $recommendation->getResource()->save($recommendation);
    }

    /**
     * @return array|string[]
     */
    public static function getStateTypes(): array
    {
        return [
            0 => 'Unconfirmed',
            1 => 'Confirmed'
        ];
    }
}