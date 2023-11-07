<?php

namespace Students\Models;
use Students\Services\Db;

abstract class ActiveRecordEntity {

    /** @var int */
    protected $id;

    /** @var string */
    protected $camelCaseName;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /** 
     * @param string $name
     * 
     * @param string $value
     */
    public function __set($name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    /** 
     * @param string $source
     * 
     * @return string
     */
    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    /**
     * @param string $order
     * 
     * @param string $direction
     * 
     * @return static[]
     */
    public static function findAll(string $order = 'points', string $direction = 'DESC'): array
    {
        $db = Db::getInstance();
        return $db->query("SELECT * FROM `" . static::getTableName() . "` ORDER BY {$order} {$direction};", [], static::class);
    }

    /**
     * @param string $id
     * 
     * @return static|null
     */
    public static function getById($id): ?self
    {
        $db = Db::getInstance();
        $entities = $db->query('SELECT * FROM `' . static::getTableName() .  '`' . 'WHERE id = :id', ['id' => $id], static::class);
        return $entities ? $entities[0] : null;
    }

    /**
     * @param string $columnName
     * 
     * @param string $value
     * 
     * @return static|null
     */
    public static function findOneByColumn(string $columnName, $value): ?self {
        
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value LIMIT 1', ['value' => $value], static::class);

        if($result === []) {
            return null;
        }

        return $result[0];
    }

    /** @return void */
    public function save(): void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();
        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    /** 
     * @param array $mappedProperties
     * 
     * @return void
     */
    private function update(array $mappedProperties): void
    {

        $columnsToParams = [];
        $paramsToColumns = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index;
            $columnsToParams[] = $column  . ' = ' . $param;
            $paramsToColumns[$param] = $value;
            $index++;
        }
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(',', $columnsToParams) . ' WHERE id = ' . $this->id;
        $db = Db::getInstance();
        $db->query($sql, $paramsToColumns, static::class);
    }

    /** 
     * @param array $mappedProperties
     * 
     * @return void
     */
    private function insert(array $mappedProperties): void
    {

        $filteredProperties = array_filter($mappedProperties);
        $columns = [];
        $paramsNames = [];
        $params2Values = [];

        foreach ($filteredProperties as $columnName => $value) {
            $columns[] = '`' . $columnName . '`';
            $paramName = ':' . $columnName;
            $paramsNames[] = $paramName;
            $params2Values[$paramName] = $value;
        }

        $columnsViaSemicolon = implode(',', $columns);
        $paramsNamesViaSemicolon = implode(',', $paramsNames);

        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . $columnsViaSemicolon . ') VALUES (' .  $paramsNamesViaSemicolon . ');';
        $db = Db::getInstance();
        $db->query($sql, $params2Values, static::class);
        $this->id = $db->getLastInsertedId();
        $this->refresh();
    }

    /** @return void */
    public function refresh(): void
    {

        $objFromDb = static::getById($this->id);
        $properties = get_object_vars($objFromDb);
        foreach ($properties as $key => $value) {
            $this->$key = $value;
        }
    }

    /** @return array */
    public function mapPropertiesToDbFormat(): array
    {

        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    abstract protected static function getTableName(): string;
}