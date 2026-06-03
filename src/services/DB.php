<?php

namespace src\services;

use exceptions\DBException;

class DB extends \mysqli{
    public function __construct($config){
        try{
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            parent::__construct(
            $config['hostname'],
            $config['username'],
            $config['password'],
            $config['database']);
        }catch(\mysqli_sql_exception $e){
            throw new DBException('Ошибка при подключении к БД' . $e->getMessage());
        }
    }
    public function querySQL(string $sql, array $params = []): array|bool{
        $result = parent::query($sql);
        if(gettype($result) == 'boolean') return $result;

        return $result->fetch_all(\MYSQLI_ASSOC);
    }
}