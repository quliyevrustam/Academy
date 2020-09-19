<?php

namespace Bulbulatory\Recommendations\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Bulbulatory\Recommendations\Api\Data\RecommendationInterface;
use Bulbulatory\Recommendations\Api\RecommendationRepositoryInterface;
use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\CollectionFactory as RecommendationCollectionFactory;

class RecommendationRepository implements RecommendationRepositoryInterface
{
    /**
     * @var RecommendationFactory
     */
    private $recommendationFactory;

    /**
     * @var RecommendationCollectionFactory
     */
    private $recommendationCollectionFactory;

    public function __construct(
        RecommendationFactory $recommendationFactory,
        RecommendationCollectionFactory $recommendationCollectionFactory
    ) {
        $this->recommendationFactory = $recommendationFactory;
        $this->recommendationCollectionFactory = $recommendationCollectionFactory;
    }

    public function getById($id)
    {
        $recommendation = $this->recommendationFactory->create();
        $recommendation->getResource()->load($recommendation, $id);
        if (! $recommendation->getId()) {
            throw new NoSuchEntityException(__('Unable to find recommendation with ID "%1"', $id));
        }
        return $recommendation;
    }

    public function save(RecommendationInterface $recommendation)
    {
        $recommendation->getResource()->save($recommendation);
        return $recommendation;
    }

    public function delete(RecommendationInterface $recommendation)
    {
        $recommendation->getResource()->delete($recommendation);
    }
}