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
                Contact::FIELD_STORE_ID,
                Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'default' => '0'],
                'Store ID'
            )
            ->addColumn(
                Contact::FIELD_STATUS_CODE,
                Table::TYPE_TEXT,
                30,
                ['nullable' => false],
                'Status code'
            )
            ->addColumn(
                Contact::FIELD_USER_NAME,
                Table::TYPE_TEXT,
                80,
                ['nullable' => false],
                'User name'
            )
            ->addColumn(
                Contact::FIELD_USER_EMAIL,
                Table::TYPE_TEXT,
                80,
                ['nullable' => false],
                'User email'
            )
            ->addColumn(
                Contact::FIELD_PHONE_NUMBER,
                Table::TYPE_TEXT,
                20,
                ['nullable' => false],
                'Phone number'
            )
            ->addColumn(
                Contact::FIELD_CREATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Contact create date'
            )
            ->addIndex(
                $setup->getIdxName(Contact::TABLE, [Contact::FIELD_STORE_ID]),
                [Contact::FIELD_STORE_ID]
            )
            ->addForeignKey(
                $setup->getFkName(Contact::TABLE, Contact::FIELD_STORE_ID, 'store', 'store_id'),
                Contact::FIELD_STORE_ID,
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
                ContactMessage::FIELD_CONTACT_ID,
                Table::TYPE_BIGINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Contact ID'
            )
            ->addColumn(
                ContactMessage::FIELD_TYPE_CODE,
                Table::TYPE_TEXT,
                30,
                ['nullable' => false],
                'Type code'
            )
            ->addColumn(
                ContactMessage::FIELD_TEXT,
                Table::TYPE_TEXT,
                '64k',
                ['nullable' => false],
                'Text'
            )
            ->addColumn(
                ContactMessage::FIELD_CREATED_AT,
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Contact create date'
            )
            ->addIndex(
                $setup->getIdxName(ContactMessage::TABLE, [ContactMessage::FIELD_CONTACT_ID]),
                [ContactMessage::FIELD_CONTACT_ID]
            )
            ->addForeignKey(
                $setup->getFkName(ContactMessage::TABLE, ContactMessage::FIELD_CONTACT_ID, Contact::TABLE, Contact::FIELD_ID),
                ContactMessage::FIELD_CONTACT_ID,
                $setup->getTable(Contact::TABLE),
                Contact::FIELD_ID,
                Table::ACTION_CASCADE
            )
            ->setComment('Contact message information');
        $setup->getConnection()->createTable($table);
    }
}
