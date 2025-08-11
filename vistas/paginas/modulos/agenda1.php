    <?php
    $fechaCita = $_POST['fecha'];
    $horaCita = $_POST['hora'];
    echo '<input type="hidden" id="fecha" name="fecha" value="'.$fechaCita.'">';
    echo '<input type="hidden" id="hora" name="hora" value="'.$horaCita.'">';
    ?>
    <!-- Contenido Principal -->
    <main class="p-4 md:p-6 lg:p-8 max-w-7xl mx-auto">
      <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Columna Izquierda: Calendario y Leyenda -->
        <div class="lg:col-span-2">
          <!-- Leyenda expandida con icono de calendario -->
          <div class="bg-fondo-principal p-2 lg:p-4 rounded-xl shadow-sm mb-6">
            <div class="flex justify-between items-center">
              <div class="flex items-center space-x-2">
                <div class="">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-calendar-icon lucide-calendar w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 text-primario"
                  >
                    <path d="M8 2v4" />
                    <path d="M16 2v4" />
                    <rect width="18" height="18" x="3" y="4" rx="2" />
                    <path d="M3 10h18" />
                  </svg>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <div class="w-5 h-5 bg-[#10b981] rounded"></div>
                <small>Hoy</small>
              </div>
              <div class="flex items-center space-x-2">
                <div class="w-5 h-5 bg-primario rounded"></div>
                <small>Seleccionado</small>
              </div>
              <div class="flex items-center space-x-2">
                <div class="w-5 h-5 bg-boton-deshabilitado-bg rounded"></div>
                <small>No disponible</small>
              </div>
            </div>
          </div>

          <!-- Navegador de Meses/Días -->
          <div class="flex justify-between items-center mb-4">
            <button
              id="prevDays"
              class="p-2 cursor-pointer btn suave bg-transparent border border-borde rounded-full w-fit"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-chevron-left-icon lucide-chevron-left w-5 h-5"
              >
                <path d="m15 18-6-6 6-6" />
              </svg>
            </button>
            <h5 id="monthDisplay"></h5>
            <button
              id="nextDays"
              class="p-2 cursor-pointer btn suave bg-transparent border border-borde rounded-full w-fit"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-chevron-right-icon lucide-chevron-right w-5 h-5"
              >
                <path d="m9 18 6-6-6-6" />
              </svg>
            </button>
          </div>

          <!-- Días de la Semana -->
          <div id="daysContainer" class="grid grid-cols-4 gap-2 mb-6">
            <!-- Los días se generarán dinámicamente por JS -->
          </div>

          <!-- Horarios -->
          <div id="timeSlotsContainer" class="grid grid-cols-4 gap-2 mb-6">
            <!-- Los horarios se generarán dinámicamente por JS -->
          </div>
        </div>

        <!-- Columna Derecha: Resumen de la Cita -->
        <div class="mt-6 lg:mt-0">
          <div class="bg-fondo-principal p-6 rounded-xl shadow-sm">
            <h3 class="mb-6">Resumen de la Cita</h3>

            <!-- Especialidad -->
            <div class="mb-4">
              <h4 class="mb-2">Especialidad</h4>
              <p
                id="summarySpecialty"
                class="encabezado bg-estatus-neutral-bg text-estatus-neutral-texto rounded-lg"
              >
                <?php echo $_POST['especialidad']; ?>
              </p>
            </div>

            <!-- Especialista -->
            <div class="mb-4">
              <h4 class="mb-2">Especialista</h4>
              <div class="flex items-center gap-2">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="lucide lucide-stethoscope-icon lucide-stethoscope w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 text-primario"
                >
                  <path d="M11 2v2" />
                  <path d="M5 2v2" />
                  <path
                    d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"
                  />
                  <path d="M8 15a6 6 0 0 0 12 0v-3" />
                  <circle cx="20" cy="10" r="2" />
                </svg>
                <p id="summaryDoctor"><?php echo $_POST['especialista']; ?></p>
              </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="mb-4">
              <h4 class="mb-2">Fecha y Hora</h4>
              <div class="space-y-2">
                <div class="flex items-center gap-2">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-calendar-icon lucide-calendar w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 text-primario"
                  >
                    <path d="M8 2v4" />
                    <path d="M16 2v4" />
                    <rect width="18" height="18" x="3" y="4" rx="2" />
                    <path d="M3 10h18" />
                  </svg>
                  <p id="summaryDate">martes, 22 de julio de 2025</p>
                </div>
                <div class="flex items-center gap-2">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="lucide lucide-clock4-icon lucide-clock-4 w-4 h-4 md:w-5 md:h-5 lg:w-6 lg:h-6 text-primario"
                  >
                    <path d="M12 6v6l4 2" />
                    <circle cx="12" cy="12" r="10" />
                  </svg>
                  <p id="summaryTime">16:00</p>
                </div>
              </div>
            </div>

            <!-- Duración -->
            <div class="mb-6">
              <h4 class="mb-2">Duración</h4>
              <p>45 minutos</p>
            </div>

            <!-- Separador -->
            <hr class="border-borde mb-6" />

            <!-- Total a pagar -->
            <div class="mb-6">
              <div class="flex justify-between items-center mb-2">
                <h4>Total a pagar:</h4>
                <h4 class="text-green-600">$150.00</h4>
              </div>
              <small>Consulta especializada</small>
            </div>

            <!-- Botón Proceder al Pago -->

            <a
              href="agenda.html"
              class="flex justify-center items-center focus:outline-none gap-2 btn primario w-full cursor-pointer"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-credit-card-icon lucide-credit-card w-5 h-5"
              >
                <rect width="20" height="14" x="2" y="5" rx="2" />
                <line x1="2" x2="22" y1="10" y2="10" />
              </svg>
              Proceder al Pago
            </a>

            <!-- Términos y condiciones -->
            <small class="mt-4 inline-block">
              Al proceder al pago, aceptas nuestros términos y condiciones
            </small>
          </div>
        </div>
      </div>
    </main>