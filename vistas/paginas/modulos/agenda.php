<?php
$ruta = ControladorRuta::ctrRuta();
// Datos de la cita (Vienen de la pagina especialistas.php)
$datosCita = [
    'especialidad' => $_POST['especialidad'],
    'especialista' => $_POST['especialista'],
    'id_especialista' => $_POST['id_especialista'],
    'fecha' => $_POST['fecha'], // Formato YYYY-MM-DD
    'hora' => $_POST['hora'],   // Formato HH:MM
    'total' => 150
];

// Eventos ocupados (normalmente vendrían de una base de datos)
$eventosOcupados = [
    ['fecha' => '2025-08-10', 'hora' => '12:00'],
    ['fecha' => '2025-08-10', 'hora' => '13:00'],
    ['fecha' => '2025-08-11', 'hora' => '11:00'],
    ['fecha' => '2025-08-11', 'hora' => '17:00'],
    ['fecha' => '2025-08-12', 'hora' => '14:00'],
    ['fecha' => '2025-08-13', 'hora' => '17:00'],
    ['fecha' => '2025-08-14', 'hora' => '11:00'],
    ['fecha' => '2025-08-15', 'hora' => '12:00'],
    ['fecha' => '2025-08-16', 'hora' => '15:00'],
];

// Función para verificar si hay conflicto
function tieneConflicto($datosCita, $eventosOcupados) {
    
    // Obtener fecha actual (formato YYYY-MM-DD)
    $fechaActual = date('Y-m-d');

    // Si la fecha es anterior o igual a la actual, es conflicto
    if ($datosCita['fecha'] < $fechaActual) {
        return true;
    }

    // Verificar si la fecha y hora de la cita ya están ocupadas
    foreach ($eventosOcupados as $evento) {
        if ($evento['fecha'] === $datosCita['fecha'] && $evento['hora'] === $datosCita['hora']) {
            return true;
        }
    }
    return false;
}

// Función para generar código de cita
function generarCodigoCita($fecha) {
    $fechaFormateada = str_replace('-', '', $fecha);
    $digitosAleatorios = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
    return "CITA-{$fechaFormateada}-{$digitosAleatorios}";
}

$conflicto = tieneConflicto($datosCita, $eventosOcupados);
$codigoCita = $conflicto ? null : generarCodigoCita($datosCita['fecha']);
?>
<div class="mx-auto w-full p-4 md:p-6 lg:p-8 max-w-7xl">

  <!-- Layout responsive -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Columna izquierda: Calendario -->
    <div class="space-y-6">
      <?php if ($conflicto): ?>
      <!-- Mensaje de error cuando hay conflicto -->
      <div class="rounded-2xl border bg-white p-6 shadow-sm">
        <div class="text-center space-y-4">
          <div
            class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center"
          >
            <svg
              class="h-8 w-8 text-red-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              ></path>
            </svg>
          </div>
          <div>
            <h3 class="text-xl font-semibold text-red-700 font-heading mb-2">
              Horario No Disponible
            </h3>
            <p class="text-red-600">
              El horario seleccionado ya está ocupado. Por favor, utiliza el
              botón "Modificar" para seleccionar una nueva fecha y hora
              disponible.
            </p>
          </div>
        </div>
      </div>
      <?php else: ?>
      <!-- Leyendas -->
      <div class="rounded-2xl border bg-white p-4 shadow-sm border-borde">
        <div
          class="flex flex-col items-center gap-4 md:flex-row md:items-center md:gap-6"
        >
          <div
            class="flex h-10 w-10 items-center justify-center rounded-lg border bg-white border-borde"
          >
            <svg
              class="h-5 w-5 text-slate-700"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              ></path>
            </svg>
          </div>
          <div
            class="flex w-full flex-wrap items-center justify-center gap-4 md:justify-start"
          >
            <div class="flex items-center gap-2">
              <span
                class="inline-block h-4 w-6 rounded-md bg-emerald-500"
              ></span>
              <span class="text-slate-600 font-medium">Hoy</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-block h-4 w-6 rounded-md bg-blue-600"></span>
              <span class="text-slate-600 font-medium">Seleccionado</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-block h-4 w-6 rounded-md bg-slate-200"></span>
              <span class="text-slate-600 font-medium">Ocupado</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Navegación de mes y ventana -->
      <div class="flex items-center justify-between">
        <button
          id="btn-anterior"
          class="h-12 w-12  rounded-full border border-borde cursor-pointer bg-white text-slate-700 grid place-items-center transition hover:border-blue-300"
        >
          <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            ></path>
          </svg>
        </button>
        <h2
          id="mes-actual"
          class="text-xl font-semibold tracking-tight text-titulos font-heading"
        ></h2>
        <button
          id="btn-siguiente"
          class="h-12 w-12 rounded-full border border-borde cursor-pointer bg-white text-slate-700 grid place-items-center transition hover:border-blue-300"
        >
          <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            ></path>
          </svg>
        </button>
      </div>

      <!-- Selector de días -->
      <div id="dias-container" class="grid grid-cols-4 gap-4">
        <!-- Los días se generarán con JavaScript -->
      </div>

      <!-- Grilla de horas -->
      <div id="horas-container" class="grid grid-cols-4 gap-4">
        <!-- Las horas se generarán con JavaScript -->
      </div>
      <?php endif; ?>
    </div>

    <!-- Columna derecha: Resumen de la Cita -->
    <div class="lg:sticky lg:top-6 lg:h-fit">
      <section>
        <div class="rounded-2xl border border-borde bg-white p-5 shadow-sm">
          <h3
            class="text-2xl font-extrabold tracking-tight text-titulos font-heading"
          >
            Resumen de la Cita
          </h3>

          <!-- Especialidad -->
          <div class="mt-6">
            <div class="text-xl font-semibold text-blue-900 font-heading">
              Especialidad
            </div>
            <div class="mt-3 rounded-xl bg-slate-100 px-4 py-3 text-slate-600">
              <?php echo htmlspecialchars($datosCita['especialidad']); ?>
            </div>
          </div>

          <!-- Especialista -->
          <div class="mt-6">
            <div class="text-xl font-semibold text-blue-900 font-heading">
              Especialista
            </div>
            <div class="mt-3 flex items-center gap-3 text-slate-700">
              <svg
                class="h-5 w-5 text-blue-700"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                ></path>
              </svg>
              <span class="text-lg"
                ><?php echo htmlspecialchars($datosCita['especialista']); ?></span
              >
            </div>
          </div>

          <!-- Fecha y Hora -->
          <div class="mt-6">
            <div class="text-xl font-semibold text-blue-900 font-heading">
              Fecha y Hora
            </div>
            <div class="mt-3 flex flex-col gap-3 text-slate-700">
              <div class="flex items-center gap-3">
                <svg
                  class="h-5 w-5 text-blue-700"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"
                  ></path>
                </svg>
                <span id="fecha-formateada" class="text-lg capitalize"></span>
              </div>
              <div class="flex items-center gap-3">
                <svg
                  class="h-5 w-5 text-blue-700"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
                <span class="text-lg"
                  ><?php echo htmlspecialchars($datosCita['hora']); ?></span
                >
              </div>
            </div>
            <button
              id="btn-modificar"
              class="mt-3 h-10 px-4 rounded-xl cursor-pointer bg-transparent border border-gray-200 bg-white shadow-sm hover:bg-gray-50"
            >
              Modificar
            </button>
          </div>

          <?php if (!$conflicto && $codigoCita): ?>
          <!-- Código de Cita -->
          <div class="mt-6">
            <div class="text-xl font-semibold text-blue-900 font-heading">
              Código de Cita
            </div>
            <div
              class="mt-3 rounded-xl bg-blue-50 px-4 py-3 text-titulos font-mono text-lg font-semibold"
            >
              <?php echo htmlspecialchars($codigoCita); ?>
            </div>
          </div>

          <!-- Separador -->
          <div class="my-6 h-px bg-gray-200"></div>

          <!-- Total -->
          <div class="flex items-start justify-between">
            <div class="text-xl font-semibold text-blue-900 font-heading">
              Total a pagar:
            </div>
            <div class="text-2xl font-bold text-emerald-600 font-heading">
              $<?php echo number_format($datosCita['total'], 2); ?>
            </div>
          </div>
          <div class="mt-2 text-slate-400">Consulta especializada</div>

          <button
            class="mt-5 h-12 w-full rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-lg gap-2 cursor-pointer flex items-center justify-center"
          >
            <svg
              class="h-5 w-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"
              ></path>
            </svg>
            Preagendar Cita
          </button>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</div>

<!-- Modal de modificación -->
<div id="modal-modificar" class="fixed inset-0 z-50 hidden bg-black/80">
  <div
    class="fixed left-1/2 top-1/2 z-50 grid w-full max-w-md -translate-x-1/2 -translate-y-1/2 gap-4 border bg-white p-6 shadow-lg rounded-lg"
  >
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-semibold font-heading">Modificar Cita</h2>
      <button
        id="btn-cerrar-modal"
        class="rounded-sm opacity-70 hover:opacity-100 cursor-pointer"
      >
        <svg
          class="h-4 w-4"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          ></path>
        </svg>
      </button>
    </div>

    <!-- Toggle de vista -->
    <div class="flex rounded-lg bg-slate-100 p-1">
      <button
        id="btn-vista-fecha"
        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-blue-700 shadow-sm"
      >
        Fecha
      </button>
      <button
        id="btn-vista-hora"
        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-slate-600 hover:text-slate-900 opacity-50 cursor-not-allowed"
        disabled
      >
        Hora
      </button>
    </div>

    <!-- Contenido del modal -->
    <div id="modal-contenido">
      <!-- El contenido se generará con JavaScript -->
    </div>
  </div>
</div>

<script>
    // Datos PHP pasados a JavaScript 
    const ruta = "<?php echo $ruta; ?>";
    const datosCita = <?php echo json_encode($datosCita); ?>;
    const eventosOcupados = <?php echo json_encode($eventosOcupados); ?>;
    const tieneConflictoInicial = <?php echo json_encode($conflicto); ?>;
</script>
