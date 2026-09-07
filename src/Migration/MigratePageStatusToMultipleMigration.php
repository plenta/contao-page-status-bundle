<?php

declare(strict_types=1);

/**
 * Contao Page Status Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\ContaoPageStatusBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Types\TextType;

final class MigratePageStatusToMultipleMigration extends AbstractMigration
{
    private const TABLES = ['tl_article', 'tl_form', 'tl_page'];

    public function __construct(private readonly Connection $db)
    {
    }

    public function getName(): string
    {
        return 'Plenta ContaoPageStatusBundle: convert page_status to multi-select';
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->db->createSchemaManager();

        foreach (self::TABLES as $table) {
            if (!$schemaManager->tablesExist([$table])) {
                continue;
            }

            $columns = $schemaManager->listTableColumns($table);

            if (!isset($columns['page_status'])) {
                continue;
            }

            if (!$columns['page_status']->getType() instanceof TextType) {
                return true;
            }
        }

        return false;
    }

    public function run(): MigrationResult
    {
        $schemaManager = $this->db->createSchemaManager();

        foreach (self::TABLES as $table) {
            if (!$schemaManager->tablesExist([$table])) {
                continue;
            }

            $columns = $schemaManager->listTableColumns($table);

            if (!isset($columns['page_status']) || $columns['page_status']->getType() instanceof TextType) {
                continue;
            }

            $this->migrateTable($table);
        }

        return $this->createResult(true);
    }

    private function migrateTable(string $table): void
    {
        $schemaManager = $this->db->createSchemaManager();
        $columns = $schemaManager->listTableColumns($table);

        if (isset($columns['page_status_new'])) {
            $this->db->executeStatement("ALTER TABLE $table DROP page_status_new");
        }

        $rows = $this->db->fetchAllAssociative(
            "SELECT id, page_status FROM $table WHERE page_status > 0"
        );

        $this->db->executeStatement("ALTER TABLE $table ADD page_status_new text NULL");

        foreach ($rows as $row) {
            $this->db->executeStatement(
                "UPDATE $table SET page_status_new = :status WHERE id = :id",
                [
                    'status' => serialize([(string) $row['page_status']]),
                    'id'     => (int) $row['id'],
                ],
                [
                    'status' => ParameterType::STRING,
                    'id'     => ParameterType::INTEGER,
                ]
            );
        }

        $this->db->executeStatement("ALTER TABLE $table DROP page_status");
        $this->db->executeStatement("ALTER TABLE $table CHANGE page_status_new page_status text NULL");
    }
}
