<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\Migrations;

/**
 *  Для получения уникального ключа:
 * echo strtoupper(implode('', array_map(static function ($column) {
 *      return dechex(crc32($column));
 * }, ['a_supply_witem_elko', 'in_stocks_list_id'])));
 */
abstract class AbstractMigration extends \Doctrine\DBAL\Migrations\AbstractMigration
{
    protected function addUpSqlFromFile(string $migrationsBaseDir): void
    {
        $this->addSqlFromFile('up', $migrationsBaseDir);
    }

    protected function addDownSqlFromFile(string $migrationsBaseDir): void
    {
        $this->addSqlFromFile('down', $migrationsBaseDir);
    }

    private function addSqlFromFile(string $suffix, string $migrationsBaseDir): void
    {
        $version = $this->version->getVersion();

        $queries = explode(';', file_get_contents($migrationsBaseDir . DIRECTORY_SEPARATOR . 'SQL' . DIRECTORY_SEPARATOR . "{$version}_$suffix.sql"));

        $queries = array_filter($queries, function (string $query) {
            return '' !== trim($query);
        });

        foreach ($queries as $sql) {
            $this->addSql($sql);
        }
    }
}
