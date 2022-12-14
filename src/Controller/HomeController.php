<?php

namespace App\Controller;

use JetBrains\PhpStorm\NoReturn;

class HomeController extends AbstractController
{
    /**
     * @return void
     */
    #[NoReturn] public function index(): void
    {
        $this->render('home/home');
    }
}
