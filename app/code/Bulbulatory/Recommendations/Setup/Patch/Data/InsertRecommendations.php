<?php

namespace Bulbulatory\Recommendations\Setup\Patch\Data;

use Bulbulatory\Recommendations\Setup\Patch\Schema\UpgradeHashRecommendations;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InsertRecommendations implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {

        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $setup = $this->moduleDataSetup;

        $data = [
            ['reference' => 'Very good site', 'email' => 'test_one@test_one.com'],
            ['reference' => 'Very very good site!!!', 'email' => 'test_two@test_two.com']
        ];
        foreach ($data as $bind) {
            $setup->getConnection()
                ->insertForce($setup->getTable('bulbulatory_recommendations'), $bind);
        }

        $this->moduleDataSetup->endSetup();
    }

    public function getAliases()
    {
        return [];
    }

    public static function getDependencies()
    {
        return [UpgradeHashRecommendations::class];
    }
}