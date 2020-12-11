<link rel="stylesheet" href="<?= dominio("assets/formulario.css") ?>">

<a href="<?= ruta("PlanEmergenciaResumen") ?>" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
    <span class="material-icons">
        arrow_back
    </span>    
    REGRESAR A LA LISTA
</a>

<h4 class='text-center'>INFORMACIÓN PARA EL PLAN DE EMERGENCIA DE CAÑAVERALES -MODIFICACI&Oacute;N-</h4>

<?php if(isset($_SESSION['formulario_plan'])){ ?>
    <div class='mensaje <?= $_SESSION['formulario_plan']['type'] ?>'>
        <?= $_SESSION['formulario_plan']['message'] ?>
    </div>
<?php 
    unset($_SESSION['formulario_plan']);
    } 
?>

<form method="POST" action='<?= dominio("actualizarPlanEmergencia") ?>' onsubmit='return confirm("¿Confirma que desea modificar la información diligenciada?")'>
    
    <input type="hidden" name="csfr_token" value='<?= $_SESSION['_token_'] ?>'>
    <input type="hidden" name="hermano_id" value='<?= $data['hermano']->id ?>'>

    <fieldset>
        <legend>Información personal</legend>

        <div class="content-fieldset">
            <div class='content-item-form'>
                <label for="perfil">Usted es</label>
                <?php foreach(constantes('PERFIL_HERMANO') as $key => $perfil){ ?>
                    <div>
                        <label class='radio'>
                            <input type="radio" name='perfil' value="<?= $key ?>" <?= ($key == 'estudiante')? 'required' : '' ?> <?= ($data['hermano']->perfil == $key)? 'checked' : '' ?>>
                            <?= $perfil ?>
                        </label>
                    </div>
                <?php } ?>
            </div>

            <div class='content-item-form'>
                <label for="grupo">Seleccione el grupo al que pertenece</label>
                <select name="grupo" id="grupo" required>
                    <option value="">Seleccione una opción</option>
                    <?php foreach(constantes('GRUPOS_CONGRE') as $key => $grupo){ ?>
                        <option value="<?= $key ?>"  <?= ($data['hermano']->grupo == $key)? 'selected' : '' ?> ><?= $grupo ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class='content-item-form'>
                <label for="nombres">Nombres</label>
                <input type="text" name='nombres' id='nombres' required value='<?= $data['hermano']->nombres ?>'>
            </div>

            <div class='content-item-form'>
                <label for="apellidos">Apellidos</label>
                <input type="text" name='apellidos' id='apellidos' required value='<?= $data['hermano']->apellidos ?>'>
            </div>

            <div class='content-item-form'>
                <label for="direccion">Dirección</label>
                <input type="text" name='direccion' id='direccion' required value='<?= $data['hermano']->direccion ?>'>
            </div>

            <div class='content-item-form'>
                <label for="telefono">teléfono</label>
                <input type="phone" name='telefono' id='telefono' value='<?= $data['hermano']->telefono ?>'>
            </div>

            <div class='content-item-form'>
                <label for="celular1">celular 1</label>
                <input type="phone" name='celular1' id='celular1' value='<?= $data['hermano']->celular1 ?>'>
            </div>

            <div class='content-item-form'>
                <label for="celular2">celular 2</label>
                <input type="phone" name='celular2' id='celular2' value='<?= $data['hermano']->celular2 ?>'>
            </div>

            <div class='content-item-form'>
                <label for="fecha_nacimiento">fecha de nacimiento</label>
                <input type="text" name='fecha_nacimiento' id='fecha_nacimiento' class='my-datepicker' readonly required value='<?= date('Y-m-d', strtotime($data['hermano']->nacimiento)) ?>'>
            </div>

            <div class='content-item-form content-fecha-bautismo' style='display:none'>
                <label for="fecha_bautismo">fecha de bautismo</label>
                <input type="text" name='fecha_bautismo' id='fecha_bautismo' class='my-datepicker' readonly value='<?= date('Y-m-d', strtotime($data['hermano']->bautismo)) ?>'>
            </div>

            <div class='content-item-form'>
                <label for="correo">correo personal</label>
                <input type="email" name='correo' id='correo' value='<?= $data['hermano']->correo ?>'>
            </div>
        
        </div>

    </fieldset>

    <?php for ($i=0; $i < count($data['familiares']) ; $i++) { ?>
        <fieldset>
            <legend>Información del Familiar #<?= $i + 1 ?></legend>
            
            <input type="hidden" name='familiar_id[]' value='<?= $data['familiares'][$i]->id ?>'>

            <div class="content-fieldset">
                <div class='content-item-form'>
                    <label for="nombres_familiar_<?= $i + 1 ?>">nombres familiar</label>
                    <input type="text" name='nombres_familiar[]' id='nombres_familiar_<?= $i + 1 ?>' required value='<?= $data['familiares'][$i]->nombres ?>'>
                </div>

                <div class='content-item-form'>
                    <label for="apellidos_familiar_<?= $i + 1 ?>">Apellidos familiar</label>
                    <input type="text" name='apellidos_familiar[]' id='apellidos_familiar_<?= $i + 1 ?>' required value='<?= $data['familiares'][$i]->apellidos ?>'>
                </div>

                <div class='content-item-form'>
                    <label for="parentesco_familiar_<?= $i + 1 ?>">Parentesco</label>
                    <input type="text" name='parentesco_familiar[]' id='parentesco_familiar_<?= $i + 1 ?>' required value='<?= $data['familiares'][$i]->parentesco ?>'>
                </div>

                <div class='content-item-form'>
                    <label for="ciudad_familiar_<?= $i + 1 ?>">Ciudad de residencia</label>
                    <input type="text" name='ciudad_familiar[]' id='ciudad_familiar_<?= $i + 1 ?>' required value='<?= $data['familiares'][$i]->ciudad ?>'>
                </div>

                <div class='content-item-form'>
                    <label for="telefono_familiar_<?= $i + 1 ?>">Teléfono</label>
                    <input type="phone" name='telefono_familiar[]' id='telefono_familiar_<?= $i + 1 ?>' value='<?= $data['familiares'][$i]->telefono ?>'>
                </div>

                <div class='content-item-form'>
                    <label for="celular_familiar_<?= $i + 1 ?>">Celular</label>
                    <input type="phone" name='celular_familiar[]' id='celular_familiar_<?= $i + 1 ?>' value='<?= $data['familiares'][$i]->celular ?>'>
                </div>
            </div>
        </fieldset> 
    <?php } ?>


    
    <div class="content-button">
        <button type='submit'>Modificar</button>
    </div>
</form>