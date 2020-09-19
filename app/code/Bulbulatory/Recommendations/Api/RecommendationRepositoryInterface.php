<?php

namespace Bulbulatory\Recommendations\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Bulbulatory\Recommendations\Api\Data\RecommendationInterface;

interface RecommendationRepositoryInterface
{
    /**
     * @param int $id
     * @return RecommendationInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param RecommendationInterface $recommendation
     * @return RecommendationInterface
     */
    public function save(RecommendationInterface $recommendation);

    /**
     * @param RecommendationInterface $recommendation
     * @return void
     */
    public function delete(RecommendationInterface $recommendation);
}