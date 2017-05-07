<?php

namespace Bogkov\Contact\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class Uninstall
 *
 * @package Bogkov\Contact\Setup
 */
class Uninstall implements UninstallInterface
{
    /**
     * @param SchemaSetupInterface   $setup   setup
     * @param ModuleContextInterface $context context
     *
     * @return void
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->dropTable('contact_message');
        $setup->getConnection()->dropTable('contact');

        $setup->endSetup();
    }
}