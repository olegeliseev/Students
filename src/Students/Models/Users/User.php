<?php

namespace Students\Models\Users;
use \Students\Models\ActiveRecordEntity;
use \Students\Exceptions\InvalidArgumentException;
use \Students\Services\Db;

class User extends ActiveRecordEntity {

    protected $firstName;
    protected $lastName;
    protected $groupNumber;
    protected $points;
    protected $gender;
    protected $birthYear;
    protected $email;
    protected $passwordHash;
    protected $authToken;
    protected $residence;
    protected $createdAt;

    public function getFirstName(): string  {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    public function getGroupNumber(): string {
        return $this->groupNumber;
    }

    public function setGroupNumber(string $groupNumber): void {
        $this->groupNumber = $groupNumber;
    }

    public function getPoints(): int {
        return $this->points;
    }

    public function setPoints(int $points): void {
        $this->points = $points;
    }

    public function getGender(): string {
        return $this->gender;
    }

    public function setGender(string $gender): void {
        $this->gender = $gender;
    }

    public function getBirthYear(): int {
        return $this->birthYear;
    }

    public function setBirthYear(int $birthYear): void {
        $this->birthYear = $birthYear;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    public function getAuthToken(): string {
        return $this->authToken;
    }

    public function getResidence(): string {
        return $this->residence;
    }

    public function setResidence(string $residence): void {
        $this->residence = $residence;
    }

    public static function getTableName(): string
    {
        return 'students';
    }

    public static function findByKeyWord(string $keyword, string $order = 'points', string $direction = 'DESC'): ?array
    {    
        $db = Db::getInstance();
        $result = $db->query("SELECT * FROM `" . static::getTableName() . 
        "` WHERE CONCAT(`first_name`, ' ',`last_name`, ' ', `group_number`, ' ', `points`) LIKE CONCAT('%',:keyword,'%') ORDER BY {$order} {$direction}", 
        ['keyword' => $keyword], 
        static::class);

        if($result === []) {
            return null;
        }

        return $result;
    }

    public static function register(array $userData): User 
    {
        if (static::findOneByColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким электронным адресом уже существует');
        }

        $user = new User();
        $user->firstName = $userData['firstName'];
        $user->lastName = $userData['lastName'];
        $user->groupNumber = $userData['groupNumber'];
        $user->points = $userData['points'];
        $user->gender = $userData['gender'];
        $user->birthYear = $userData['birthYear'];
        $user->email = $userData['email'];
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->residence = $userData['residence'];
        
        $user->save();
        return $user;
    }

    public function updateFromArray(array $userData): User 
    {
        $this->setFirstName($userData['firstName']);
        $this->setLastName($userData['lastName']);
        $this->setGender($userData['gender']);
        $this->setGroupNumber($userData['groupNumber']);
        $this->setBirthYear($userData['birthYear']);
        $this->setPoints($userData['points']);
        $this->setResidence($userData['residence']);

        $this->save();
        return $this;
    }

    public static function login(array $userData): User {

        if(empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан электронный адрес!');
        }

        if(empty($userData['password'])) {
            throw new InvalidArgumentException('Не передан пароль!');
        }

        $user = User::findOneByColumn('email', $userData['email']);

        if($user === null) {
            throw new InvalidArgumentException('Пользователя с таким электронным адресом не существует!');
        }

        if(!password_verify($userData['password'], $user->getPasswordHash())) {
            throw new InvalidArgumentException('Неверно указанный пароль!');
        }

        $user->refreshAuthToken();
        $user->save();

        return $user;
    }

    private function refreshAuthToken() {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
}
