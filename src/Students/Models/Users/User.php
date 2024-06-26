<?php

namespace Students\Models\Users;
use \Students\Models\ActiveRecordEntity;
use \Students\Exceptions\InvalidArgumentException;
use \Students\Services\Db;

class User extends ActiveRecordEntity {

    /** @param string */
    protected $firstName;

    /** @param string */
    protected $lastName;

    /** @param string */
    protected $groupNumber;

    /** @param int */
    protected $points;

    /** @param string */
    protected $gender;

    /** @param int */
    protected $birthYear;
    
    /** @param string */
    protected $email;

    /** @param string */
    protected $passwordHash;

    /** @param string */
    protected $authToken;

    /** @param string */
    protected $residence;

    /** @param string */
    protected $createdAt;

    /** @return string */
    public function getFirstName(): string  {
        return $this->firstName;
    }

    /** 
     * @param string $firstName
     * 
     * @return void
     */
    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    /** @return string */
    public function getLastName(): string {
        return $this->lastName;
    }

    /** 
     * @param string $lastName
     * 
     * @return void
     */
    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    /** @return string */
    public function getGroupNumber(): string {
        return $this->groupNumber;
    }

    /** 
     * @param string $groupNumber
     * 
     * @return void
     */
    public function setGroupNumber(string $groupNumber): void {
        $this->groupNumber = $groupNumber;
    }

    /** @return int */
    public function getPoints(): int {
        return $this->points;
    }

    /** 
     * @param int $points
     * 
     * @return void
     */
    public function setPoints(int $points): void {
        $this->points = $points;
    }

    /** @return string */
    public function getGender(): string {
        return $this->gender;
    }

    /** 
     * @param string $gender
     * 
     * @return void
     */
    public function setGender(string $gender): void {
        $this->gender = $gender;
    }

    /** @return int */
    public function getBirthYear(): int {
        return $this->birthYear;
    }

    /** 
     * @param int $birthYear
     * 
     * @return void
     */
    public function setBirthYear(int $birthYear): void {
        $this->birthYear = $birthYear;
    }

    /** @return string */
    public function getEmail(): string {
        return $this->email;
    }

    /** @return string */
    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    /** @return string */
    public function getAuthToken(): string {
        return $this->authToken;
    }

    /** @return string */
    public function getResidence(): string {
        return $this->residence;
    }

    /** 
     * @param string $residence
     * 
     * @return void
     */
    public function setResidence(string $residence): void {
        $this->residence = $residence;
    }

    /** @return string */
    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    /** @return string */
    public static function getTableName(): string
    {
        return 'students';
    }

    /**
     * @param string $keyword text from search field
     * 
     * @param string $order
     * 
     * @param string $direction
     * 
     * @return array|null
     */
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

    /** 
     * @param array $userData
     * 
     * @return User
     */
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

    /** 
     * @param array $userData
     * 
     * @return User
     */
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

    /** 
     * @param array $userData
     * 
     * @return User
     */
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

    /** @return void */
    private function refreshAuthToken(): void {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }
}
