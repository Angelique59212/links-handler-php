<?php

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Manager\UserManager;
use JetBrains\PhpStorm\NoReturn;

class UserController extends AbstractController
{
    /**
     * @return void
     */
    #[NoReturn] public function index(): void
    {
        $this->render('home/home');
    }

    /**
     * @return void
     */
    #[NoReturn] public function register(): void
    {
        if ($this->verifyFormSubmit()) {

            $pseudo = $this->dataClean(filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING));
            $mail = $this->dataClean(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
            $password = $this->dataClean($this->getFormField('password'));


            if (!$this->formIsset('pseudo', 'email', 'password')) {
                $_SESSION['error'] = "Un champ est manquant";
                header("Location: /?c=user&a=register");
                exit();
            }

            if ($this->checkPassword($_POST['password'],$_POST['repeat-password'])) {

                $_SESSION['error'] = "Les password ne correspondent pas, ou il ne respecte pas les critères de sécurité (minuscule, majuscule, nombre, caractère spécial)";
                header("Location: /?c=user&a=register");
                exit();
            }

            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "L'email n'est pas valide";
                header("Location: /?c=user&a=register");
                exit();
            }

            if (UserManager::mailExist($mail)) {

                $_SESSION['error'] = "L'email existe déjà";
                header("Location: /?c=user&a=register");
                exit();
            }

            /**
             * registration of the user in the database
             */
            $user = new User();
            $user
                ->setPseudo($pseudo)
                ->setEmail($mail)
                ->setPassword(password_hash($password, PASSWORD_ARGON2I));
            UserManager::addUser($user);

            $_SESSION['user'] = $user;
            $_SESSION['success'] = "Vous êtes bien enregistré";
            $this->render('home/home');
        }
        $this->render('user/register');
    }

    #[NoReturn] public function login()
    {
        if ($this->verifyFormSubmit()) {
            if (!$this->formIsset('pseudo', 'password')) {
                $_SESSION['error'] = "Un champ est manquant";
                header("Location: /?c=user&a=login");
                exit();
            }

            $pseudo = $this->dataClean(filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING));
            $password = $this->getFormField('password');

            $user = UserManager::getUserByPseudo($pseudo);

            // If user where found from database and password is ok.
            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['success'] = "Connexion validée";
                //storing user in session.
                $_SESSION['user'] = $user;
            } else {
                $_SESSION['error'] = 'Mot de passe ou pseudo incorrect';
            }
            header('Location: /?c=home&a=home');
            exit();
        }

        $this->render('user/login');
    }

    public function disconnect(): void
    {
        // Keeping messages if any
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;

        $_SESSION['user'] = null;
        session_unset();
        session_destroy();

        header("Location: /?c=home");
        exit();
    }
}
