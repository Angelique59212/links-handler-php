<?php

namespace App\Controller;

use App\Model\Manager\LinksManager;
use App\Model\Manager\UserManager;
use JetBrains\PhpStorm\NoReturn;

class HomeController extends AbstractController
{
    /**
     * @return void
     */
    #[NoReturn] public function index(): void
    {
        if (isset($_SESSION['user'])) {
            $user = UserManager::getUserById($_SESSION['user']->getId());
            $userLink = LinksManager::getAllLinkByUserId($user->getId());
            $this->render('home/home', [
                "userLinks" => $userLink,
            ]);
            exit();
        }
        $this->render('home/home');
    }


}
