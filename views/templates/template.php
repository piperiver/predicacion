<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Material Design Lite</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="<?= dominio("assets/images/android-desktop.png") ?>">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="<?= dominio("assets/images/ios-desktop.png") ?>">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="<?= dominio("assets/images/touch/ms-touch-icon-144x144-precomposed.png") ?>">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="<?= dominio("assets/images/favicon.png") ?>">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.teal-red.min.css">

    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-html5-1.6.5/r-2.2.6/datatables.min.css"/>


    <link rel="stylesheet" href="<?= dominio("assets/styles.css") ?>">
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style>
  </head>
  <body>


  <dialog class="mdl-dialog">
  <h4 class="mdl-dialog__title text-center">Creación de usuario</h4>
  <div class="mdl-dialog__content">
  <!-- imput username   -->
  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" type="text" id="username">
    <label class="mdl-textfield__label" for="sample3">Usuario</label>
  </div>

  <!-- input password -->
  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
    <input class="mdl-textfield__input" type="password" id="password">
    <label class="mdl-textfield__label" for="sample3">Contrase&ntilde;a</label>
  </div>

  <!-- input is admin -->
    <label for="is_admin">Administrador</label>
    <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="is_admin">
      <input type="checkbox" id="is_admin" class="mdl-switch__input" value="true">
      <span class="mdl-switch__label"></span>
    </label>
    
    <div class="errores" style="display: none">
      
    </div>

  </div>
  <div class="mdl-dialog__actions ">
    <button type="button" class="mdl-button bg-azul" id="btn-save-user">Guardar</button>
    <button type="button" class="mdl-button bg-rojo close">Cancelar</button>
  </div>
</dialog>



    <div class="demo-layout mdl-layout mdl-layout--fixed-header mdl-js-layout mdl-color--grey-100">
      <header class="demo-header mdl-layout__header mdl-layout__header--scroll mdl-color--grey-100 mdl-color-text--grey-800">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">Predicaci&oacute;n Congregaci&oacute;n Ca&ntilde;averales</span>
          <div class="mdl-layout-spacer"></div>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable content-menu">
            <!-- Right aligned menu below button -->
            <button id="demo-menu-lower-right"
                    class="mdl-button mdl-js-button mdl-button--icon">
              <i class="material-icons">more_vert</i>
            </button>

            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect opciones-menu"
                for="demo-menu-lower-right">
                <?php if(is_admin()){ ?>
                  <li class="mdl-menu__item"><a href="<?= ruta("Admin55210") ?>">Administraci&oacute;n</a></li>
                  <li class="mdl-menu__item"><a href="<?= ruta("Predicacion") ?>">Predicaci&oacute;n</a></li>
                <?php } ?>

                <?php if(is_session()){ ?>
                  <li class="mdl-menu__item"><a href="<?= ruta("Salir") ?>">Salir</a></li>
                <?php } ?>
            </ul>


            <!-- <ul class="opciones-menu">
              <?php if(is_admin()){ ?>
              <li><a href="<?= ruta("Administracion") ?>">Administraci&oacute;n</a></li>
              <li><a href="<?= ruta("Predicacion") ?>">Predicaci&oacute;n</a></li>
              <?php } ?>

              <?php if(is_session()){ ?>
                <li><a href="<?= ruta("Salir") ?>">Salir</a></li>
                <?php } ?>
            </ul> -->
          </div>
        </div>
      </header>
      <div class="demo-ribbon"></div>
      <main class="demo-main mdl-layout__content">
        <div class="demo-container mdl-grid">
          <div class="mdl-cell mdl-cell--2-col mdl-cell--hide-tablet mdl-cell--hide-phone"></div>
          <div class="demo-content mdl-color--white mdl-shadow--4dp content mdl-color-text--grey-800 mdl-cell mdl-cell--8-col content-card">
                <?php include "views/templates/$vista"; ?>
          </div>
        </div>
        <footer class="demo-footer mdl-mini-footer">
          <?= strftime("%A, %d de %B de %Y") ?>
          <!-- <div class="mdl-mini-footer--left-section text-center"> -->
            <!-- <ul class="mdl-mini-footer--link-list"> -->
              <!-- <li></li> -->
              <!-- <li><a href="#"></a></li> -->
              <!-- <li><a href="#">User Agreement</a></li> -->
            <!-- </ul> -->
          <!-- </div> -->
        </footer>
      </main>
    </div>
    <a target="_blank" id="view-source" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-color--accent mdl-color-text--accent-contrast" style="display:none">Instrucciones</a>
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.22/b-1.6.5/b-html5-1.6.5/r-2.2.6/datatables.min.js"></script>

    <script>
      const dominio = "<?= ruta("") ?>";

      var dialog = document.querySelector('dialog');
    
      if (!dialog.showModal) {
        dialogPolyfill.registerDialog(dialog);
      }
    
      dialog.querySelector('.close').addEventListener('click', function() {
        dialog.close();
      });
    </script>
    <script src="<?= dominio("assets/script.js") ?>"></script>
  </body>
</html>
