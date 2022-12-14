<?php

namespace App\Controller;

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
}
