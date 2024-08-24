<?php

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Migration;

use Doctrine\DBAL\Migrations\AbortMigrationException;
use Doctrine\DBAL\Schema\Schema;
use UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20240823080725 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @throws AbortMigrationException
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()
                ->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addUpSqlFromFile(__DIR__);
    }

    /**
     * @param Schema $schema
     * @throws AbortMigrationException
     */
    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()
                ->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addDownSqlFromFile(__DIR__);
    }
}
