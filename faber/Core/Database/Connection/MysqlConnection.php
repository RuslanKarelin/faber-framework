<?php

namespace Faber\Core\Database\Connection;

use \PDO;
use Faber\Core\Exceptions\DBException;

class MysqlConnection extends AbstractConnection
{
    public function connection()
    {
        $dbConfig = config('database.connections.mysql');
        try {
            $this->PDO = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']}",
                $dbConfig['username'],
                $dbConfig['password']
            );
        } catch (\Exception $exception) {
            throw new DBException($exception, $exception->getCode());
        }
    }
}