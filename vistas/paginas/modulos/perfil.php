<?php
/* session_start(); */

// Verificar si el usuario está logueado
/* if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php');
    exit();
} */

// Datos simulados del usuario
$datosUsuario = [
    'id' => 'USR-20250116-001',
    'nombre' => /* $_SESSION['user_name'] ??  */ 'Dr. María García López',
    'email' => /* $_SESSION['user_email'] ??  */'maria.garcia@agendamaster.com',
    'telefono' => '+52 55 1234 5678',
    'foto' => 'professional-doctor-portrait.png'
];

// Datos de cita pendiente
$citaPendiente = [
    'id' => 'CITA-20250125-0001',
    'especialista' => 'Dr. Carlos Mendoza',
    'especialidad' => 'Odontologia General',
    'fecha' => '2025-08-18',
    'hora' => '10:00',
    'total' => 150.00
];

function formatearFecha($fecha) {
    $fechaObj = new DateTime($fecha);
    $formatter = new IntlDateFormatter(
        'es_ES',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'America/Mexico_City',
        IntlDateFormatter::GREGORIAN
    );
    return $formatter->format($fechaObj);
}
?>
<!-- Header -->
<header class="bg-white border-b border-slate-200 shadow-sm">
  <div class="max-w-7xl mx-auto px-4 py-4">
    <div class="flex items-center justify-between">
      <!-- Lado izquierdo: Logo y saludo -->
      <div class="flex items-center gap-4">
        <div
          class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center shadow-md"
        >
          <i data-lucide="stethoscope" class="h-5 w-5 text-white"></i>
        </div>
        <div>
          <h1 class="text-xl font-bold text-blue-900 font-montserrat">
            Hola,
            <?php echo explode(' ', $datosUsuario['nombre'])[1]; ?>
          </h1>
        </div>
      </div>

      <!-- Lado derecho: Botón cerrar sesión -->
      <button
        onclick="cerrarSesion()"
        class="flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 hover:bg-red-50 hover:border-red-300 hover:text-red-700 transition-colors bg-transparent"
      >
        <i data-lucide="log-out" class="h-4 w-4"></i>
        Cerrar Sesión
      </button>
    </div>
  </div>
</header>

<div class="px-4 py-6">
  <div class="max-w-7xl mx-auto">
    <!-- Tarjeta de cita pendiente -->
    <div class="mb-8">
      <div
        class="rounded-2xl border border-orange-200 bg-gradient-to-r from-orange-50 to-amber-50 p-6 shadow-sm"
      >
        <div class="flex items-start justify-between">
          <div class="flex items-center gap-4">
            <div
              class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center"
            >
              <i data-lucide="alert-circle" class="h-6 w-6 text-orange-600"></i>
            </div>
            <div>
              <h3
                class="text-xl font-bold text-orange-900 font-montserrat mb-1"
              >
                Cita Pendiente de Pago
              </h3>
              <p class="text-orange-700 font-medium">
                Tienes una cita confirmada pendiente de pago
              </p>
            </div>
          </div>
          <div
            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold border bg-orange-100 text-orange-800 border-orange-200"
          >
            <i data-lucide="alert-circle" class="h-4 w-4"></i>
            Pendiente
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <i data-lucide="stethoscope" class="h-5 w-5 text-orange-600"></i>
              <div>
                <p class="text-sm font-medium text-orange-600">Especialista</p>
                <p class="text-lg font-semibold text-orange-900">
                  <?php echo htmlspecialchars($citaPendiente['especialista']); ?>
                </p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <i data-lucide="badge" class="h-5 w-5 text-orange-600"></i>
              <div>
                <p class="text-sm font-medium text-orange-600">Especialidad</p>
                <p class="text-lg font-semibold text-orange-900">
                  <?php echo htmlspecialchars($citaPendiente['especialidad']); ?>
                </p>
              </div>
            </div>
          </div>

          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <i data-lucide="calendar" class="h-5 w-5 text-orange-600"></i>
              <div>
                <p class="text-sm font-medium text-orange-600">Fecha</p>
                <p class="text-lg font-semibold text-orange-900 capitalize">
                  <?php echo formatearFecha($citaPendiente['fecha']); ?>
                </p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <i data-lucide="clock" class="h-5 w-5 text-orange-600"></i>
              <div>
                <p class="text-sm font-medium text-orange-600">Hora</p>
                <p class="text-lg font-semibold text-orange-900">
                  <?php echo htmlspecialchars($citaPendiente['hora']); ?>
                </p>
              </div>
            </div>
          </div>
        </div>

        <div
          class="mt-6 flex items-center justify-between pt-4 border-t border-orange-200"
        >
          <div>
            <p class="text-sm font-medium text-orange-600">Total a pagar</p>
            <p class="text-2xl font-bold text-orange-900">
              $<?php echo number_format($citaPendiente['total'], 2); ?>
            </p>
          </div>
          <button
            onclick="procederAlPago()"
            id="btnPago"
            class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i data-lucide="credit-card" class="h-5 w-5"></i>
            Proceder al Pago
          </button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Información del usuario -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-bold text-blue-900 font-montserrat mb-6">
          Información Personal
        </h2>

        <div class="flex flex-col md:flex-row items-start gap-6">
          <!-- Foto de perfil -->
          <div class="flex-shrink-0">
            <img
              src="<?php echo htmlspecialchars($datosUsuario['foto']); ?>"
              alt="Foto de perfil"
              class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow-md"
            />
          </div>

          <!-- Información -->
          <div class="flex-1 space-y-4">
            <div class="flex items-center gap-3">
              <i data-lucide="user" class="h-5 w-5 text-blue-600"></i>
              <div>
                <p class="text-sm font-medium text-slate-500">Nombre</p>
                <p class="text-lg font-semibold text-slate-900">
                  <?php echo htmlspecialchars($datosUsuario['nombre']); ?>
                </p>
              </div>
            </div>

            <div class="flex items-center gap-3">
              <i data-lucide="mail" class="h-5 w-5 text-blue-600"></i>
              <div>
                <p class="text-sm font-medium text-slate-500">
                  Correo Electrónico
                </p>
                <p class="text-lg font-semibold text-slate-900">
                  <?php echo htmlspecialchars($datosUsuario['email']); ?>
                </p>
              </div>
            </div>

            <div class="flex items-center gap-3">
              <i data-lucide="phone" class="h-5 w-5 text-blue-600"></i>
              <div>
                <p class="text-sm font-medium text-slate-500">Teléfono</p>
                <p class="text-lg font-semibold text-slate-900">
                  <?php echo htmlspecialchars($datosUsuario['telefono']); ?>
                </p>
              </div>
            </div>

            <div class="flex items-center gap-3">
              <i data-lucide="badge" class="h-5 w-5 text-blue-600"></i>
              <div>
                <p class="text-sm font-medium text-slate-500">ID de Usuario</p>
                <p class="text-lg font-semibold text-slate-900">
                  <?php echo htmlspecialchars($datosUsuario['id']); ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Historial de citas -->
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-bold text-blue-900 font-montserrat mb-6">
          Historial de Citas
        </h2>

        <div id="citasContainer" class="space-y-4">
          <!-- Las citas se cargarán con JavaScript -->
        </div>

        <div
          id="paginacion"
          class="flex items-center justify-center gap-2 mt-6 pt-6 border-t border-slate-200"
        >
          <!-- La paginación se cargará con JavaScript -->
        </div>

        <div id="infoPaginacion" class="text-center mt-4">
          <!-- La información de paginación se cargará con JavaScript -->
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de pago -->
<div
  id="paymentModal"
  class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden"
>
  <div
    class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
  >
    <div class="p-6 border-b border-slate-200">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-2xl font-bold text-blue-900 font-montserrat">
            Procesar Pago
          </h3>
          <p class="text-slate-600 mt-1">
            Completa los datos para confirmar tu cita
          </p>
        </div>
        <button
          onclick="cerrarModalPago()"
          class="rounded-full w-10 h-10 p-0 border border-slate-300 hover:bg-slate-100 bg-transparent"
        >
          <i data-lucide="x" class="h-5 w-5"></i>
        </button>
      </div>
    </div>

    <div class="p-6">
      <!-- Resumen de la cita -->
      <div class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-200">
        <h4 class="font-semibold text-blue-900 mb-3">Resumen de la cita</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p class="text-blue-600 font-medium">Especialista</p>
            <p class="text-blue-900">
              <?php echo htmlspecialchars($citaPendiente['especialista']); ?>
            </p>
          </div>
          <div>
            <p class="text-blue-600 font-medium">Especialidad</p>
            <p class="text-blue-900">
              <?php echo htmlspecialchars($citaPendiente['especialidad']); ?>
            </p>
          </div>
          <div>
            <p class="text-blue-600 font-medium">Fecha</p>
            <p class="text-blue-900 capitalize">
              <?php echo formatearFecha($citaPendiente['fecha']); ?>
            </p>
          </div>
          <div>
            <p class="text-blue-600 font-medium">Hora</p>
            <p class="text-blue-900">
              <?php echo htmlspecialchars($citaPendiente['hora']); ?>
            </p>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-blue-200">
          <div class="flex justify-between items-center">
            <span class="font-semibold text-blue-900">Total a pagar:</span>
            <span class="text-2xl font-bold text-blue-900">
              $<?php echo number_format($citaPendiente['total'], 2); ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Contenedor del Payment Brick -->
      <div id="payment-brick-container" class="min-h-[400px]"></div>

      <div
        id="loadingPayment"
        class="flex items-center justify-center py-8 hidden"
      >
        <div
          class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"
        ></div>
        <span class="ml-3 text-blue-700 font-medium">Procesando pago...</span>
      </div>
    </div>
  </div>
</div>

<!-- Modal de alerta personalizada -->
<div
  id="alertModal"
  class="fixed inset-0 z-50 flex items-center justify-center hidden"
>
  <div
    class="fixed inset-0 bg-black bg-opacity-50"
    onclick="cerrarAlerta()"
  ></div>
  <div class="relative bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-xl">
    <button
      onclick="cerrarAlerta()"
      class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors"
    >
      <i data-lucide="x" class="h-5 w-5"></i>
    </button>

    <div class="flex justify-center mb-4">
      <div
        id="alertIcon"
        class="w-16 h-16 rounded-full flex items-center justify-center"
      >
        <!-- El icono se cargará dinámicamente -->
      </div>
    </div>

    <div class="text-center">
      <h3
        id="alertTitle"
        class="text-xl font-semibold text-slate-900 font-montserrat mb-2"
      ></h3>
      <p id="alertMessage" class="text-slate-600 mb-6"></p>
      <button
        id="alertConfirm"
        onclick="confirmarAlerta()"
        class="w-full h-11 rounded-xl text-white font-semibold transition-all duration-200 hover:scale-105 active:scale-95"
      >
        Entendido
      </button>
    </div>
  </div>
</div>

<script>
  lucide.createIcons();
</script>
