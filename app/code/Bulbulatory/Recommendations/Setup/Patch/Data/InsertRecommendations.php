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
            ['customer_id' => 1, 'email' => 'test_one@test_one.com', 'hash' => 'A9F70FD94A58F18F47CEFC296B2E919B3F12505E30069172F2D8ADB6337864C1'],
            ['customer_id' => 2, 'email' => 'test_two@test_two.com', 'hash' => '5A6A9D5E04C3FCCC721C017106016C1E3D5DAA21801B02325D6C7D5171367005']
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
        return [];
    }
}