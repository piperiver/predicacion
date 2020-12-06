<link rel="stylesheet" href="<?=dominio("assets/recorridos.css")?>">
<h1 class='text-center'>Historial de recorridos</h1>
<div class='content-dates'>
  <div class='item-date'>
    <?php foreach ($data['reboot'] as $item) {?>
    <div class="row-date date">
        Inicio el <?=strftime('%d de %B del %Y', strtotime($item->fechaReset))?>
        <img src="<?= dominio("assets/images/flecha-curva.svg") ?>" class="flecha" srcset="">
    </div>
    <div class="row-date"></div>
    <?php }?>
  </div>
  <div class='item-date'>
    <?php foreach ($data['reboot'] as $item) {?>
    <div class='row-days'><?=$item->dias?> Días</div>
    <?php }?>
  </div>
</div>
