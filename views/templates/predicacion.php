<link rel="stylesheet" href="<?= dominio("assets/predicacion.css") ?>">

<div class="text-center">
    <h1 class="text-center" id="text-telefono">0</h1>
</div>
<div class="text-center" id="content-text-estado" style="display: none">
    <p id="text-estado">El numero de telefono quedo marcado como</p>
</div>
<div class="text-center content-options">
    <div>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect bg-verde btn-estado"
            data-estado="<?= constantes('REVISITA') ?>" data-color="verde" disabled>
            Revisita
        </button>
    </div>
    <div>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect bg-rojo btn-estado"
            data-estado="<?= constantes('NO_LLAMAR') ?>" data-color="rojo" disabled>
            No Llamar
        </button>
    </div>
    <div>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect bg-amarillo btn-estado"
            data-estado="<?= constantes('INACTIVO') ?>" data-color="amarillo" disabled>
            N&uacute;mero Da&ntilde;ado
        </button>
    </div>
</div>

<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect bg-azul btn-next" id="btn-get-telefono"
    data-init="true">
    iniciar a predicar
</button>