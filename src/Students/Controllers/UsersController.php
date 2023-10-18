<?php

namespace Students\Controllers;

use \Students\Controllers\AbstractController;
use \Students\Models\Users\User;
use \Students\Exceptions\InvalidArgumentException;
use \Students\Exceptions\UnauthorizedException;
use \Students\Exceptions\ForbiddenException;
use \Students\Models\Users\UsersAuthService;
use \Students\Helpers\Validator;

class UsersController extends AbstractController
{

    public function register()
    {
        if (!empty($_POST)) {
            try {
                Validator::validateAllFields($_POST, 'register');
                $user = User::register($_POST);
                UsersAuthService::createToken($user);
                echo "<script>";
                echo "alert('" . $user->getFirstName() . ", ваши данные успешно сохранены, вы можете при желании их отредактировать');";
                echo "window.location = '/';";
                echo "</script>";
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/register.php', ['error' => $e->getMessage()]);
                return;
            }
        }
        $this->view->renderHtml('users/register.php');
    }

    public function login()
    {
        if (!empty($_POST)) {
            try {
                Validator::validateAllFields($_POST, 'login');
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
                header('Location: /');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }
        $this->view->renderHtml('users/login.php');
    }

    public function logout()
    {
        setcookie('token', '', -1, '/', '', false, true);
        header('Location: /');
    }

    public function edit(int $userId)
    {

        $user = User::getById($userId);

        if ($user === null) {
            throw new UnauthorizedException('Необходимо авторизоваться!');
        }

        if($user != UsersAuthService::getUserByToken($_COOKIE['token'])) {
            throw new ForbiddenException('Нельзя редактировать чужие данные!');
        }

        if (!empty($_POST)) {
            try {
                Validator::validateAllFields($_POST, 'edit');
                $user->updateFromArray($_POST);
                echo "<script>";
                echo "alert('" . $user->getFirstName() . ", ваши данные успешно сохранены, вы можете при желании их отредактировать');";
                echo "window.location = '/';";
                echo "</script>";
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/edit.php', ['error' => $e->getMessage()]);
                return;
            }
        }

        $this->view->renderHtml('users/edit.php', ['user' => $user]);
    }
}
