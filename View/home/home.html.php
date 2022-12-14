<?php

use App\Controller\UserController;
use App\Model\Entity\Links;

if (isset($data['userLinks'])) {
    $links = $data['userLinks'];
}
?>

<div>
    <h1 class="home">Bienvenue sur Links Handler</h1>
</div>
<div id="image-home">
    <img id="img_home" src="/img/links.jpg" alt="links">
</div>

<div id="link-home">
    <?php
    if (UserController::verifyUserConnect()) { ?>
        <div id="container-link"><?php
            foreach ($links as $link) {
            /* @var Links $link */ ?>
             <div class="link">
                <div>
                    <img src="/img/<?= $link->getImage() ?>" alt="image-name">
                </div>
                <div>
                    <a class="details-link" href="<?= $link->getLink()?>" target="_blank"><?= $link->getName() ?></a>
                    <a class="details-link" id="remove-link" href="/?c=link&a=delete-link&id=<?= $link->getId() ?>">Supprimer</a>
                </div>
            </div><?php
        } ?>
        </div><?php
    }
    ?>
</div>