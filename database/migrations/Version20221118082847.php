<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118082847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $schema->dropTable('statuses');
    }

    public function down(Schema $schema): void
    {
        $table = $schema->createTable('statuses');

        $table->addColumn('id', Types::INTEGER)->setAutoincrement(true);

        $table->addColumn('title', Types::STRING);

        $table->addColumn('created_at', Types::DATETIME_MUTABLE, [
            'default' => 'CURRENT_TIMESTAMP',
        ]);

        $table->addColumn('updated_at', Types::DATETIME_MUTABLE, [
            'default' => 'CURRENT_TIMESTAMP',
        ]);

        $table->addColumn('deleted_at', Types::DATETIME_MUTABLE, [
            'notnull' => false
        ]);

        $table->setPrimaryKey(array('id'));

        $schema->getTable('statuses')->addIndex(['title']);
    }
}
