<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114124222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('messages');

        $table->addColumn('id', Types::INTEGER)->setAutoincrement(true);

        $table->addColumn('text', Types::TEXT);

        $table->addColumn('created_at', Types::DATETIME_MUTABLE, [
            'default' => 'CURRENT_TIMESTAMP',
        ]);

        $table->addColumn('updated_at', Types::DATETIME_MUTABLE, [
            'default' => 'CURRENT_TIMESTAMP',
        ]);

        $table->addColumn('deleted_at', Types::DATETIME_MUTABLE, [
            'notnull' => false
        ]);

        $table->addColumn('status_id', Types::INTEGER);

        $table->addForeignKeyConstraint('statuses', ['status_id'], ['id']);

        $table->setPrimaryKey(array('id'));

        $schema->getTable('messages')->addIndex(['created_at']);
        $schema->getTable('messages')->addIndex(['status_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('messages');
    }
}
