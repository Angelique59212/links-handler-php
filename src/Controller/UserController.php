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

            if (!$this->checkPassword($_POST['password'],$_POST['repeat-password'])) {

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
            header('Location: /?c=home');
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

    #[NoReturn] public function disconnect(): void
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

    /**
     * @return void
     */
    #[NoReturn] public function showUser(): void
    {
        $this->redirectIfNotConnected();

        $this->render('user/profile', [
            'profile' => $_SESSION['user']
        ]);
    }

    #[NoReturn] public function editUser()
    {
        $this->redirectIfNotConnected();
        $user = $_SESSION['user'];

        if (isset($_POST['submit'])) {
            $pseudo = filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = null;

            // Change password if required by user (if new password provided)
            if(isset($_POST['password'], $_POST['passwordRepeat'])) {
                if (!$this->checkPassword($_POST['password'], $_POST['passwordRepeat'])) {
                    $_SESSION['error'] = "Les password ne correspondent pas, ou il ne respecte pas les critères de sécurité (minuscule, majuscule, nombre, caractère spécial)";
                    header("Location: /?c=user&a=edit-user");
                    exit;
                }
                $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
            }

            UserManager::editUser($user->getId(), $pseudo, $email, $password);
            $user
                ->setPseudo($pseudo)
                ->setEmail($email)
            ;

            // Save the new User data into the session.
            $_SESSION['user'] = $user;
            $_SESSION['success'] = 'Votre profil a bien été modifié';

            $this->render('user/profile', [
                'profile' => $user
            ]);
        }
        else {
            // If form is not send, showing user profile and profile edition form.
            $this->showUser();
        }
    }

    /**
     * @return void
     */
    #[NoReturn] public function deleteUser(): void
    {
        $this->redirectIfNotConnected();
        $user = $_SESSION['user'];

        // If user still exists.
        if (UserManager::userExists($user->getId())) {
            if(UserManager::deleteUser($user)) {
                $_SESSION['success'] = "Votre compte a bien été supprimé";
                self::disconnect();
            }
            else {
                $_SESSION['error'] = "Impossible de supprimer votre compte, veuillez contacter un administrateur svp";
            }
        }
        $this->index();
    }
}
