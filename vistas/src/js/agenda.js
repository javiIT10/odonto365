// Constantes y utilidades
const DIAS_SEMANA_CORTO = ["dom", "lun", "mar", "mié", "jue", "vie", "sáb"];
const MESES = [
  "enero",
  "febrero",
  "marzo",
  "abril",
  "mayo",
  "junio",
  "julio",
  "agosto",
  "septiembre",
  "octubre",
  "noviembre",
  "diciembre",
];
const MESES_CORTO = [
  "ene",
  "feb",
  "mar",
  "abr",
  "may",
  "jun",
  "jul",
  "ago",
  "sep",
  "oct",
  "nov",
  "dic",
];
const HORAS = [
  "08:00",
  "09:00",
  "10:00",
  "11:00",
  "12:00",
  "13:00",
  "14:00",
  "15:00",
  "16:00",
  "17:00",
];

// Estado global
let fechaInicioVentana = new Date();
let modalAbierto = false;
let vistaModal = "fecha";
let fechaModalSeleccionada = null;
let horaModalSeleccionada = null;
let mesModalActual = new Date();
// const eventosOcupados = []; // Declare eventosOcupados
// const tieneConflictoInicial = false; // Declare tieneConflictoInicial
// const datosCita = { fecha: "", hora: "" }; // Declare datosCita

// Utilidades de fecha
function inicioDeDia(fecha) {
  return new Date(fecha.getFullYear(), fecha.getMonth(), fecha.getDate());
}

function sumarDias(fecha, dias) {
  const nuevaFecha = new Date(fecha);
  nuevaFecha.setDate(nuevaFecha.getDate() + dias);
  return nuevaFecha;
}

function sumarMeses(fecha, meses) {
  const nuevaFecha = new Date(fecha);
  nuevaFecha.setMonth(nuevaFecha.getMonth() + meses);
  return nuevaFecha;
}

function esMismoDia(fecha1, fecha2) {
  return (
    fecha1.getFullYear() === fecha2.getFullYear() &&
    fecha1.getMonth() === fecha2.getMonth() &&
    fecha1.getDate() === fecha2.getDate()
  );
}

function esDomingo(fecha) {
  return fecha.getDay() === 0;
}

function claveISOFecha(fecha) {
  const año = fecha.getFullYear();
  const mes = String(fecha.getMonth() + 1).padStart(2, "0");
  const dia = String(fecha.getDate()).padStart(2, "0");
  return `${año}-${mes}-${dia}`;
}

function formatearFechaLarga(fecha) {
  return new Intl.DateTimeFormat("es-ES", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  }).format(fecha);
}

function estaOcupado(fecha, hora) {
  if (esDomingo(fecha)) return true;
  const clave = claveISOFecha(fecha);
  return eventosOcupados.some(
    (evento) => evento.fecha === clave && evento.hora === hora
  );
}

// Inicialización
document.addEventListener("DOMContentLoaded", () => {
  if (!tieneConflictoInicial) {
    inicializarCalendario();
  }
  inicializarModal();
  actualizarFechaFormateada();
});

function inicializarCalendario() {
  const fechaCita = new Date(datosCita.fecha);
  const hoy = new Date();
  const limiteSuperior = sumarMeses(hoy, 2);

  // Configurar fecha inicial de ventana
  const candidatoInicio = sumarDias(fechaCita, -3);
  fechaInicioVentana = new Date(
    Math.max(
      hoy.getTime(),
      Math.min(
        limiteSuperior.getTime() - 3 * 24 * 60 * 60 * 1000,
        candidatoInicio.getTime()
      )
    )
  );

  actualizarCalendario();
  configurarNavegacion();
}

function actualizarCalendario() {
  actualizarMesActual();
  generarDias();
  generarHoras();
}

function actualizarMesActual() {
  const nombreMes = MESES[fechaInicioVentana.getMonth()];
  const capitalizado = nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1);
  document.getElementById(
    "mes-actual"
  ).textContent = `${capitalizado} ${fechaInicioVentana.getFullYear()}`;
}

function generarDias() {
  const container = document.getElementById("dias-container");
  container.innerHTML = "";

  const fechaCita = new Date(datosCita.fecha);
  const hoy = new Date();

  for (let i = 0; i < 4; i++) {
    const dia = sumarDias(fechaInicioVentana, i);
    const seleccionado = esMismoDia(dia, fechaCita);
    const esHoy = esMismoDia(dia, hoy);
    const domingo = esDomingo(dia);

    const diaElement = document.createElement("div");
    diaElement.className = `rounded-3xl border p-4 text-center relative select-none ${
      domingo
        ? "bg-slate-100 text-slate-400 border-transparent cursor-not-allowed"
        : seleccionado
        ? "bg-blue-700 text-white border-blue-700 shadow"
        : "bg-white text-slate-700 border-slate-200"
    }`;

    if (esHoy) {
      const punto = document.createElement("span");
      punto.className = `absolute left-2 top-2 h-2 w-2 rounded-full bg-emerald-500 ${
        seleccionado && !domingo ? "bg-white" : ""
      }`;
      diaElement.appendChild(punto);
    }

    const diaSemana = document.createElement("div");
    diaSemana.className = `text-base capitalize ${
      seleccionado && !domingo ? "text-white/90" : "text-blue-900/90"
    }`;
    diaSemana.textContent = DIAS_SEMANA_CORTO[dia.getDay()];

    const numeroDia = document.createElement("div");
    numeroDia.className = "text-3xl font-bold leading-tight";
    numeroDia.textContent = dia.getDate();

    const mesCorto = document.createElement("div");
    mesCorto.className = `text-sm ${
      seleccionado && !domingo ? "text-white/90" : "text-blue-900/90"
    }`;
    mesCorto.textContent = MESES_CORTO[dia.getMonth()];

    diaElement.appendChild(diaSemana);
    diaElement.appendChild(numeroDia);
    diaElement.appendChild(mesCorto);

    container.appendChild(diaElement);
  }
}

function generarHoras() {
  const container = document.getElementById("horas-container");
  container.innerHTML = "";

  const fechaCita = new Date(datosCita.fecha);

  HORAS.forEach((hora) => {
    for (let i = 0; i < 4; i++) {
      const dia = sumarDias(fechaInicioVentana, i);
      const ocupado = estaOcupado(dia, hora);
      const seleccionado =
        esMismoDia(dia, fechaCita) && hora === datosCita.hora && !ocupado;

      const horaElement = document.createElement("div");
      horaElement.className = `h-12 rounded-2xl border text-sm font-semibold flex items-center justify-center select-none ${
        ocupado
          ? "bg-slate-100 text-slate-400 border-transparent"
          : seleccionado
          ? "bg-blue-600 text-white border-blue-600 shadow"
          : "bg-white text-blue-900 border-slate-200"
      }`;
      horaElement.textContent = hora;

      container.appendChild(horaElement);
    }
  });
}

function configurarNavegacion() {
  const btnAnterior = document.getElementById("btn-anterior");
  const btnSiguiente = document.getElementById("btn-siguiente");

  btnAnterior.addEventListener("click", () => {
    const nuevaFecha = sumarDias(fechaInicioVentana, -4);
    const hoy = new Date();
    if (nuevaFecha >= hoy) {
      fechaInicioVentana = nuevaFecha;
      actualizarCalendario();
    }
  });

  btnSiguiente.addEventListener("click", () => {
    const nuevaFecha = sumarDias(fechaInicioVentana, 4);
    const limiteSuperior = sumarMeses(new Date(), 2);
    if (nuevaFecha <= sumarDias(limiteSuperior, -3)) {
      fechaInicioVentana = nuevaFecha;
      actualizarCalendario();
    }
  });
}

function actualizarFechaFormateada() {
  const fechaCita = new Date(datosCita.fecha);
  document.getElementById("fecha-formateada").textContent =
    formatearFechaLarga(fechaCita);
}

// Modal
function inicializarModal() {
  const btnModificar = document.getElementById("btn-modificar");
  const btnCerrarModal = document.getElementById("btn-cerrar-modal");
  const modal = document.getElementById("modal-modificar");
  const btnVistaFecha = document.getElementById("btn-vista-fecha");
  const btnVistaHora = document.getElementById("btn-vista-hora");

  btnModificar.addEventListener("click", abrirModal);
  btnCerrarModal.addEventListener("click", cerrarModal);

  // Cerrar modal al hacer clic fuera
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      cerrarModal();
    }
  });

  btnVistaFecha.addEventListener("click", () => cambiarVistaModal("fecha"));
  btnVistaHora.addEventListener("click", () => {
    if (fechaModalSeleccionada) {
      cambiarVistaModal("hora");
    }
  });
}

function abrirModal() {
  modalAbierto = true;
  vistaModal = "fecha";
  fechaModalSeleccionada = null;
  horaModalSeleccionada = null;
  mesModalActual = new Date();

  document.getElementById("modal-modificar").classList.remove("hidden");
  actualizarToggleModal();
  mostrarVistaFecha();
}

function cerrarModal() {
  modalAbierto = false;
  document.getElementById("modal-modificar").classList.add("hidden");
}

function cambiarVistaModal(vista) {
  vistaModal = vista;
  actualizarToggleModal();

  if (vista === "fecha") {
    mostrarVistaFecha();
  } else if (vista === "hora" && fechaModalSeleccionada) {
    mostrarVistaHora();
  }
}

function actualizarToggleModal() {
  const btnFecha = document.getElementById("btn-vista-fecha");
  const btnHora = document.getElementById("btn-vista-hora");

  if (vistaModal === "fecha") {
    btnFecha.className =
      "flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-blue-700 shadow-sm";
    btnHora.className = `flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-slate-600 hover:text-slate-900 ${
      !fechaModalSeleccionada ? "opacity-50 cursor-not-allowed" : ""
    }`;
    btnHora.disabled = !fechaModalSeleccionada;
  } else {
    btnFecha.className =
      "flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-slate-600 hover:text-slate-900";
    btnHora.className =
      "flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors bg-white text-blue-700 shadow-sm";
    btnHora.disabled = false;
  }
}

function mostrarVistaFecha() {
  const contenido = document.getElementById("modal-contenido");
  contenido.innerHTML = `
        <div class="space-y-4">
            <!-- Navegación del mes -->
            <div class="flex items-center justify-between">
                <button id="btn-mes-anterior" class="p-2 hover:bg-slate-100 rounded-lg cursor-pointer">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h4 id="titulo-mes-modal" class="font-semibold font-heading"></h4>
                <button id="btn-mes-siguiente" class="p-2 hover:bg-slate-100 rounded-lg cursor-pointer">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- Días de la semana -->
            <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-slate-600">
                ${DIAS_SEMANA_CORTO.map(
                  (dia) => `<div class="p-2">${dia}</div>`
                ).join("")}
            </div>

            <!-- Calendario -->
            <div id="calendario-modal" class="grid grid-cols-7 gap-1">
                <!-- Los días se generarán con JavaScript -->
            </div>
        </div>
    `;

  actualizarCalendarioModal();
  configurarNavegacionModal();
}

function mostrarVistaHora() {
  const contenido = document.getElementById("modal-contenido");
  const fechaFormateada = formatearFechaLarga(fechaModalSeleccionada);

  contenido.innerHTML = `
        <div class="space-y-4">
            <h3 class="font-semibold font-heading text-center">${fechaFormateada}</h3>
            <div id="horas-modal" class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto">
                <!-- Las horas se generarán con JavaScript -->
            </div>
        </div>
    `;

  generarHorasModal();
}

function actualizarCalendarioModal() {
  const titulo = document.getElementById("titulo-mes-modal");
  const nombreMes = MESES[mesModalActual.getMonth()];
  const capitalizado = nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1);
  titulo.textContent = `${capitalizado} ${mesModalActual.getFullYear()}`;

  generarDiasModal();
}

function generarDiasModal() {
  const container = document.getElementById("calendario-modal");
  container.innerHTML = "";

  const primerDia = new Date(
    mesModalActual.getFullYear(),
    mesModalActual.getMonth(),
    1
  );
  const ultimoDia = new Date(
    mesModalActual.getFullYear(),
    mesModalActual.getMonth() + 1,
    0
  );
  const hoy = new Date();

  // Días vacíos al inicio
  const diaSemanaPrimero = primerDia.getDay();
  for (let i = 0; i < diaSemanaPrimero; i++) {
    const diaVacio = document.createElement("div");
    diaVacio.className = "p-2";
    container.appendChild(diaVacio);
  }

  // Días del mes
  for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
    const fecha = new Date(
      mesModalActual.getFullYear(),
      mesModalActual.getMonth(),
      dia
    );
    const esHoy = esMismoDia(fecha, hoy);
    const esPasado = fecha < hoy;
    const esDomingoModal = esDomingo(fecha);
    const estaSeleccionado =
      fechaModalSeleccionada && esMismoDia(fecha, fechaModalSeleccionada);
    const estaDeshabilitado = esPasado || esHoy || esDomingoModal;

    const diaElement = document.createElement("button");
    diaElement.className = `p-2 text-sm rounded-lg transition-colors ${
      estaDeshabilitado
        ? "text-slate-300 cursor-not-allowed"
        : estaSeleccionado
        ? "bg-blue-600 text-white"
        : "hover:bg-slate-100 cursor-pointer"
    }`;
    diaElement.textContent = dia;
    diaElement.disabled = estaDeshabilitado;

    if (!estaDeshabilitado) {
      diaElement.addEventListener("click", () => seleccionarFechaModal(fecha));
    }

    container.appendChild(diaElement);
  }
}

function generarHorasModal() {
  const container = document.getElementById("horas-modal");
  container.innerHTML = "";

  const fechaClave = claveISOFecha(fechaModalSeleccionada);

  HORAS.forEach((hora) => {
    const estaOcupado = eventosOcupados.some(
      (evento) => evento.fecha === fechaClave && evento.hora === hora
    );
    const estaSeleccionado = horaModalSeleccionada === hora;

    const horaElement = document.createElement("button");
    horaElement.className = `p-3 text-sm rounded-lg border transition-colors ${
      estaOcupado
        ? "bg-slate-100 text-slate-400 cursor-not-allowed"
        : estaSeleccionado
        ? "bg-blue-600 text-white border-blue-600"
        : "bg-white hover:bg-slate-50 border-slate-200 cursor-pointer"
    }`;
    horaElement.textContent = hora;
    horaElement.disabled = estaOcupado;

    if (!estaOcupado) {
      horaElement.addEventListener("click", () => seleccionarHoraModal(hora));
    }

    container.appendChild(horaElement);
  });
}

function configurarNavegacionModal() {
  const btnAnterior = document.getElementById("btn-mes-anterior");
  const btnSiguiente = document.getElementById("btn-mes-siguiente");
  const hoy = new Date();
  const limiteSuperior = sumarMeses(hoy, 2);

  btnAnterior.addEventListener("click", () => {
    const nuevoMes = sumarMeses(mesModalActual, -1);
    const limiteInferior = new Date(hoy.getFullYear(), hoy.getMonth(), 1);

    if (nuevoMes >= limiteInferior) {
      mesModalActual = nuevoMes;
      actualizarCalendarioModal();
    }
  });

  btnSiguiente.addEventListener("click", () => {
    const nuevoMes = sumarMeses(mesModalActual, 1);
    const limiteSup = new Date(
      limiteSuperior.getFullYear(),
      limiteSuperior.getMonth(),
      1
    );

    if (nuevoMes <= limiteSup) {
      mesModalActual = nuevoMes;
      actualizarCalendarioModal();
    }
  });
}

function seleccionarFechaModal(fecha) {
  fechaModalSeleccionada = fecha;
  actualizarToggleModal();
  cambiarVistaModal("hora");
}

function seleccionarHoraModal(hora) {
  horaModalSeleccionada = hora;

  if (fechaModalSeleccionada) {
    // Validar que no haya conflicto
    const fechaClave = claveISOFecha(fechaModalSeleccionada);
    const hayConflicto = eventosOcupados.some(
      (evento) => evento.fecha === fechaClave && evento.hora === hora
    );

    if (!hayConflicto) {
      // Crear y enviar formulario dinámico para actualizar cita
      const form = document.createElement("form");
      form.method = "POST";
      form.action = `${ruta}/agenda`; // Cambia esta URL según tu backend

      // Campo especialidad
      const inputEspecialidad = document.createElement("input");
      inputEspecialidad.type = "hidden";
      inputEspecialidad.name = "especialidad";
      inputEspecialidad.value = datosCita.especialidad;
      form.appendChild(inputEspecialidad);

      // Campo especialista
      const inputEspecialista = document.createElement("input");
      inputEspecialista.type = "hidden";
      inputEspecialista.name = "especialista";
      inputEspecialista.value = datosCita.especialista;
      form.appendChild(inputEspecialista);

      // Campo id_especialista
      const inputIdEspecialista = document.createElement("input");
      inputIdEspecialista.type = "hidden";
      inputIdEspecialista.name = "id_especialista";
      inputIdEspecialista.value = datosCita.id_especialista; // Asegúrate de que este campo esté definido
      form.appendChild(inputIdEspecialista);

      // Campo fecha
      const inputFecha = document.createElement("input");
      inputFecha.type = "hidden";
      inputFecha.name = "fecha";
      inputFecha.value = fechaClave;
      form.appendChild(inputFecha);

      // Campo hora
      const inputHora = document.createElement("input");
      inputHora.type = "hidden";
      inputHora.name = "hora";
      inputHora.value = hora;
      form.appendChild(inputHora);

      // Opcional: agrega otros campos que necesites

      document.body.appendChild(form);
      form.submit();

      cerrarModal();
    }
  }
}
