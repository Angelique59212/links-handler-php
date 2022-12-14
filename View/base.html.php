<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Links Handler</title>
</head>
<body>
<header>
    <h1>Links Handler</h1>
</header>

<div id="menu">
    <div id="login"><?php

        use App\Controller\UserController;

        if (!UserController::verifyUserConnect()) { ?>
            <a href="/?c=home">Accueil</a>
            <a href="/?c=user&a=login">Se Connecter</a>
            <a href="/?c=user&a=register">S'enregistrer</a><?php
        }
        else
        { ?>
        <a href="/?c=link&a=add-link">Ajouter un lien</a>
        <a href="/c=user&a=disconnect">Se déconnecter</a><?php
        }?>
    </div>
</div>

<main class="container">
    <?= $html ?>
</main>


</body>
</html>