<?php

namespace Bogkov\Contact\Setup;

use Bogkov\Contact\Model\ResourceModel\Contact;
use Bogkov\Contact\Model\ResourceModel\ContactMessage;
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
            ->newTable($setup->getTable(Contact::TABLE))
            ->addColumn(
                Contact::FIELD_ID,
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
            ->addForeignKey(
                $setup->getFkName(Contact::TABLE, 'store_id', 'store', 'store_id'),
                'store_id',
                $setup->getTable('store'),
                'store_id',
                Table::ACTION_SET_NULL
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
            ->newTable($setup->getTable(ContactMessage::TABLE))
            ->addColumn(
                ContactMessage::FIELD_ID,
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
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Contact create date'
            )
            ->addIndex(
                $setup->getIdxName(Contact::TABLE, ['contact_id']),
                ['contact_id']
            )
            ->addForeignKey(
                $setup->getFkName(ContactMessage::TABLE, 'contact_id', Contact::TABLE, Contact::FIELD_ID),
                'contact_id',
                $setup->getTable(Contact::TABLE),
                Contact::FIELD_ID,
                Table::ACTION_CASCADE
            )
            ->setComment('Contact message information');
        $setup->getConnection()->createTable($table);
    }
}
