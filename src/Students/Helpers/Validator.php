<?php

namespace Students\Helpers;
use Students\Exceptions\InvalidArgumentException;

class Validator {

    /**
     * @param array $userData
     * 
     * @param string $fn used to identify the method where validation was called
     * 
     * @return void
     */
    public static function validateAllFields(array $userData, string $fn): void
    {
        if($fn === 'login' || $fn === 'register') {
            self::validateEmail($userData['email']);
            self::validatePassword($userData['password']);
        }
        
        if($fn === 'edit' || $fn === 'register') {
            self::validateFirstName($userData['firstName']);
            self::validateLastName($userData['lastName']);
            self::validateGender($userData['gender']);
            self::validateGroupNumber($userData['groupNumber']);
            self::validateBirthYear($userData['birthYear']);
            self::validatePoints($userData['points']);
            self::validateResidence($userData['residence']);
        }
    }

    /** 
     * @param string $firstName
     * 
     * @return void
     */
    private static function validateFirstName(string $firstName): void
    {
        if (empty($firstName)) {
            throw new InvalidArgumentException('Не передано имя');
        }
    }

    /** 
     * @param string $lastName
     * 
     * @return void
     */
    private static function validateLastName(string $lastName): void
    {
        if (empty($lastName)) {
            throw new InvalidArgumentException('Не передана фамилия');
        }
    }

    /** 
     * @param string $gender
     * 
     * @return void
     */
    private static function validateGender(string $gender): void
    {
        if (empty($gender)) {
            throw new InvalidArgumentException('Не передан пол');
        }
    }

    /** 
     * @param string $groupNumber
     * 
     * @return void
     */
    private static function validateGroupNumber(string $groupNumber): void
    {
        if (empty($groupNumber)) {
            throw new InvalidArgumentException('Не передан номер группы');
        }

        if ((mb_strlen($groupNumber) < 2) || (mb_strlen($groupNumber) > 6)) {
            throw new InvalidArgumentException('Номер группы не может быть меньше 2 символов или больше 6 символов');
        }
    }

    /** 
     * @param string $email
     * 
     * @return void
     */
    private static function validateEmail(string $email): void
    {
        if (empty($email)) {
            throw new InvalidArgumentException('Не передан электронный адрес');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Электронный адрес некорректен');
        }
    }

    /** 
     * @param string $password
     * 
     * @return void
     */
    private static function validatePassword(string $password): void
    {
        if (empty($password)) {
            throw new InvalidArgumentException('Не передан пароль');
        }

        if (mb_strlen($password) < 8) {
            throw new InvalidArgumentException('Пароль должен быть не менее 8 символов');
        }
    }

    /** 
     * @param string $birthYear
     * 
     * @return void
     */
    private static function validateBirthYear(int $birthYear): void
    {
        if (empty($birthYear)) {
            throw new InvalidArgumentException('Не передан год рождения');
        }

        if (($birthYear < 1900) || ($birthYear > 2050)) {
            throw new InvalidArgumentException('Год рождения должен быть между значениями 1900 и 2050');
        }
    }

    /** 
     * @param string $points
     * 
     * @return void
     */
    private static function validatePoints(int $points): void
    {
        if (empty($points)) {
            throw new InvalidArgumentException('Не переданы суммарные баллы ЕГЭ');
        }

        if (($points < 0) || ($points > 300)) {
            throw new InvalidArgumentException('Суммарные баллы ЕГЭ не могут быть меньше 0 или больше 300');
        }
    }
    
    /** 
     * @param string $residence
     * 
     * @return void
     */
    private static function validateResidence(string $residence): void
    {
        if (empty($residence)) {
            throw new InvalidArgumentException('Не передано проживание');
        }
    }
}