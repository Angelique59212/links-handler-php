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
        $this->render('home/home');
    }

    /**
     * @return void
     */
    #[NoReturn] public function register(): void
    {
        self::redirectIfConnected();

        /**
         * verification of information
         */
        if (!isset($_POST['submit'])) {
            $this->render('user/register');
        }

        if (!$this->formIsset('pseudo','email', 'password')) {
            $_SESSION['error'] = "Un champ est manquant";
            header("Location: /?c=user");
            die();
        }

        $pseudo = $this->dataClean(filter_var($_POST['pseudo'], FILTER_SANITIZE_STRING));
        $mail = $this->dataClean(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
        $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
        $passwordRepeat = $this->dataClean($this->getFormField('password-repeat'));

        if ($password !== $passwordRepeat) {
            $_SESSION['error'] = "Les password ne correspondent pas, ou il ne respecte pas les critères de sécurité (minuscule, majuscule, nombre, caractère spécial)";
            header("Location: /?c=user");
            die();
        }

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "L'email n'est pas valide";
            header("Location: /?c=user");
            die();
        }

        if (UserManager::mailExist($mail)) {
            $_SESSION['error'] = "L'email existe déjà";
            header("Location: /?c=user");
            die();
        }

        /**
         * registration of the user and the validation key in the database
         */
        $user = (new User())
            ->setEmail($mail)
            ->setPseudo($pseudo)
            ->setPassword($password)
        ;

        if (!UserManager::addUser($user)) {
            $_SESSION['error'] = "Enregistrement impossible, réessayez plus tard";
            header("Location: /?c=user&a=register");
            die();
        }

//        self::redirectIfConnected();
//
//        if ($this->verifyFormSubmit()) {
//            $mail = $this->dataClean($this->getFormField('email'));
//            $pseudo = $this->dataClean($this->getFormField('pseudo'));
//            $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
//            $passwordRepeat = $this->dataClean($this->getFormField('password-repeat'));
//
//
//            $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
//            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
//                $_SESSION['error'] = "L'adresse mail n'est pas valide";
//            }
//
//            if ($password !== $passwordRepeat) {
//                $_SESSION['error'] = "Les password ne correspondent pas";
//            }
//            else {
//                $user = new User();
//                $user
//                    ->setPseudo($pseudo)
//                    ->setEmail($mail)
//                    ->setPassword(password_hash($password, PASSWORD_ARGON2I));
//                if (!UserManager::mailExist($user->getEmail())) {
//                    if (!UserManager::userExists($user->getPseudo())) {
//                        UserManager::addUser($user);
//                    }
//                    if (null !== $user->getId()) {
//                        $_SESSION['success'] = "Compte activé";
//                        $_SESSION['user'] = $user;
//                        header("Location: /?c=home");
//                    } else {
//                        $_SESSION['errors'] = "Erreur d'enregistrement";
//                    }
//                } else {
//                    $_SESSION['errors'] = "Adresse mail déjà existante";
//                }
//            }
//        }
//        $this->render('user/register');
    }

    #[NoReturn] public function login()
    {
        if (isset($_POST['submit'])) {

            if (!$this->formIsset('email', 'password')) {
                $_SESSION['error'] = "Un champ est manquant";
                header("Location: /?c=user&a=login");
                die();
            }

            $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $user = UserManager::getUserByMail($mail);

            // If user where found from database and password is ok.
            if ($user && password_verify($password, $user->getPassword())) {
              $_SESSION['success'] = "Connexion validée";
                //storing user in session.
              $_SESSION['user'] = $user;
            }
            else {
                $_SESSION['error'] = 'Mot de passe ou adresse mail incorrect';
            }
            header('Location: /?c=home');
            die();
        }

        $this->render('user/login');
//        self::redirectIfConnected();
//
//        if ($this->verifyFormSubmit()) {
//            $errorMessage = "Données non correctes";
//            $mail = $this->dataClean($this->getFormField('email'));
//            $password = $this->getFormField('password');
//
//            $user = UserManager::getUserByMail($mail);
//            if (null === $user) {
//                $_SESSION['errors'][] = $errorMessage;
//            } else {
//                if (password_verify($password, $user->getPassword())) {
//                    $_SESSION['user'] = $user;
//                    $this->redirectIfConnected();
//                } else {
//                    $_SESSION['errors'][] = $errorMessage;
//                }
//            }
//        }
//
//        $this->render('user/login');
    }

    public function disconnect(): void
    {
        // Keeping messages if any
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;

        $_SESSION['user'] = null;
        session_unset();
        session_destroy();

        // Restart session to be able to use messages in session.
        session_start();

        // Setting again existing messages into the session.
        if ($error) {
            $_SESSION['error'] = $error;
        }

        if ($success) {
            $_SESSION['success'] = $success;
        }

        header("Location: /index.php");
    }
}
