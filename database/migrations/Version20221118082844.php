<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118082844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('messages');

        $table->dropIndex('IDX_DB021E966BF700BD');

        $table->removeForeignKey('FK_DB021E966BF700BD');

        $table->dropColumn('status_id');

        $table->addColumn('is_send', Types::BOOLEAN);

        $schema->getTable('messages')->addIndex(['is_send']);

        $schema->getTable('messages')->addIndex(['updated_at']);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('messages');

        $table->dropIndex('IDX_DB021E9643625D9F');

        $table->dropIndex('IDX_DB021E96328014EC');

        $table->dropColumn('is_send');

        $table->addColumn('status_id', Types::INTEGER);

        $table->addForeignKeyConstraint('statuses', ['status_id'], ['id']);

        $schema->getTable('messages')->addIndex(['status_id']);
    }
}
