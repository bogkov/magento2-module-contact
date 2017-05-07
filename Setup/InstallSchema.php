<?php

namespace Bogkov\Contact\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 *
 * @package Bogkov\Contact\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createTableContact($setup);
        $this->createTableContactMessage($setup);

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup setup
     *
     * @return void
     */
    protected function createTableContact(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('contact'))
            ->addColumn(
                'contact_id',
                Table::TYPE_BIGINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Contact ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Customer ID'
            )
            ->addColumn(
                'status_code',
                Table::TYPE_TEXT,
                30,
                ['nullable' => false],
                'Status code'
            )
            ->addColumn(
                'user_name',
                Table::TYPE_TEXT,
                128,
                ['nullable' => false],
                'User name'
            )
            ->addColumn(
                'user_email',
                Table::TYPE_TEXT,
                128,
                ['nullable' => false],
                'User email'
            )
            ->addColumn(
                'phone_number',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Phone number'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Contact create date'
            )
            ->addIndex(
                $setup->getIdxName('contact', ['store_id']),
                ['store_id']
            )
            ->addIndex(
                $setup->getIdxName('contact', ['customer_id']),
                ['customer_id']
            )
            ->setComment('Contact base information');
        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup setup
     *
     * @return void
     */
    protected function createTableContactMessage(SchemaSetupInterface $setup)
    {
        $table = $setup->getConnection()
            ->newTable($setup->getTable('contact_message'))
            ->addColumn(
                'contact_message_id',
                Table::TYPE_BIGINT,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Contact message ID'
            )
            ->addColumn(
                'contact_id',
                Table::TYPE_BIGINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Contact ID'
            )
            ->addColumn(
                'type_code',
                Table::TYPE_TEXT,
                30,
                ['nullable' => false],
                'Type code'
            )
            ->addColumn(
                'text',
                Table::TYPE_TEXT,
                '64k',
                ['nullable' => false],
                'Text'
            )
            ->addIndex(
                $setup->getIdxName('contact', ['contact_id']),
                ['contact_id']
            )
            ->addForeignKey(
                $setup->getFkName('contact_message', 'contact_id', 'contact', 'contact_id'),
                'contact_id',
                $setup->getTable('contact'),
                'contact_id',
                Table::ACTION_CASCADE
            )
            ->setComment('Contact message information');
        $setup->getConnection()->createTable($table);
    }
}
