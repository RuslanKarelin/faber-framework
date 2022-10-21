<?php

namespace Faber\Core\Database\Builder;

use \Faber\Core\Database\Builder\AbstractBuilder;

class MysqlBuilder extends AbstractBuilder
{
    public function tableExists(string $table): bool
    {
        if ($table) {
            $query = "SELECT COUNT(TABLE_NAME) as count FROM information_schema.TABLES WHERE TABLE_NAME = '{$table}'";
            return boolval($this->dbService->query($query)[0]['count']);
        }
        return false;
    }

    public function dropTable(string $table): void
    {
        $this->dbService->exec("DROP TABLE {$table}");
    }
}