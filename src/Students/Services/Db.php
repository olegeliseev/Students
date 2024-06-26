<?php

namespace Students\Services;
use Students\Exceptions\DbException;

class Db
{
    /** @var \PDO */
    private $pdo;

    /** @var Db */
    private static $instance;

    private function __construct()
    {

        $dbOptions = (require __DIR__ . '/../../settings.php')['db'];

        try {
            $this->pdo = new \PDO(
                'mysql:host=' . $dbOptions['host'] . ';dbname=' . $dbOptions['dbname'],
                $dbOptions['user'],
                $dbOptions['password']
            );
            $this->pdo->exec('SET NAMES UTF8');
        } catch (\PDOException $e) {
            throw new DbException('Ошибка при подключении к базе: ' . $e->getMessage());
        }
    }

    /** @return int */
    public function getLastInsertedId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

    /**
     *  Checks if a PDO instance already exists, before creating a new one 
     *  @return Db
     * */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $sql
     * @param array $params
     * @param string $className
     * @return array|null
     */
    public function query(string $sql, array $params = [], string $className = 'stdClass'): ?array
    {

        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if (false === $result) {
            return null;
        }

        return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }
}
