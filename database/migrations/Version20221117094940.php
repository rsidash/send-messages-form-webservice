<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221117094940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('messages');

        $table->addColumn('reason', Types::TEXT, [
            'notnull' => false,
        ]);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('messages');

        $table->dropColumn('reason');
    }
}
