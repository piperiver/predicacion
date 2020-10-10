<link rel="stylesheet" href="<?= dominio("assets/administracion.css") ?>">

<h1 class="text-center">Directorio Teléfonico</h1>

<div class="content-button text-center">
    <!-- Colored FAB button with ripple -->
    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" id="btn-create">
    <i class="material-icons">add</i>
    </button>
</div>

<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp my-table myTable">
  <thead>
    <tr>
      <th>Teléfono</th>
      <th class="text-center">Usado</th>
      <th class="text-center">Estado</th>
    </tr>
  </thead>
  <tbody>
      <?php foreach ($data["telefonos"] as $telefono) { ?>
        <tr data-idtelefono="<?= $telefono->id ?>">
            <td><?= $telefono->telefono ?></td>
            <td class="text-center"><?= ($telefono->usado)? "SI" : "NO" ?></td>
            <td class="text-center">
                <select class="myselect admin-estado">
                    <?php foreach($data["estados"] as $estado){ ?>
                        <!-- <option value='{"estado": <?= $estado->id ?>, "idtelefono": <?= $telefono->id ?>}'  <?= ($estado->id == $telefono->estado)? 'selected' : '' ?>><?= $estado->nombre ?></option> -->
                        <option value='<?= json_encode(["estado" => $estado->id, "idtelefono" => $telefono->id]) ?>'  <?= ($estado->id == $telefono->estado)? 'selected' : '' ?>><?= $estado->nombre ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>      
      <?php }  ?>
  </tbody>
</table>
