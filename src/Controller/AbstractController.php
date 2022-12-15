<?php

namespace App\Controller;

use DateTime;
use JetBrains\PhpStorm\NoReturn;

abstract class AbstractController
{
    abstract public function index();

    /**
     * @param string $template
     * @param array $data
     * @return void
     */
    #[NoReturn] public function render(string $template, array $data = []): void
    {
        ob_start();
        require __DIR__ . "/../../View/" . $template . ".html.php";
        $html = ob_get_clean();
        require __DIR__ . "/../../View/base.html.php";
        exit;
    }

    /**
     * checks if the user is logged in
     * @return bool
     */
    public static function verifyUserConnect(): bool
    {
        return isset($_SESSION['user']) && null !== ($_SESSION['user'])->getId();
    }

    /**
     * @return void
     */
    public  function redirectIfNotConnected(): void
    {
        if(!self::verifyUserConnect()) {
            $this->render('user/login');
        }
    }

    /**
     * @return void
     */
    public function redirectIfConnected(): void
    {
        if (self::verifyUserConnect()) {
            $this->render('home/home');
        }
    }

    /**
     * check if the form is submitted
     * @return bool
     */
    public function verifyFormSubmit(): bool
    {
        return isset($_POST['submit']);
    }

    /**
     * @param ...$inputNames
     * @return bool
     */
    public function formIsset(...$inputNames): bool
    {
        foreach ($inputNames as $name) {
            if (!isset($_POST[$name])) {
                return false;
            }
        }
        return true;
    }

    /**
     * sanitize data
     * @param $data
     * @return string
     */
    public function dataClean($data):string
    {
        $data = trim(strip_tags($data));
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    /**
     * @param string $password
     * @param string $password_repeat
     * @return bool
     */
    public function checkPassword(string $password, string $password_repeat): bool
    {
        $uppercase = preg_match('/[A-Z]/', $password);
        $lowercase = preg_match('/[a-z]/', $password);
        $number    = preg_match('/[0-9]/', $password);
        $specialChars = preg_match('/[^\w]/', $password);
        $same = $password === $password_repeat;
        $lenght = strlen($password) >= 7 && strlen($password) <= 70;

        return $uppercase && $lowercase && $number && $specialChars && $same && $lenght;
    }

    /**
     * @param string $field
     * @param $default
     * @return mixed|string
     */
    public function getFormField(string $field, $default = null): mixed
    {
        if (!isset($_POST[$field])) {
            return (null === $default) ? '' : $default;
        }

        return $_POST[$field];
    }

    /**
     * image management
     * @param string $field
     * @return false|string
     */
    public function getFormFieldImage(string $field): bool|string
    {
        // Return false if asked image does not exist.
        if(!$_FILES[$field]) {
            return false;
        }

        if ($_FILES[$field]['error']) {
            $_SESSION['error'] = "Erreur lors de l'upload de l'image";
            return false;
        }

        $authorizedMimeTypes = ['image/jpeg', 'image/jpg', 'image.png'];
        if (!in_array($_FILES[$field]['type'], $authorizedMimeTypes)) {
            $_SESSION['error'] = "Type de fichier non autorisé (uniquement images jpg, jpeg et png)";
            return false;
        }

        $oldName = $_FILES[$field]['name'];
        $newName = (new DateTime())->format('ymdhis') . '-' . uniqid();
        $newName .= substr($oldName, strripos($oldName, '.'));
        if (!move_uploaded_file($_FILES[$field]['tmp_name'], 'uploads/' . $newName)) {
            $_SESSION['error'] = "Echec de l'enregistrement de l'image";
            return false;
        }
        return $newName;
    }
}
