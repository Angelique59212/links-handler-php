<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Links Handler</title>
</head>
<body><?php

// error message
if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);

    foreach ($errors as $error) {?>
        <div class="alert-error"><?= $error ?></div><?php
    }
}

//success message
if (isset($_SESSION['success'])) {
    $message = $_SESSION['success'];
    unset($_SESSION['success']);
    ?>
    <div class="alert-success"><?= $message ?></div><?php
}?>

<header>
    <div id="menu">
        <div id="login"><?php

            use App\Controller\UserController;

            if (!UserController::verifyUserConnect()) { ?>
                    <ul>
                        <li><a class="link-menu" href="/?c=home">Accueil</a></li>
                        <li><a class="link-menu" href="/?c=user&a=login">Se Connecter</a></li>
                        <li><a class="link-menu" href="/?c=user&a=register">S'enregistrer</a></li>
                    </ul><?php
            } else { ?>
                    <ul>
                        <li><a class="link-menu" href="/?c=home">Accueil</a></li>
                        <li><a class="link-menu" href="/?c=link&a=add-link">Ajouter un lien</a></li>
                        <li><a class="link-menu" href="/c=user&a=disconnect">Se déconnecter</a></li>
                    </ul>
                <?php
            } ?>
        </div>
    </div>
</header>

<main class="container">
    <?= $html ?>
</main>

<script src="build/js/front-bundle.js"></script>
</body>
</html>