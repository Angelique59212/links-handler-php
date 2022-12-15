<?php

namespace App\Controller;

use App\Model\Manager\UserManager;
use JetBrains\PhpStorm\NoReturn;
use User;

class UserController extends AbstractController
{
    /**
     * @return void
     */
    #[NoReturn] public function index(): void
    {
        $this->render('user/register');
    }

    /**
     * @return void
     */
    public function register(): void
    {
       self::redirectIfConnected();

        if (!isset($_POST['submit'])) {
            header('Location: /?c=user');
            die();
        }

        if (!$this->formIsset('pseudo', 'email', 'password', 'password-repeat')) {
            $_SESSION['error'] = "Un champ est manquant";
            header("Location : /?c=user");
            die();
        }

        $pseudo = $this->dataClean(filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING));
        $mail = $this->dataClean(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

        if (!$this->checkPassword($_POST['password'], $_POST['password-repeat'])) {
            $_SESSION['error'] = "Les passwords ne correspondent pas , ou ne respecte pas les critères.";
            header('Location: /?c=user');
            die();
        }

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "L'email n'est pas valide";
            header('Location: /?c=user');
            die();
        }

        if (UserManager::mailExist($mail)) {
            $_SESSION['error'] = "L'email existe déjà";
            header('Location: /?c=user');
            die();
        }

        $user = (new User())
            ->setPseudo($pseudo)
            ->setEmail($mail)
            ->setPassword($password)
            ;

        if (!UserManager::addUser($user)) {
            $_SESSION['error'] = "L'enregistrement a échoué, réessayez plus tard";
            header('Location: /?c=user&a=register');
            die();
        }
    }
}
