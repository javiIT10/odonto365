    <?php
    if (!isset($_POST['especialistaId']) || empty($_POST['especialistaId'])) {
    // Redirigir a la página principal
    header("Location: /sistema-citas-odonto365/");
    exit();
  
    }

  // Recibir datos de la página anterior
    $datosCita = [
        'especialidad' => $_POST['especialidad'] ?? 'Ortodoncia',
        'especialista' => $_POST['especialista'] ?? 'Dra. María González',
        'fecha' => $_POST['fecha'] ?? '2025-08-14T00:00:00.000Z',
        'hora' => $_POST['hora'] ?? '11:00',
        'precio' => $_POST['precio'] ?? 1500,
        'especialistaId' => $_POST['especialistaId'] ?? 1
    ];

    // Convertir fecha ISO a objeto Date de PHP
    $fechaCita = new DateTime($datosCita['fecha']);

    // Eventos ocupados simulados
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

    // Verificar si hay conflicto
    $fechaClave = $fechaCita->format('Y-m-d');
    $tieneConflicto = false;
    foreach ($eventosOcupados as $evento) {
        if ($evento['fecha'] === $fechaClave && $evento['hora'] === $datosCita['hora']) {
            $tieneConflicto = true;
            break;
        }
    }

    // Generar código de cita
    function generarCodigoCita($fecha) {
        $año = $fecha->format('Y');
        $mes = $fecha->format('m');
        $dia = $fecha->format('d');
        $digitosAleatorios = rand(1000, 9999);
        return "CITA-{$año}{$mes}{$dia}-{$digitosAleatorios}";
    }

    $codigoCita = $tieneConflicto ? null : generarCodigoCita($fechaCita);

    // Constantes
    $horas = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];
    $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $diasSemanaCorto = ['dom', 'lun', 'mar', 'mié', 'jue', 'vie', 'sáb'];
    $mesesCorto = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    ?>

    <main class="min-h-screen bg-slate-50">
        <div class="mx-auto w-full max-w-7xl px-4 py-6">

            <!-- Layout responsive -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Columna izquierda: Calendario -->
                <div class="space-y-6">
                    <?php if ($tieneConflicto): ?>
                    <!-- Mensaje de error cuando hay conflicto -->
                    <div class="rounded-2xl border border-borde bg-white p-6 shadow-sm">
                        <div class="text-center space-y-4">
                            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center">
                                <i data-lucide="clock" class="h-8 w-8 text-red-600"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-red-700 font-montserrat mb-2">Horario No Disponible</h3>
                                <p class="text-red-600">
                                    El horario seleccionado ya está ocupado. Por favor, utiliza el botón "Modificar" para seleccionar
                                    una nueva fecha y hora disponible.
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Leyendas -->
                    <div class="rounded-2xl border border-borde bg-white p-4 shadow-sm">
                        <div class="flex flex-col items-center gap-4 md:flex-row md:items-center md:gap-6">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-borde bg-white">
                                <i data-lucide="calendar" class="h-5 w-5 text-slate-700"></i>
                            </div>
                            <div class="flex w-full flex-wrap items-center justify-center gap-4 md:justify-start">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block h-4 w-6 rounded-md bg-emerald-500"></span>
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
                        <button id="anterior-btn" class="h-12 w-12 rounded-full border border-borde bg-white text-slate-700 grid place-items-center transition hover:border-blue-300 cursor-pointer">
                            <i data-lucide="chevron-left" class="h-5 w-5"></i>
                        </button>
                        <h2 id="mes-titulo" class="text-xl font-semibold tracking-tight text-blue-800 font-montserrat">
                            <?php echo ucfirst($meses[$fechaCita->format('n') - 1]) . ' ' . $fechaCita->format('Y'); ?>
                        </h2>
                        <button id="siguiente-btn" class="h-12 w-12 rounded-full border border-borde bg-white text-slate-700 grid place-items-center transition hover:border-blue-300 cursor-pointer">
                            <i data-lucide="chevron-right" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <!-- Selector de días (visual) -->
                    <div id="dias-grid" class="grid grid-cols-4 gap-4">
                        <!-- Los días se generarán dinámicamente -->
                    </div>

                    <!-- Grilla de horas (visual) -->
                    <div id="horas-grid" class="grid grid-cols-4 gap-4">
                        <!-- Las horas se generarán dinámicamente -->
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Columna derecha: Resumen de la Cita -->
                <div class="lg:sticky lg:top-6 lg:h-fit">
                    <section>
                        <div class="rounded-2xl border border-borde bg-white p-5 shadow-sm">
                            <h3 class="text-2xl font-extrabold tracking-tight text-blue-800 font-montserrat">
                                Resumen de la Cita
                            </h3>

                            <!-- Especialidad -->
                            <div class="mt-6">
                                <div class="text-xl font-semibold text-blue-900 font-montserrat">Especialidad</div>
                                <div class="mt-3 rounded-xl bg-slate-100 px-4 py-3 text-slate-600"><?php echo $datosCita['especialidad']; ?></div>
                            </div>

                            <!-- Especialista -->
                            <div class="mt-6">
                                <div class="text-xl font-semibold text-blue-900 font-montserrat">Especialista</div>
                                <div class="mt-3 flex items-center gap-3 text-slate-700">
                                    <i data-lucide="stethoscope" class="h-5 w-5 text-blue-700"></i>
                                    <span class="text-lg"><?php echo $datosCita['especialista']; ?></span>
                                </div>
                            </div>

                            <!-- Fecha y Hora -->
                            <div class="mt-6">
                                <div class="text-xl font-semibold text-blue-900 font-montserrat">Fecha y Hora</div>
                                <div class="mt-3 flex flex-col gap-3 text-slate-700">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="calendar" class="h-5 w-5 text-blue-700"></i>
                                        <span class="text-lg capitalize">
                                            <?php 
                                            $fechaFormateada = $fechaCita->format('l, j \d\e F \d\e Y');
                                            $fechaFormateada = str_replace(
                                                ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                                                ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'],
                                                $fechaFormateada
                                            );
                                            $fechaFormateada = str_replace(
                                                ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                                                ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
                                                $fechaFormateada
                                            );
                                            echo $fechaFormateada;
                                            ?>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="clock" class="h-5 w-5 text-blue-700"></i>
                                        <span class="text-lg"><?php echo $datosCita['hora']; ?></span>
                                    </div>
                                </div>
                                <button id="modificar-btn" class="mt-3 h-10 px-4 rounded-xl cursor-pointer bg-transparent border border-borde  hover:bg-slate-50 transition-colors">
                                    Modificar
                                </button>
                            </div>

                            <?php if (!$tieneConflicto && $codigoCita): ?>
                            <!-- Código de Cita -->
                            <div class="mt-6">
                                <div class="text-xl font-semibold text-blue-900 font-montserrat">Código de Cita</div>
                                <div class="mt-3 rounded-xl bg-blue-50 px-4 py-3 text-blue-800 font-mono text-lg font-semibold">
                                    <?php echo $codigoCita; ?>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="h-px bg-slate-200 my-6"></div>
                            <div class="flex items-start justify-between">
                                <div class="text-xl font-semibold text-blue-900 font-montserrat">Total a pagar:</div>
                                <div class="text-2xl font-bold text-emerald-600 font-montserrat">
                                    $<?php echo number_format($datosCita['precio'], 2); ?>
                                </div>
                            </div>
                            <div class="mt-2 text-slate-400">Consulta especializada</div>

                            <button class="mt-5 h-12 w-full rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-lg gap-2 cursor-pointer transition-colors flex items-center justify-center">
                                <i data-lucide="calendar" class="h-5 w-5"></i>
                                Preagendar Cita
                            </button>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Modal de modificación -->
            <div id="modal" class="modal fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
                <div class="bg-white rounded-2xl max-w-md mx-4 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-montserrat text-xl font-semibold">Modificar Cita</h2>
                        <button id="cerrar-modal" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <!-- Toggle de vista -->
                    <div class="flex rounded-lg bg-slate-100 p-1 mb-4">
                        <button id="tab-fecha" class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-blue-700 shadow-sm">
                            Fecha
                        </button>
                        <button id="tab-hora" class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-slate-600 hover:text-slate-900 opacity-50 cursor-not-allowed" disabled>
                            Hora
                        </button>
                    </div>

                    <!-- Vista de fecha -->
                    <div id="vista-fecha" class="space-y-4">
                        <!-- Navegación del mes -->
                        <div class="flex items-center justify-between">
                            <button id="mes-anterior" class="p-2 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors">
                                <i data-lucide="chevron-left" class="h-4 w-4"></i>
                            </button>
                            <h3 id="mes-modal" class="font-semibold font-montserrat"></h3>
                            <button id="mes-siguiente" class="p-2 hover:bg-slate-100 rounded-lg cursor-pointer transition-colors">
                                <i data-lucide="chevron-right" class="h-4 w-4"></i>
                            </button>
                        </div>

                        <!-- Días de la semana -->
                        <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-slate-600">
                            <div class="p-2">dom</div>
                            <div class="p-2">lun</div>
                            <div class="p-2">mar</div>
                            <div class="p-2">mié</div>
                            <div class="p-2">jue</div>
                            <div class="p-2">vie</div>
                            <div class="p-2">sáb</div>
                        </div>

                        <!-- Calendario -->
                        <div id="calendario-modal" class="grid grid-cols-7 gap-1">
                            <!-- Los días se generarán dinámicamente -->
                        </div>
                    </div>

                    <!-- Vista de hora -->
                    <div id="vista-hora" class="space-y-4 hidden">
                        <h3 id="fecha-seleccionada-modal" class="font-semibold font-montserrat text-center"></h3>
                        <div class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto" id="horas-modal">
                            <!-- Las horas se generarán dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Inicializar Lucide icons
        lucide.createIcons();

        // Datos de PHP convertidos a JavaScript
        const datosCita = <?php echo json_encode($datosCita); ?>;
        const eventosOcupados = <?php echo json_encode($eventosOcupados); ?>;
        const tieneConflicto = <?php echo json_encode($tieneConflicto); ?>;
        const horas = <?php echo json_encode($horas); ?>;
        const meses = <?php echo json_encode($meses); ?>;
        const diasSemanaCorto = <?php echo json_encode($diasSemanaCorto); ?>;
        const mesesCorto = <?php echo json_encode($mesesCorto); ?>;

        // Variables globales
        let fechaCita = new Date(datosCita.fecha);
        let fechaInicioVentana = new Date(fechaCita);
        fechaInicioVentana.setDate(fechaInicioVentana.getDate() - 3);
        
        let mesModalActual = new Date();
        let fechaModalSeleccionada = null;
        let horaModalSeleccionada = null;
        let vistaModal = 'fecha';

        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        // Funciones de utilidad
        function formatearFecha(fecha) {
            return fecha.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        function esDomingo(fecha) {
            return fecha.getDay() === 0;
        }

        function esMismoDia(a, b) {
            return a.getFullYear() === b.getFullYear() && 
                   a.getMonth() === b.getMonth() && 
                   a.getDate() === b.getDate();
        }

        function sumarDias(fecha, dias) {
            const nueva = new Date(fecha);
            nueva.setDate(nueva.getDate() + dias);
            return nueva;
        }

        function estaOcupado(dia, hora) {
            if (esDomingo(dia)) return true;
            const fechaClave = dia.toISOString().split('T')[0];
            return eventosOcupados.some(e => e.fecha === fechaClave && e.hora === hora);
        }

        // Generar vista del calendario principal
        function generarCalendario() {
            if (tieneConflicto) return;

            const diasGrid = document.getElementById('dias-grid');
            const horasGrid = document.getElementById('horas-grid');
            
            diasGrid.innerHTML = '';
            horasGrid.innerHTML = '';

            // Generar 4 días visibles
            const diasVisibles = [];
            for (let i = 0; i < 4; i++) {
                diasVisibles.push(sumarDias(fechaInicioVentana, i));
            }

            // Generar días
            diasVisibles.forEach(dia => {
                const seleccionado = esMismoDia(dia, fechaCita);
                const esHoy = esMismoDia(dia, hoy);
                const domingo = esDomingo(dia);

                const div = document.createElement('div');
                div.className = `rounded-3xl border border-borde p-4 text-center relative select-none ${
                    domingo ? 'bg-slate-100 text-slate-400 border border-borde-transparent cursor-not-allowed' :
                    seleccionado ? 'bg-blue-700 text-white border border-borde-blue-700 shadow' :
                    'bg-white text-slate-700 '
                }`;

                if (esHoy) {
                    const punto = document.createElement('span');
                    punto.className = `absolute left-2 top-2 h-2 w-2 rounded-full ${
                        seleccionado && !domingo ? 'bg-white' : 'bg-emerald-500'
                    }`;
                    div.appendChild(punto);
                }

                div.innerHTML += `
                    <div class="${seleccionado && !domingo ? 'text-white/90' : 'text-blue-900/90'} text-base capitalize">
                        ${diasSemanaCorto[dia.getDay()]}
                    </div>
                    <div class="text-3xl font-bold leading-tight">${dia.getDate()}</div>
                    <div class="${seleccionado && !domingo ? 'text-white/90' : 'text-blue-900/90'} text-sm">
                        ${mesesCorto[dia.getMonth()]}
                    </div>
                `;

                diasGrid.appendChild(div);
            });

            // Generar horas
            horas.forEach(hora => {
                diasVisibles.forEach((dia, idxDia) => {
                    const ocupado = estaOcupado(dia, hora);
                    const seleccionado = esMismoDia(dia, fechaCita) && hora === datosCita.hora && !ocupado;

                    const div = document.createElement('div');
                    div.className = `h-12 rounded-2xl border border-borde text-sm font-semibold flex items-center justify-center select-none ${
                        ocupado ? 'bg-slate-100 text-slate-400 border border-borde-transparent' :
                        seleccionado ? 'bg-blue-600 text-white border border-borde-blue-600 shadow' :
                        'bg-white text-blue-900 '
                    }`;
                    div.textContent = hora;

                    horasGrid.appendChild(div);
                });
            });
        }

        // Modal
        const modal = document.getElementById('modal');
        const modificarBtn = document.getElementById('modificar-btn');
        const cerrarModalBtn = document.getElementById('cerrar-modal');

        function abrirModal() {
            modal.classList.add('active');
            vistaModal = 'fecha';
            actualizarVistaModal();
            generarCalendarioModal();
        }

        function cerrarModal() {
            modal.classList.remove('active');
        }

        if (modificarBtn) {
            modificarBtn.addEventListener('click', abrirModal);
        }
        cerrarModalBtn.addEventListener('click', cerrarModal);

        // Tabs del modal
        document.getElementById('tab-fecha').addEventListener('click', function() {
            vistaModal = 'fecha';
            actualizarVistaModal();
        });

        document.getElementById('tab-hora').addEventListener('click', function() {
            if (fechaModalSeleccionada) {
                vistaModal = 'hora';
                actualizarVistaModal();
                generarHorasModal();
            }
        });

        function actualizarVistaModal() {
            const tabFecha = document.getElementById('tab-fecha');
            const tabHora = document.getElementById('tab-hora');
            const vistaFecha = document.getElementById('vista-fecha');
            const vistaHora = document.getElementById('vista-hora');

            if (vistaModal === 'fecha') {
                tabFecha.className = 'flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-blue-700 shadow-sm';
                tabHora.className = `flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-slate-600 hover:text-slate-900 ${!fechaModalSeleccionada ? 'opacity-50 cursor-not-allowed' : ''}`;
                vistaFecha.classList.remove('hidden');
                vistaHora.classList.add('hidden');
            } else {
                tabFecha.className = 'flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-slate-600 hover:text-slate-900';
                tabHora.className = 'flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-blue-700 shadow-sm';
                vistaFecha.classList.add('hidden');
                vistaHora.classList.remove('hidden');
            }
        }

        // Navegación de mes en modal
        document.getElementById('mes-anterior').addEventListener('click', function() {
            mesModalActual.setMonth(mesModalActual.getMonth() - 1);
            generarCalendarioModal();
        });

        document.getElementById('mes-siguiente').addEventListener('click', function() {
            mesModalActual.setMonth(mesModalActual.getMonth() + 1);
            generarCalendarioModal();
        });

        function generarCalendarioModal() {
            const mesNombre = meses[mesModalActual.getMonth()];
            const año = mesModalActual.getFullYear();
            document.getElementById('mes-modal').textContent = `${mesNombre.charAt(0).toUpperCase() + mesNombre.slice(1)} ${año}`;

            const primerDia = new Date(año, mesModalActual.getMonth(), 1);
            const ultimoDia = new Date(año, mesModalActual.getMonth() + 1, 0);
            const calendarioModal = document.getElementById('calendario-modal');
            
            calendarioModal.innerHTML = '';

            // Días vacíos al inicio
            for (let i = 0; i < primerDia.getDay(); i++) {
                const div = document.createElement('div');
                div.className = 'p-2';
                calendarioModal.appendChild(div);
            }

            // Días del mes
            for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
                const fecha = new Date(año, mesModalActual.getMonth(), dia);
                const button = document.createElement('button');
                button.textContent = dia;
                
                const esPasado = fecha < hoy;
                const esHoy = esMismoDia(fecha, hoy);
                const esDomingoModal = esDomingo(fecha);
                const estaSeleccionado = fechaModalSeleccionada && esMismoDia(fecha, fechaModalSeleccionada);
                const estaDeshabilitado = esPasado || esHoy || esDomingoModal;
                
                if (estaDeshabilitado) {
                    button.className = 'p-2 text-sm rounded-lg transition-colors text-slate-300 cursor-not-allowed';
                    button.disabled = true;
                } else {
                    button.className = `p-2 text-sm rounded-lg transition-colors ${
                        estaSeleccionado ? 'bg-blue-600 text-white' : 'hover:bg-slate-100 cursor-pointer'
                    }`;
                    button.addEventListener('click', function() {
                        seleccionarFechaModal(fecha);
                    });
                }
                
                calendarioModal.appendChild(button);
            }
        }

        function seleccionarFechaModal(fecha) {
            fechaModalSeleccionada = fecha;
            horaModalSeleccionada = null;
            
            // Habilitar tab de hora
            document.getElementById('tab-hora').disabled = false;
            document.getElementById('tab-hora').classList.remove('opacity-50', 'cursor-not-allowed');
            
            // Cambiar a vista de hora
            vistaModal = 'hora';
            actualizarVistaModal();
            generarHorasModal();
        }

        function generarHorasModal() {
            if (!fechaModalSeleccionada) return;
            
            document.getElementById('fecha-seleccionada-modal').textContent = formatearFecha(fechaModalSeleccionada);
            
            const horasModal = document.getElementById('horas-modal');
            horasModal.innerHTML = '';
            
            horas.forEach(hora => {
                const fechaClave = fechaModalSeleccionada.toISOString().split('T')[0];
                const estaOcupado = eventosOcupados.some(e => e.fecha === fechaClave && e.hora === hora);
                const estaSeleccionado = horaModalSeleccionada === hora;

                const button = document.createElement('button');
                button.className = `p-3 text-sm rounded-lg border border-borde transition-colors ${
                    estaOcupado ? 'bg-slate-100 text-slate-400 cursor-not-allowed' :
                    estaSeleccionado ? 'bg-blue-600 text-white border border-borde-blue-600' :
                    'bg-white hover:bg-slate-50  cursor-pointer'
                }`;
                button.textContent = hora;
                button.disabled = estaOcupado;

                if (!estaOcupado) {
                    button.addEventListener('click', function() {
                        seleccionarHoraModal(hora);
                    });
                }
                
                horasModal.appendChild(button);
            });
        }

        function seleccionarHoraModal(hora) {
            horaModalSeleccionada = hora;

            if (fechaModalSeleccionada) {
                // Actualizar datos de la cita
                datosCita.fecha = fechaModalSeleccionada.toISOString();
                datosCita.hora = hora;
                fechaCita = new Date(fechaModalSeleccionada);

                // Recargar la página con los nuevos datos
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo $ruta; ?>agenda';

                Object.entries(datosCita).forEach(([key, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = String(value);
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        }

        // Navegación del calendario principal
        document.getElementById('anterior-btn').addEventListener('click', function() {
            fechaInicioVentana = sumarDias(fechaInicioVentana, -4);
            generarCalendario();
        });

        document.getElementById('siguiente-btn').addEventListener('click', function() {
            fechaInicioVentana = sumarDias(fechaInicioVentana, 4);
            generarCalendario();
        });

        // Cerrar modal al hacer clic fuera
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                cerrarModal();
            }
        });

        // Inicializar
        generarCalendario();
        generarCalendarioModal();
    </script>

