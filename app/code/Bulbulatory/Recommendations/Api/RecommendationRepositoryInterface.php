<?php

namespace Bulbulatory\Recommendations\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Bulbulatory\Recommendations\Model\Recommendation;

interface RecommendationRepositoryInterface
{
    /**
     * @param int $id
     * @return Recommendation
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param Recommendation $recommendation
     * @return Recommendation
     */
    public function save(Recommendation $recommendation);

    /**
     * @param Recommendation $recommendation
     * @return void
     */
    public function delete(Recommendation $recommendation);
}