<div>
    <link rel="stylesheet" href="<?= dominio("assets/login.css") ?>">

    <h1 class="text-center">Iniciar Sesión</h1>
    <div class="text-center">
        <div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="login_username" autocomplete="nope">
                <label class="mdl-textfield__label" for="login_username">USUARIO</label>
                <span class="mdl-textfield__error">Digits only</span>
            </div>
        </div>
        <div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="password" id="login_password" autocomplete="nope">
                <label class="mdl-textfield__label" for="login_password">CONTRASE&Ntilde;A</label>
            </div>
        </div>

    </div>
    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored bg-verde btn-next" id="btn-login">
        ENTRAR
    </button>

</div>
<script>
    const login = true;
</script>