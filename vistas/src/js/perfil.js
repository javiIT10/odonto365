// Datos de citas
const citasHistorial = [
  {
    id: "CITA-20250120-0001",
    status: "agendada",
    especialista: "Dr. Carlos Mendoza",
    especialidad: "Cardiología",
    fecha: "2025-01-20",
    hora: "10:30",
  },
  {
    id: "CITA-20250118-0002",
    status: "completada",
    especialista: "Dra. Ana Rodríguez",
    especialidad: "Dermatología",
    fecha: "2025-01-18",
    hora: "14:00",
  },
  {
    id: "CITA-20250115-0003",
    status: "cancelada",
    especialista: "Dr. Luis Hernández",
    especialidad: "Neurología",
    fecha: "2025-01-15",
    hora: "09:15",
  },
  {
    id: "CITA-20250112-0004",
    status: "completada",
    especialista: "Dra. Patricia Silva",
    especialidad: "Ginecología",
    fecha: "2025-01-12",
    hora: "16:45",
  },
  {
    id: "CITA-20250110-0005",
    status: "agendada",
    especialista: "Dr. Roberto Vega",
    especialidad: "Oftalmología",
    fecha: "2025-01-25",
    hora: "11:00",
  },
  {
    id: "CITA-20250108-0006",
    status: "completada",
    especialista: "Dr. Miguel Torres",
    especialidad: "Traumatología",
    fecha: "2025-01-08",
    hora: "13:30",
  },
  {
    id: "CITA-20250105-0007",
    status: "cancelada",
    especialista: "Dra. Carmen López",
    especialidad: "Endocrinología",
    fecha: "2025-01-05",
    hora: "15:15",
  },
  {
    id: "CITA-20250103-0008",
    status: "completada",
    especialista: "Dr. Fernando Ruiz",
    especialidad: "Urología",
    fecha: "2025-01-03",
    hora: "09:45",
  },
  {
    id: "CITA-20241230-0009",
    status: "agendada",
    especialista: "Dra. Isabel Moreno",
    especialidad: "Psiquiatría",
    fecha: "2024-12-30",
    hora: "12:00",
  },
  {
    id: "CITA-20241228-0010",
    status: "completada",
    especialista: "Dr. Alejandro Díaz",
    especialidad: "Neumología",
    fecha: "2024-12-28",
    hora: "16:30",
  },
  {
    id: "CITA-20241225-0011",
    status: "cancelada",
    especialista: "Dra. Lucía Vargas",
    especialidad: "Reumatología",
    fecha: "2024-12-25",
    hora: "10:15",
  },
  {
    id: "CITA-20241222-0012",
    status: "completada",
    especialista: "Dr. Raúl Jiménez",
    especialidad: "Gastroenterología",
    fecha: "2024-12-22",
    hora: "14:45",
  },
];

const rutaPrincipal = "http://localhost/sistema-citas-odonto365/";
let paginaActual = 1;
const citasPorPagina = 5;
let paymentBrick = null;
const MercadoPago = window.MercadoPago; // Declare MercadoPago variable
const lucide = window.lucide; // Declare lucide variable

// Inicializar la página
document.addEventListener("DOMContentLoaded", () => {
  lucide.createIcons();
  cargarCitas();
});

function obtenerIconoStatus(status) {
  switch (status) {
    case "agendada":
      return '<i data-lucide="alert-circle" class="h-5 w-5 text-blue-600"></i>';
    case "completada":
      return '<i data-lucide="check-circle" class="h-5 w-5 text-emerald-600"></i>';
    case "cancelada":
      return '<i data-lucide="x-circle" class="h-5 w-5 text-red-600"></i>';
    default:
      return '<i data-lucide="alert-circle" class="h-5 w-5 text-slate-400"></i>';
  }
}

function obtenerColorStatus(status) {
  switch (status) {
    case "agendada":
      return "bg-blue-100 text-blue-800 border-blue-200";
    case "completada":
      return "bg-emerald-100 text-emerald-800 border-emerald-200";
    case "cancelada":
      return "bg-red-100 text-red-800 border-red-200";
    default:
      return "bg-slate-100 text-slate-800 border-slate-200";
  }
}

function formatearFecha(fecha) {
  const fechaObj = new Date(fecha);
  return fechaObj.toLocaleDateString("es-ES", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

function cargarCitas() {
  const citasOrdenadas = [...citasHistorial].sort(
    (a, b) => new Date(b.fecha).getTime() - new Date(a.fecha).getTime()
  );
  const totalPaginas = Math.ceil(citasOrdenadas.length / citasPorPagina);
  const indiceInicio = (paginaActual - 1) * citasPorPagina;
  const indiceFin = indiceInicio + citasPorPagina;
  const citasActuales = citasOrdenadas.slice(indiceInicio, indiceFin);

  const container = document.getElementById("citasContainer");
  container.innerHTML = "";

  citasActuales.forEach((cita) => {
    const citaElement = document.createElement("div");
    citaElement.className =
      "rounded-xl border border-slate-200 bg-slate-50 p-4 hover:shadow-md transition-all duration-200";
    citaElement.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-bold text-slate-600">${cita.id}</span>
                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold border ${obtenerColorStatus(
                  cita.status
                )}">
                    ${obtenerIconoStatus(cita.status)}
                    ${
                      cita.status.charAt(0).toUpperCase() + cita.status.slice(1)
                    }
                </div>
            </div>
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-slate-900">${
                  cita.especialista
                }</h3>
                <p class="text-slate-600 font-medium">${cita.especialidad}</p>
                <div class="flex items-center gap-4 text-sm text-slate-500">
                    <div class="flex items-center gap-1">
                        <i data-lucide="calendar" class="h-4 w-4"></i>
                        <span class="capitalize">${formatearFecha(
                          cita.fecha
                        )}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <i data-lucide="clock" class="h-4 w-4"></i>
                        <span>${cita.hora}</span>
                    </div>
                </div>
            </div>
        `;
    container.appendChild(citaElement);
  });

  // Cargar paginación
  if (totalPaginas > 1) {
    cargarPaginacion(
      totalPaginas,
      citasOrdenadas.length,
      indiceInicio,
      indiceFin
    );
  }

  lucide.createIcons();
}

function cargarPaginacion(totalPaginas, totalCitas, indiceInicio, indiceFin) {
  const paginacionContainer = document.getElementById("paginacion");
  const infoPaginacionContainer = document.getElementById("infoPaginacion");

  paginacionContainer.innerHTML = `
        <button onclick="irAPaginaAnterior()" ${
          paginaActual === 1 ? "disabled" : ""
        } 
                class="flex items-center gap-1 h-9 px-3 rounded-lg border border-slate-300 hover:bg-blue-50 hover:border-blue-300 disabled:opacity-50 disabled:cursor-not-allowed bg-transparent">
            <i data-lucide="chevron-left" class="h-4 w-4"></i>
            Anterior
        </button>
        
        <div class="flex items-center gap-1">
            ${Array.from({ length: totalPaginas }, (_, i) => i + 1)
              .map(
                (pagina) => `
                <button onclick="irAPagina(${pagina})" 
                        class="h-9 w-9 rounded-lg ${
                          paginaActual === pagina
                            ? "bg-blue-700 hover:bg-blue-800 text-white"
                            : "border border-slate-300 hover:bg-blue-50 hover:border-blue-300"
                        }">
                    ${pagina}
                </button>
            `
              )
              .join("")}
        </div>
        
        <button onclick="irAPaginaSiguiente()" ${
          paginaActual === totalPaginas ? "disabled" : ""
        } 
                class="flex items-center gap-1 h-9 px-3 rounded-lg border border-slate-300 hover:bg-blue-50 hover:border-blue-300 disabled:opacity-50 disabled:cursor-not-allowed bg-transparent">
            Siguiente
            <i data-lucide="chevron-right" class="h-4 w-4"></i>
        </button>
    `;

  infoPaginacionContainer.innerHTML = `
        <p class="text-sm text-slate-500">
            Mostrando ${indiceInicio + 1} - ${Math.min(
    indiceFin,
    totalCitas
  )} de ${totalCitas} citas
        </p>
    `;

  lucide.createIcons();
}

function irAPaginaAnterior() {
  if (paginaActual > 1) {
    paginaActual--;
    cargarCitas();
  }
}

function irAPaginaSiguiente() {
  const totalPaginas = Math.ceil(citasHistorial.length / citasPorPagina);
  if (paginaActual < totalPaginas) {
    paginaActual++;
    cargarCitas();
  }
}

function irAPagina(pagina) {
  paginaActual = pagina;
  cargarCitas();
}

function cerrarSesion() {
  window.location.href = "login.php";
}

function procederAlPago() {
  document.getElementById("paymentModal").classList.remove("hidden");
  setTimeout(() => {
    initializeBricks();
  }, 100);
}

function cerrarModalPago() {
  if (paymentBrick) {
    paymentBrick.unmount();
    paymentBrick = null;
  }
  document.getElementById("paymentModal").classList.add("hidden");
  document.getElementById("loadingPayment").classList.add("hidden");
}

function initializeBricks() {
  if (MercadoPago) {
    const mp = new MercadoPago("TEST-3672e9f9-d061-4a4b-b90d-209cf016e20d", {
      locale: "es-MX",
    });

    const bricksBuilder = mp.bricks();

    const renderPaymentBrick = async () => {
      const settings = {
        initialization: {
          amount: 250,
        },
        customization: {
          paymentMethods: {
            creditCard: "all",
            debitCard: "all",
            ticket: "all",
            bankTransfer: "all",
            mercadoPago: "all",
          },
        },
        callbacks: {
          onReady: () => {
            console.log("Payment Brick ready");
          },
          onSubmit: async ({ selectedPaymentMethod, formData }) => {
            document
              .getElementById("loadingPayment")
              .classList.remove("hidden");

            try {
              const response = await fetch(
                `${rutaPrincipal}controladores/payment.controlador.php`,
                {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json",
                  },
                  body: JSON.stringify({
                    citaId: "CITA-20250125-0001",
                    especialista: "Dr. Carlos Mendoza",
                    especialidad: "Cardiología",
                    fecha: "2025-01-25",
                    hora: "10:30",
                    total: 250,
                    formData: formData,
                    paymentMethodId: selectedPaymentMethod,
                  }),
                }
              );

              if (!response.ok) {
                throw new Error("Error al procesar el pago");
              }

              const data = await response.json();

              if (data.status === "approved") {
                cerrarModalPago();
                mostrarAlerta(
                  "success",
                  "¡Cita Agendada Exitosamente!",
                  "Tu pago ha sido procesado correctamente y tu cita ha sido confirmada. Recibirás un correo de confirmación en breve."
                );
              } else if (data.status === "pending") {
                alert(
                  "Tu pago está siendo procesado. Te notificaremos cuando se confirme."
                );
                cerrarModalPago();
              } else {
                alert(
                  "El pago no pudo ser procesado. Por favor, intenta nuevamente."
                );
              }
            } catch (error) {
              console.error("Error processing payment:", error);
              alert(
                "Error al procesar el pago. Por favor, intenta nuevamente."
              );
            } finally {
              document.getElementById("loadingPayment").classList.add("hidden");
            }
          },
          onError: (error) => {
            console.error("Payment Brick error:", error);
            alert(
              "Error en el formulario de pago. Por favor, verifica los datos."
            );
          },
        },
      };

      try {
        const paymentBrickController = await bricksBuilder.create(
          "payment",
          "payment-brick-container",
          settings
        );
        paymentBrick = paymentBrickController;
      } catch (error) {
        console.error("Error creating Payment Brick:", error);
      }
    };

    renderPaymentBrick();
  }
}

function mostrarAlerta(tipo, titulo, mensaje) {
  const modal = document.getElementById("alertModal");
  const icon = document.getElementById("alertIcon");
  const titleEl = document.getElementById("alertTitle");
  const messageEl = document.getElementById("alertMessage");
  const confirmBtn = document.getElementById("alertConfirm");

  titleEl.textContent = titulo;
  messageEl.textContent = mensaje;

  if (tipo === "success") {
    icon.className =
      "w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center";
    icon.innerHTML =
      '<i data-lucide="check-circle" class="h-8 w-8 text-emerald-600"></i>';
    confirmBtn.className =
      "w-full h-11 rounded-xl text-white font-semibold transition-all duration-200 hover:scale-105 active:scale-95 bg-emerald-600 hover:bg-emerald-700";
  } else {
    icon.className =
      "w-16 h-16 bg-red-100 rounded-full flex items-center justify-center";
    icon.innerHTML =
      '<i data-lucide="x-circle" class="h-8 w-8 text-red-600"></i>';
    confirmBtn.className =
      "w-full h-11 rounded-xl text-white font-semibold transition-all duration-200 hover:scale-105 active:scale-95 bg-red-600 hover:bg-red-700";
  }

  modal.classList.remove("hidden");
  lucide.createIcons();
}

function cerrarAlerta() {
  document.getElementById("alertModal").classList.add("hidden");
}

function confirmarAlerta() {
  cerrarAlerta();
}
