<?php

namespace App\Controller;

use App\Model\Manager\LinksManager;
use App\Model\Manager\UserManager;
use JetBrains\PhpStorm\NoReturn;
use Links;
use User;

class LinkController extends AbstractController
{
    /**
     * @return void
     */
    #[NoReturn] public function index(): void
    {
        $this->render('home/home');
    }

    #[NoReturn] public function addLink() {
        self::redirectIfNotConnected();
        if ($this->verifyFormSubmit()) {
            $image = $this->getFormFieldImage('image');

            //Redirect if no image provided
            if (!$image) {
                $_SESSION['error'] = "Vous n'avez pas fourni d'image";
                header('Location: /?c=link&a=add-link');
                die();
            }

            $user = $_SESSION['user'];

            //Getting and securing form content
            $name = $this->dataClean($this->getFormField('name'));
            $image = $this->dataClean($this->getFormField('image'));

            $link = new Links();
            $link
                ->setName($name)
                ->setImage($image)
                ->setLinksUser($user)
                ;
            if (LinksManager::addNewLink($link, $name, $image, $_SESSION['user']->getId())) {
                $_SESSION['success'] = "Votre lien a bien été ajouté";
                header('Location: /?c=home&a=home');
            }
            else {
                $this->render('link/add-link');
            }
        }
        $this->render('link/add-link');
    }
}
