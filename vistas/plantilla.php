<?php

$ruta = ControladorRuta::ctrRuta();
$servidor = ControladorRuta::ctrServidor();

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Title -->
    <title>Odonto365</title>

    <!-- Favicon -->

    <!--==================== CSS ====================-->

    <!-- lucide -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <!-- CSS Perzonalizado -->
    <link rel="stylesheet" href="vistas/src/css/output.css" />
  </head>
  <body class="font-body bg-fondo-alternativo min-h-screen">

      <?php
      if (isset($_GET["pagina"])) {
        if (
          $_GET["pagina"] == "especialistas" ||
          $_GET["pagina"] == "agenda" ||
          $_GET["pagina"] == "perfil"
          ) {
          include "paginas/modulos/header-".$_GET["pagina"].".php";
        } else {
          include "paginas/modulos/header-inicio.php";
        }
      } else {
        include "paginas/modulos/header-inicio.php";
      } 
      ?>

    <!--==================== MAIN ====================-->
    <main>
      <?php

      if (isset($_GET["pagina"])) {

        if (
          $_GET["pagina"] == "especialistas" ||
          $_GET["pagina"] == "agenda" ||
          $_GET["pagina"] == "perfil"
        ) {
          include "paginas/" . $_GET["pagina"] . ".php";
        } else {
          include "paginas/404.php";
        }
      } else {
        include "paginas/inicio.php";
      }

      ?>
    </main>
    <script src="vistas/src/js/main.js"></script>
    <script src="vistas/src/js/especialistas.js"></script>
    <script src="vistas/src/js/agenda.js"></script>
  </body>
</html>
