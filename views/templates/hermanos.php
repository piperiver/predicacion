<link rel="stylesheet" href="<?= dominio("assets/hermanos.css") ?>">

<h1 class="text-center">Registros Plan de Emergencia</h1>


<!-- <div class="content-button text-center">
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" id="btn-create-user">
    <i class="material-icons">add</i>
    </button>
</div> -->

<div class="content-filters"> 
    <h2 class='titleFilters'>Filtros</h2>
   
    <div class='content-item-form item-filter'>
        <label for="filterFor"><strong>Filtrar Por</strong></label>
        <select id="filterFor">
            <option value="">Seleccione una opci&oacute;n</option>
            <option value="nombres">Nombre Completo</option>
            <option value="grupo">Grupo</option>
            <option value="perfil">Perfil</option>
        </select>
    </div>

    <div id="contentFilterHermanos" class='item-filter'>
            
    </div>

    <div class='item-filter'>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id='desplegarTodos' data-desplegar='true'>
            Abrir Todos
        </button>
    </div>
</div>

<div class="content-total">
    <h3>Total de usuarios mostrados en pantalla: <span id='totalUsuarios'><?= count($data["hermanos"]) ?></span></h3>
</div>

<div class='content-hermanos'>
    <?php foreach($data['hermanos'] as $hermano){ ?>
        <div class="info-hermano dsp-info" id='hermano_<?= $hermano->id ?>' data-id='<?= $hermano->id ?>'>

            <span class='material-icons delete-item deleteHermano' data-id='<?= $hermano->id ?>' data-type='hermano'>
                delete_forever
            </span>

            <div class="nombre"><?= "$hermano->nombres $hermano->apellidos" ?></div>

            <div class="content-info" style='display:none'>

                <div class="mdl-grid">
                    <div class='mdl-cell mdl-cell--12-col'>
                        <label>Direcci&oacute;n</label> <?= $hermano->direccion ?>
                    </div>

                    <div class='mdl-cell mdl-cell--12-col'>
                        <label>Correo</label> <?= $hermano->correo ?>
                    </div>
                    <div class='mdl-cell mdl-cell--12-col'>
                        <label>Fecha de nacimiento</label> <?= ($hermano->nacimiento == '0000-00-00')? 'N/A' : date('d-m-Y', strtotime($hermano->nacimiento)) ?>
                    </div>
                
                    <div class='mdl-cell mdl-cell--12-col'><label>Tel&eacute;fono</label><?= (empty($hermano->telefono))? 'N/A' : $hermano->telefono ?></div>

                    <div class='mdl-cell mdl-cell--6-col'><label>Cel 1</label><?= (empty($hermano->celular1))? 'N/A' : $hermano->celular1 ?></div>
                    <div class='mdl-cell mdl-cell--6-col'><label>Cel 2</label><?= (empty($hermano->celular2))? 'N/A' : $hermano->celular2 ?></div>
                

                    
                    
                    <div class='mdl-cell mdl-cell--12-col'>
                        <hr>
                        <label>Grupo</label> <?= constantes('GRUPOS_CONGRE')[$hermano->grupo] ?>
                    </div>
                
                    <div class='mdl-cell mdl-cell--6-col'>
                        <label>Perfil</label> <?= constantes('PERFIL_HERMANO')[$hermano->perfil] ?>
                    </div>
                    <div class='mdl-cell mdl-cell--6-col'>
                        <label>Bautismo</label> <?= ($hermano->bautismo == '0000-00-00' || $hermano->bautismo == null)? 'N/A' : date('d-m-Y', strtotime($hermano->bautismo)) ?>
                    </div>

                    <div class='mdl-cell mdl-cell--12-col text-center'>
                        <a href="<?= ruta("EditarHermano/$hermano->id") ?>" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                            <span class="material-icons">
                                edit
                            </span>    
                            MODIFICAR
                        </a>
                    </div>
                </div>
            </div>

            <span class='btn-detalle'>
                <span class="material-icons icon">
                    keyboard_arrow_down
                </span>
            </span>
        </div>
    <?php } ?>
</div>

<div id="toast" class="mdl-js-snackbar mdl-snackbar messageBlank">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div>

<script>
    const hermanos = JSON.parse('<?= json_encode($data['hermanos']) ?>');
    const grupos = JSON.parse('<?= json_encode(constantes('GRUPOS_CONGRE')) ?>');
    const perfiles = JSON.parse('<?= json_encode(constantes('PERFIL_HERMANO')) ?>');
</script>