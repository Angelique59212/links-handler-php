<div id="container-login">
    <div id="login-container">
        <form action="/?c=user&a=login" method="post">
            <div>
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" minlength="2" maxlength="70" required>
            </div>
            <div>
                <label for="password">Mot de passe: </label>
                <input type="password" name="password" id="password" minlength="7" maxlength="70" required>
            </div>

            <input type="submit" name="submit" class="submit" value="Se connecter">
        </form>

    </div>
</div>
