<link rel="stylesheet" href="<?= dominio("assets/formulario.css") ?>">

<h4 class='text-center'>INFORMACIÓN PARA EL PLAN DE EMERGENCIA DE CAÑAVERALES</h4>

<?php if(isset($_SESSION['formulario_plan'])){ ?>
    <div class='mensaje <?= $_SESSION['formulario_plan']['type'] ?>'>
        <?= $_SESSION['formulario_plan']['message'] ?>
    </div>
<?php 
    unset($_SESSION['formulario_plan']);
    } 
?>

<form method="POST" action='<?= dominio("guardarPlanEmergencia") ?>' onsubmit='return confirm("¿Confirma que desea guardar la información diligenciada?")'>
    <fieldset>
        <legend>Información personal</legend>

        <div class="content-fieldset">
            <div class='content-item-form'>
                <label for="perfil">Usted es</label>
                <?php foreach(constantes('PERFIL_HERMANO') as $key => $perfil){ ?>
                    <div>
                        <label class='radio'>
                            <input type="radio" name='perfil' value="<?= $key ?>" <?= ($key == 'estudiante')? 'required' : '' ?>>
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
                        <option value="<?= $key ?>"><?= $grupo ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class='content-item-form'>
                <label for="nombres">Nombres</label>
                <input type="text" name='nombres' id='nombres' required>
            </div>

            <div class='content-item-form'>
                <label for="apellidos">Apellidos</label>
                <input type="text" name='apellidos' id='apellidos' required>
            </div>

            <div class='content-item-form'>
                <label for="direccion">Dirección</label>
                <input type="text" name='direccion' id='direccion' required>
            </div>

            <div class='content-item-form'>
                <label for="telefono">teléfono</label>
                <input type="phone" name='telefono' id='telefono'>
            </div>

            <div class='content-item-form'>
                <label for="celular1">celular 1</label>
                <input type="phone" name='celular1' id='celular1'>
            </div>

            <div class='content-item-form'>
                <label for="celular2">celular 2</label>
                <input type="phone" name='celular2' id='celular2'>
            </div>

            <div class='content-item-form'>
                <label for="fecha_nacimiento">fecha de nacimiento</label>
                <input type="date" name='fecha_nacimiento' id='fecha_nacimiento' required>
            </div>

            <div class='content-item-form content-fecha-bautismo' style='display:none'>
                <label for="fecha_bautismo">fecha de bautismo</label>
                <input type="date" name='fecha_bautismo' id='fecha_bautismo'>
            </div>

            <div class='content-item-form'>
                <label for="correo">correo personal</label>
                <input type="email" name='correo' id='correo'>
            </div>
        
        </div>

    </fieldset>


    <fieldset>
        <legend>Información del Familiar #1</legend>
        
        <div class="content-fieldset">
            <div class='content-item-form'>
                <label for="nombres_familiar_1">nombres familiar</label>
                <input type="text" name='nombres_familiar[]' id='nombres_familiar_1' required>
            </div>

            <div class='content-item-form'>
                <label for="apellidos_familiar_1">Apellidos familiar</label>
                <input type="text" name='apellidos_familiar[]' id='apellidos_familiar_1' required>
            </div>

            <div class='content-item-form'>
                <label for="parentesco_familiar_1">Parentesco</label>
                <input type="text" name='parentesco_familiar[]' id='parentesco_familiar_1' required>
            </div>

            <div class='content-item-form'>
                <label for="ciudad_familiar_1">Ciudad de residencia</label>
                <input type="text" name='ciudad_familiar[]' id='ciudad_familiar_1' required>
            </div>

            <div class='content-item-form'>
                <label for="telefono_familiar_1">Teléfono</label>
                <input type="phone" name='telefono_familiar[]' id='telefono_familiar_1'>
            </div>

            <div class='content-item-form'>
                <label for="celular_familiar_1">Celular</label>
                <input type="phone" name='celular_familiar[]' id='celular_familiar_1'>
            </div>
        </div>
    </fieldset>


    <fieldset>
        <legend>Información del Familiar #2</legend>
        
        <div class="content-fieldset">
            <div class='content-item-form'>
                <label for="nombres_familiar_2">nombres familiar</label>
                <input type="text" name='nombres_familiar[]' id='nombres_familiar_2' required>
            </div>

            <div class='content-item-form'>
                <label for="apellidos_familiar_2">Apellidos familiar</label>
                <input type="text" name='apellidos_familiar[]' id='apellidos_familiar_2' required>
            </div>

            <div class='content-item-form'>
                <label for="parentesco_familiar_2">Parentesco</label>
                <input type="text" name='parentesco_familiar[]' id='parentesco_familiar_2' required>
            </div>

            <div class='content-item-form'>
                <label for="ciudad_familiar_2">Ciudad de residencia</label>
                <input type="text" name='ciudad_familiar[]' id='ciudad_familiar_2' required>
            </div>

            <div class='content-item-form'>
                <label for="telefono_familiar_2">Teléfono</label>
                <input type="phone" name='telefono_familiar[]' id='telefono_familiar_2'>
            </div>

            <div class='content-item-form'>
                <label for="celular_familiar_2">Celular</label>
                <input type="phone" name='celular_familiar[]' id='celular_familiar_2'>
            </div>
        </div>
        
    </fieldset>

    <div class="content-button">
        <button type='submit'>Guardar</button>
    </div>
</form>