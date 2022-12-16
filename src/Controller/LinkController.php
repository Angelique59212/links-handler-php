<?php

namespace App\Controller;

use App\Model\Entity\Links;
use App\Model\Manager\LinksManager;
use JetBrains\PhpStorm\NoReturn;

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
            $image = $this->getFormFieldImage('imageName');
            $linkName = $this->getFormField('link');

            //Redirect if no image provided
            if (!$image) {
                $_SESSION['error'] = "Vous n'avez pas fourni d'image";
                header('Location: /?c=link&a=add-link');
                exit();
            }

            $user = $_SESSION['user'];

            //Getting and securing form content
            $name = $this->dataClean($this->getFormField('title'));

            $link = new Links();
            $link
                ->setName($name)
                ->setImage($image)
                ->setLink($linkName)
                ->setLinksUser($user)
                ;
            if (LinksManager::addNewLink($link)) {
                $_SESSION['success'] = "Votre lien a bien été ajouté";
                header('Location: /?c=home');
                exit();
            }
            else {
                $this->render('link/add-link');
            }
        }
        $this->render('link/add-link');
    }
}
