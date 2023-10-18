<?php

namespace Students\Helpers;
use Students\Exceptions\InvalidArgumentException;

class Validator {

    public static function validateAllFields(array $userData, string $fn) 
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

    private static function validateFirstName(string $firstName) 
    {
        if (empty($firstName)) {
            throw new InvalidArgumentException('Не передано имя');
        }
    }

    private static function validateLastName(string $lastName)
    {
        if (empty($lastName)) {
            throw new InvalidArgumentException('Не передана фамилия');
        }
    }

    private static function validateGender(string $gender)
    {
        if (empty($gender)) {
            throw new InvalidArgumentException('Не передан пол');
        }
    }

    private static function validateGroupNumber(string $groupNumber)
    {
        if (empty($groupNumber)) {
            throw new InvalidArgumentException('Не передан номер группы');
        }

        if ((mb_strlen($groupNumber) < 2) || (mb_strlen($groupNumber) > 6)) {
            throw new InvalidArgumentException('Номер группы не может быть меньше 2 символов или больше 6 символов');
        }
    }

    private static function validateEmail(string $email)
    {
        if (empty($email)) {
            throw new InvalidArgumentException('Не передан электронный адрес');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Электронный адрес некорректен');
        }
    }

    private static function validatePassword(string $password)
    {
        if (empty($password)) {
            throw new InvalidArgumentException('Не передан пароль');
        }

        if (mb_strlen($password) < 8) {
            throw new InvalidArgumentException('Пароль должен быть не менее 8 символов');
        }
    }

    private static function validateBirthYear(int $birthYear)
    {
        if (empty($birthYear)) {
            throw new InvalidArgumentException('Не передан год рождения');
        }

        if (($birthYear < 1900) || ($birthYear > 2050)) {
            throw new InvalidArgumentException('Год рождения должен быть между значениями 1900 и 2050');
        }
    }

    private static function validatePoints(int $points)
    {
        if (empty($points)) {
            throw new InvalidArgumentException('Не переданы суммарные баллы ЕГЭ');
        }

        if (($points < 0) || ($points > 300)) {
            throw new InvalidArgumentException('Суммарные баллы ЕГЭ не могут быть меньше 0 или больше 300');
        }
    }
    
    private static function validateResidence(string $residence)
    {
        if (empty($residence)) {
            throw new InvalidArgumentException('Не передано проживание');
        }
    }
}