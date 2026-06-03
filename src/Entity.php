<?php


namespace src;


use src\services\DB;
use src\services\Request;


abstract class Entity{
    protected int $id;
    protected string $tableName;


    public function __construct(protected Request $request, protected DB $DB){
    }


    public function load(array $fields): void
    {
        foreach($fields as $key => $value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
    }

    public function insert(array $fields): bool
    {
        $columns = array_keys($fields);
        $values  = array_values($fields);

        $colsSql = '`' . implode('`,`', $columns) . '`';

        $escapedValues = array_map(function($value){
            return $this->DB->real_escape_string((string)$value);
        }, $values);

        $placeholders = "'" . implode("','", $escapedValues) . "'";

        $sql = "INSERT INTO `{$this->tableName}` ({$colsSql}) VALUES ({$placeholders})";

        return (bool)$this->DB->querySQL($sql);
    }

    public function findAll(): array{
        $sql = 'SELECT * FROM ' . $this->tableName;
        $result = $this->DB->querySQL($sql);
        if($result === false) return [];
        return $result ?? [];
    }

    public function getById(int $id): ?array{
        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = " . (int)$id;
        $result = $this->DB->querySQL($sql);
        if($result === false || empty($result)){
            return null;
        }
        return $result[0] ?? null;
    }


    public function findByColumn(string $columnName, $value, int $limit = 0): ?array{
        $escapedValue = $this->DB->real_escape_string((string)$value);
        $columnName = $this->sanitizeColumnName($columnName);
        $sql = "SELECT * FROM " . $this->tableName . " WHERE $columnName = '$escapedValue'";
        if($limit > 0){
            $sql .= " LIMIT " . (int)$limit;
        }
        $result = $this->DB->querySQL($sql);
        if(!$result){
            return [];
        }
        $entities = [];
        foreach($result as $row){
            $entity = new static($this->request, $this->DB);
            $entity->load($row);
            $entities[] = $entity;
        }
        return $entities;
    }


    public function findOneByColumn(string $columnName, $value): ?array{
        $columnName = $this->sanitizeColumnName($columnName);
        $escapedValue = $this->DB->real_escape_string((string)$value);
        $sql = "SELECT * FROM " . $this->tableName . " WHERE $columnName = '$escapedValue' LIMIT 1";
        $result = $this->DB->querySQL($sql);


        if($result && isset($result[0])){
            return $result[0];
        }
        return null;
    }


    public function update(array $fields){
        if(!isset($this->id) || $this->id <= 0){
            throw new \InvalidArgumentException('ID сущности не установлен');
        }
        
        $propValuesArray = [];
        foreach($fields as $key => $value){
            $escapedValue = $this->DB->real_escape_string((string)$value);
            $propValuesArray[] = "$key = '$escapedValue'";
        }
        $propValues = implode(', ', $propValuesArray);
        $sql = "UPDATE " . $this->tableName . ' SET ' . $propValues . " WHERE id = " . (int)$this->id;
        return $this->DB->querySQL($sql);
    }


    public function delete(): bool{
        if(!isset($this->id) || $this->id <= 0){
            throw new \InvalidArgumentException('ID сущности не установлен');
        }
        $sql = "DELETE FROM " . $this->tableName . " WHERE id = " . (int)$this->id;
        return (bool)$this->DB->querySQL($sql);
    }
    protected function sanitizeColumnName(string $columnName): string{
        if(!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $columnName)){
            throw new \InvalidArgumentException('Недопустимое имя колонки');
        }
        return $columnName;
    }
}