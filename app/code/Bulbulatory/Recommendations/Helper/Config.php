<?php

namespace Bulbulatory\Recommendations\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Config extends AbstractHelper
{
    const RECOMMENDATION_EMAIL_TEMPLATE  = 'recommendations/general/recommendation_email';

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Config constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * Return store configuration value of your template field that which id you set for template
     *
     * @param string $path
     * @return mixed
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()->getStoreId()
        );
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        $recommendationModuleAccess = false;
        try {
            $recommendationModuleAccess = (bool) $this->getConfigValue('recommendations/general/enable');
        }
        catch (\Throwable $exception)
        {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }

        return $recommendationModuleAccess;
    }

    public function getRecommendationMailTemplateId()
    {
        return $this->getConfigValue(self::RECOMMENDATION_EMAIL_TEMPLATE);
    }
}