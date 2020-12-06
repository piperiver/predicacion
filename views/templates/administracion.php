<link rel="stylesheet" href="<?= dominio("assets/administracion.css") ?>">

<h1 class="text-center">Usuarios</h1>


<div class="content-button text-center">
    <!-- Colored FAB button with ripple -->
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" id="btn-create-user">
    <i class="material-icons">add</i>
    </button>
</div>

<div class="table-responsive">
  <table id="tableUsuarios" class="mdl-data-table mdl-js-data-table mdl-shadow--2dp my-table myTable">
    <thead>
      <tr>
        <th class="text-center">Usuario</th>
        <!-- <th class="text-center">Creación</th> -->
        <th class="text-center" data-select='true'>Administrador</th>
        <th class="text-center" data-select='true'>Activo</th>
        <th class="text-center" data-nofilter='true'>Eliminar</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($data["usuarios"] as $usuario) { ?>
          <tr>
              <td class="text-center"><?= $usuario->usuario ?></td>
              <!-- <td class="text-center"><?= $usuario->fecha_creacion ?></td> -->
              <td class="text-center" data-search="<?= ($usuario->admin)? "SI" : "NO" ?>">
                  <select class="myselect updateUser admin" data-user="<?= $usuario->id ?>">
                    <option value='1' <?= ($usuario->admin)? "selected" : "" ?>>SI</option>
                    <option value="0" <?= (!$usuario->admin)? "selected" : "" ?>>NO</option>
                  </select>
              </td>
              <td class="text-center" data-search="<?= ($usuario->active)? "SI" : "NO" ?>">
                  <select class="myselect updateUser active" data-user="<?= $usuario->id ?>">
                    <option value="1" <?= ($usuario->active)? "selected" : "" ?>>SI</option>
                    <option value="0" <?= (!$usuario->active)? "selected" : "" ?>>NO</option>
                  </select>
              </td>
              <td>
                <span class='material-icons delete-item' data-type='usuario' data-id='<?= $usuario->id ?>'>
                  delete_forever
                </span>
              </td>
          </tr>      
        <?php }  ?>
    </tbody>
  </table>
</div>



<hr style="margin-top: 50px">


<h1 class="text-center">Directorio Teléfonico</h1>

<div class="content-button text-center">
    <!-- Colored FAB button with ripple -->
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" id="btn-create">
    <i class="material-icons">add</i>
    </button>
</div>

<div class="table-responsive">
  <table id="tableTelefonos" class="mdl-data-table mdl-js-data-table mdl-shadow--2dp my-table myTable">
    <thead>
      <tr>
        <th>Teléfono</th>
        <th class="text-center" data-select='true'>Usado</th>
        <th class="text-center" data-select='true'>Estado</th>
        <th class="text-center" data-nofilter='true'>Eliminar</th>
      </tr>
      <tr>
        <th class='content-filter text-center'>
            <input type="text" placeholder="Filtrar" class='search_filter'/>
        </th>
        <th class='content-filter text-center'>
            <select class='search_filter myselect'>
              <option value="">Todos</option>
              <option value="SI">SI</option>
              <option value="NO">NO</option>
            </select>
        </th>
        <th class='content-filter text-center'>
          <select class='search_filter myselect'>
              <option value="">Todos</option>
              <?php foreach($data["estados"] as $estado){ ?>
                  <option value='<?= $estado->id ?>' ><?= $estado->nombre ?></option>
              <?php } ?>
          </select>
        </th>
        <th class='content-filter text-center'></th>
      </tr>
    </thead>
    <tbody>
   
        <?php foreach ($data["telefonos"] as $telefono) { ?>
          <tr data-idtelefono="<?= $telefono->id ?>">
              <td><?= $telefono->telefono ?></td>
              <td class="text-center"><?= ($telefono->usado)? "SI" : "NO" ?></td>
              <td class="text-center" data-search="<?= $telefono->estado ?>">
                  <select class="myselect admin-estado">
                      <?php foreach($data["estados"] as $estado){ ?>
                          <!-- <option value='{"estado": <?= $estado->id ?>, "idtelefono": <?= $telefono->id ?>}'  <?= ($estado->id == $telefono->estado)? 'selected' : '' ?>><?= $estado->nombre ?></option> -->
                          <option value='<?= json_encode(["estado" => $estado->id, "idtelefono" => $telefono->id]) ?>'  <?= ($estado->id == $telefono->estado)? 'selected' : '' ?>><?= $estado->nombre ?></option>
                      <?php } ?>
                  </select>
              </td>
              <td>
                <span class='material-icons delete-item' data-type='telefono' data-id='<?= $telefono->id ?>'>
                  delete_forever
                </span>
              </td>
          </tr>      
        <?php }  ?>
    </tbody>
  </table>
</div>
