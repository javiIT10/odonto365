class AgendaApp {
  constructor() {
    // Establece el inicio del rango de 4 días para incluir el 25 de julio de 2025
    // Si hoy es 29 de julio de 2025, y queremos mostrar 22-25, necesitamos ajustar el inicio.
    // Para que el 25 de julio sea el 4to día (index 3), el primer día (index 0) debe ser el 22 de julio.
    this.currentDateRangeStart = new Date(); // 22 de julio de 2025
    this.today = new Date(); // Fecha actual para marcar "Hoy"

    this.selectedAppointment = {
      date: new Date(2025, 7, 4, 13, 0), // 25 de julio de 2025, 16:00
      specialty: "Cardiología",
      specialist: "Dr. María González",
      duration: "45 minutos",
      price: "$150.00",
    };

    this.unavailableSlots = [
      new Date(2025, 7, 5, 8, 0), // Jueves 24, 11:00
      new Date(2025, 7, 5, 10, 0), // Jueves 24, 14:00
      new Date(2025, 7, 5, 13, 0), // Jueves 24, 17:00
      new Date(2025, 7, 6, 9, 0), // Viernes 25, 12:00
      new Date(2025, 7, 3, 14, 0), // Viernes 25, 13:00
      new Date(2025, 7, 3, 18, 0), // Viernes 25, 17:00
    ];

    this.timeSlots = [];
    for (let hour = 8; hour <= 18; hour++) {
      this.timeSlots.push(`${hour.toString().padStart(2, "0")}:00`);
    }

    this.init();
  }

  init() {
    this.renderCalendar(); // Renderiza días y horarios
    this.renderMonthNavigation(); // Actualiza el mes en el navegador
    this.updateAppointmentSummary(); // Muestra el resumen al cargar
    this.bindEvents();
  }

  bindEvents() {
    document.getElementById("prevDays").addEventListener("click", () => {
      this.currentDateRangeStart.setDate(
        this.currentDateRangeStart.getDate() - 4
      );
      this.renderCalendar();
      this.renderMonthNavigation();
    });

    document.getElementById("nextDays").addEventListener("click", () => {
      this.currentDateRangeStart.setDate(
        this.currentDateRangeStart.getDate() + 4
      );
      this.renderCalendar();
      this.renderMonthNavigation();
    });
  }

  renderMonthNavigation() {
    const monthDisplay = document.getElementById("monthDisplay");
    const months = [
      "Enero",
      "Febrero",
      "Marzo",
      "Abril",
      "Mayo",
      "Junio",
      "Julio",
      "Agosto",
      "Septiembre",
      "Octubre",
      "Noviembre",
      "Diciembre",
    ];
    monthDisplay.textContent = `${
      months[this.currentDateRangeStart.getMonth()]
    } ${this.currentDateRangeStart.getFullYear()}`;
  }

  isSameDay(date1, date2) {
    return (
      date1.getDate() === date2.getDate() &&
      date1.getMonth() === date2.getMonth() &&
      date1.getFullYear() === date2.getFullYear()
    );
  }

  isSameDateTime(date1, date2) {
    return (
      this.isSameDay(date1, date2) &&
      date1.getHours() === date2.getHours() &&
      date1.getMinutes() === date2.getMinutes()
    );
  }

  renderCalendar() {
    const daysContainer = document.getElementById("daysContainer");
    const timeSlotsContainer = document.getElementById("timeSlotsContainer");
    daysContainer.innerHTML = "";
    timeSlotsContainer.innerHTML = "";

    const daysOfWeek = ["dom", "lun", "mar", "mié", "jue", "vie", "sáb"];
    const monthsShort = [
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

    const currentDaysInView = [];

    // Renderizar los encabezados de los días
    for (let i = 0; i < 4; i++) {
      const currentDayDate = new Date(this.currentDateRangeStart);
      currentDayDate.setDate(this.currentDateRangeStart.getDate() + i);
      currentDaysInView.push(currentDayDate); // Guardar la fecha para usarla en los slots

      const dayElement = document.createElement("div");

      let bgColor = "bg-transparent border border-borde";
      let textColor = "text-boton-suave-texto";

      if (this.isSameDay(currentDayDate, this.today)) {
        bgColor = "bg-[#10b981]";
        textColor = "text-boton-primario-texto";
      }

      if (this.isSameDay(currentDayDate, this.selectedAppointment.date)) {
        bgColor = "bg-primario";
        textColor = "text-boton-primario-texto";
      }

      dayElement.className = `${bgColor} ${textColor} p-4 rounded-xl text-center flex flex-col items-center justify-center`;
      dayElement.innerHTML = `
                        <span class="text-sm md:text-base lg:text-lg font-medium">${
                          daysOfWeek[currentDayDate.getDay()]
                        }</span>
                        <span class="text-xl md:text-2xl lg:text-3xl font-semibold">${currentDayDate.getDate()}</span>
                        <span class="text-sm md:text-base lg:text-lg font-medium">${
                          monthsShort[currentDayDate.getMonth()]
                        }</span>
                    `;

      daysContainer.appendChild(dayElement);
    }

    // Renderizar los slots de tiempo
    for (let timeIndex = 0; timeIndex < this.timeSlots.length; timeIndex++) {
      const timeString = this.timeSlots[timeIndex];
      const [hour, minute] = timeString.split(":").map(Number);

      for (let dayIndex = 0; dayIndex < 4; dayIndex++) {
        const slotElement = document.createElement("div");
        const slotDateTime = new Date(currentDaysInView[dayIndex]);
        slotDateTime.setHours(hour, minute, 0, 0);

        let className =
          "time-slot-button pointer-events-none cursor-not-allowed";

        // Verificar si es un slot no disponible
        const isUnavailable = this.unavailableSlots.some((unavailableDate) =>
          this.isSameDateTime(slotDateTime, unavailableDate)
        );

        if (isUnavailable) {
          className =
            "time-slot-button text-boton-deshabilitado-texto bg-boton-deshabilitado-bg cursor-not-allowed";
        }

        // Verificar si es el slot seleccionado
        if (this.isSameDateTime(slotDateTime, this.selectedAppointment.date)) {
          className =
            "time-slot-button text-boton-primario-texto bg-boton-primario-bg hover:bg-boton-primario-hover-bg pointer-events-none cursor-not-allowed";
        }

        slotElement.className = className;
        slotElement.innerHTML = `<div class="font-medium">${timeString}</div>`;

        timeSlotsContainer.appendChild(slotElement);
      }
    }
  }

  updateAppointmentSummary() {
    const monthsLong = [
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
    const daysLong = [
      "domingo",
      "lunes",
      "martes",
      "miércoles",
      "jueves",
      "viernes",
      "sábado",
    ];

    const selectedDate = this.selectedAppointment.date;
    const dateStr = `${
      daysLong[selectedDate.getDay()]
    }, ${selectedDate.getDate()} de ${
      monthsLong[selectedDate.getMonth()]
    } de ${selectedDate.getFullYear()}`;
    const timeStr = `${selectedDate
      .getHours()
      .toString()
      .padStart(2, "0")}:${selectedDate
      .getMinutes()
      .toString()
      .padStart(2, "0")}`;

    document.getElementById("summaryDate").textContent = dateStr;
    document.getElementById("summaryTime").textContent = timeStr;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  new AgendaApp();
});
