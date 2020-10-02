<?php

namespace Bulbulatory\Recommendations\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;

class Acl extends AbstractHelper
{
    /**
     * @var Config
     */
    private $configHelper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Config constructor.
     * @param Context $context
     * @param Config $configHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Config $configHelper,
        LoggerInterface $logger
    ) {
        parent::__construct($context);

        $this->configHelper = $configHelper;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function recommendationModuleAccess(): bool
    {
        $recommendationModuleAccess = false;
        try {
            $recommendationModuleAccess = (bool) $this->configHelper->getConfigValue('recommendations/general/enable');
        }
        catch (\Throwable $exception)
        {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
        }

        return $recommendationModuleAccess;
    }
}