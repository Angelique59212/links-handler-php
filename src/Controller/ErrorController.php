<?php

namespace App\Controller;

use JetBrains\PhpStorm\NoReturn;

class ErrorController extends AbstractController
{
    /**
     * @param $askPage
     * @return void
     */
    #[NoReturn] public function error404($askPage): void
    {
        $this->render('error/404');
    }

    /**
     * @return void
     */
    public function index(): void
    {

    }
}