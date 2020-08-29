<?php
namespace Bulbulatory\Recommendations\Setup\Patch\Schema;

use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\TriggerFactory;
use Magento\Framework\DB\Ddl\Trigger;

class UpgradeHashRecommendations implements SchemaPatchInterface
{
    private $moduleDataSetup;
    private $triggerFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        TriggerFactory $triggerFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->triggerFactory = $triggerFactory;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $setup->startSetup();

        $trigger = $this->triggerFactory->create()
            ->setName('trg_bulbulatory_recommendations_after_insert')
            ->setTime(Trigger::TIME_BEFORE)
            ->setEvent('INSERT')
            ->setTable($setup->getTable('bulbulatory_recommendations'));

        $trigger->addStatement("SET NEW.hash = SHA2(SHA2(CONCAT(RAND(CURRENT_TIMESTAMP)*12, '@dkjlfhdf!asdasd236*(', RAND()), 256), 224);");

        $setup->getConnection()->dropTrigger($trigger->getName());
        $setup->getConnection()->createTrigger($trigger);

        $setup->endSetup();
    }
}
