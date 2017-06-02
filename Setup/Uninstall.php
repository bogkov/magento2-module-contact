<?php

namespace Bogkov\Contact\Setup;

use Bogkov\Contact\Model\ResourceModel\Contact;
use Bogkov\Contact\Model\ResourceModel\ContactMessage;
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

        $setup->getConnection()->dropTable(ContactMessage::TABLE);
        $setup->getConnection()->dropTable(Contact::TABLE);

        $setup->endSetup();
    }
}