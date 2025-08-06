// Specialists data
const specialists = [
  {
    id: 1,
    name: "Dr. María González",
    specialty: "Ortodoncia",
    image: "/placeholder.svg?height=400&width=400",
    description:
      "Especialista en ortodoncia con más de 15 años de experiencia en el diagnóstico y tratamiento de maloclusiones dentales. Graduada de la Universidad Nacional con especialización en ortodoncia invisible y brackets estéticos. Experta en tratamientos con Invisalign y sistemas de ortodoncia moderna.",
    certifications: [
      "Especialización en Ortodoncia - Universidad Nacional",
      "Certificación Invisalign Diamond Provider",
      "Diplomado en Ortodoncia Lingual",
      "Certificación en Ortodoncia Interceptiva",
      "Miembro de la Sociedad de Ortodoncia",
    ],
  },
  {
    id: 2,
    name: "Dr. Carlos Rodríguez",
    specialty: "Ortodoncia",
    image: "/placeholder.svg?height=400&width=400",
    description:
      "Ortodoncista certificado con 12 años de experiencia en corrección dental. Especializado en ortodoncia interceptiva y correctiva para niños y adultos. Graduado de la Universidad Central con múltiples certificaciones internacionales en técnicas de ortodoncia avanzada.",
    certifications: [
      "Especialización en Ortodoncia - Universidad Central",
      "Certificación en Brackets Autoligables",
      "Diplomado en Ortodoncia Pediátrica",
      "Certificación Damon System",
      "Miembro del Colegio de Ortodoncistas",
    ],
  },
  {
    id: 3,
    name: "Dra. Ana Martínez",
    specialty: "Ortodoncia",
    image: "/placeholder.svg?height=400&width=400",
    description:
      "Ortodoncista con enfoque en tratamientos estéticos y funcionales. 10 años de experiencia en ortodoncia lingual y sistemas de brackets transparentes. Especialista en casos complejos de maloclusión y rehabilitación oral integral.",
    certifications: [
      "Especialización en Ortodoncia - Universidad Javeriana",
      "Certificación en Ortodoncia Lingual",
      "Diplomado en Brackets Estéticos",
      "Certificación Clear Aligner",
      "Especialista en Casos Complejos",
    ],
  },
];

let currentSpecialist = 0;
let selectedDate = null;
let selectedTime = null;
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let activeTab = "date"; // 'date' or 'time'

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

const timeSlots = [
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
  "18:00",
];

// Helper to get elements by suffix
function getElement(id, suffix) {
  return document.getElementById(`${id}${suffix}`);
}

// Generate specialist navigation buttons
function generateSpecialistNav() {
  const navContainer = getElement("specialists-nav", "");
  navContainer.innerHTML = "";

  specialists.forEach((specialist, index) => {
    const button = document.createElement("button");
    button.className = `btn cursor-pointer whitespace-nowrap ${
      index === currentSpecialist ? "primario" : "suave border border-borde"
    }`;
    button.textContent = specialist.name;
    button.onclick = () => selectSpecialist(index);
    navContainer.appendChild(button);
  });
}

// Generate specialist details for mobile
function generateSpecialistDetailsMobile() {
  const detailsContainer = getElement("specialist-details", "-mobile");
  const specialist = specialists[currentSpecialist];

  detailsContainer.innerHTML = `
                <div class="mb-6">
                    <div class="aspect-square bg-gradient-to-b from-fondo-alternativo to-fondo-terciario relative overflow-hidden rounded-3xl">
                        <img src="${specialist.image}" alt="${specialist.name}" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="px-4">
                    <h3>${specialist.name}</h3>
                    <small class="mb-4">${specialist.specialty}</small>
                    <p>${specialist.description}</p>
                </div>
            `;
  getElement(
    "appointment-title",
    "-mobile"
  ).textContent = `Agendar Cita con ${specialist.name}`;
}

// Generate specialist details for desktop
function generateSpecialistDetailsDesktop() {
  const detailsContainer = getElement("specialist-details", "-desktop");
  const specialist = specialists[currentSpecialist];

  detailsContainer.innerHTML = `
                <div>
                    <h3>${specialist.name}</h3>
                    <small class="mb-4 inline-block">${specialist.specialty}</small>
                    <p>${specialist.description}</p>
                </div>
                <div>
                    <div class="aspect-square bg-gradient-to-b from-fondo-alternativo to-fondo-terciario relative overflow-hidden rounded-3xl">
                        <img src="${specialist.image}" alt="${specialist.name}" class="w-full h-full object-cover">
                    </div>
                </div>
            `;
  getElement(
    "appointment-title",
    "-desktop"
  ).textContent = `Agendar Cita con ${specialist.name}`;
}

// Generate certifications
function generateCertifications(suffix = "") {
  const certificationsContainer = getElement("certifications-list", suffix);
  const specialist = specialists[currentSpecialist];

  certificationsContainer.innerHTML = "";

  specialist.certifications.forEach((certification) => {
    const certDiv = document.createElement("div");
    certDiv.className = "flex items-start gap-3";
    certDiv.innerHTML = `
                    <div class="w-2 h-2 bg-primario rounded-full mt-2 flex-shrink-0"></div>
                    <p>${certification}</p>
                `;
    certificationsContainer.appendChild(certDiv);
  });
}

// Render calendar
function renderCalendar(suffix = "") {
  const calendarGrid = getElement("calendar-grid", suffix);
  const currentMonthYearSpan = getElement("current-month-year", suffix);
  calendarGrid.innerHTML = "";

  const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
  const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
  const firstDayOfWeek = firstDayOfMonth.getDay(); // 0 for Sunday, 1 for Monday...
  const today = new Date();

  currentMonthYearSpan.textContent = `${months[currentMonth]} ${currentYear}`;

  // Add empty cells for days before the first day of the month
  for (let i = 0; i < firstDayOfWeek; i++) {
    const emptyCell = document.createElement("div");
    emptyCell.className = "calendar-day disabled"; // Visually disabled
    calendarGrid.appendChild(emptyCell);
  }

  // Add days of the month
  for (let day = 1; day <= daysInMonth; day++) {
    const date = new Date(currentYear, currentMonth, day);
    const isToday =
      date.getDate() === today.getDate() &&
      date.getMonth() === today.getMonth() &&
      date.getFullYear() === today.getFullYear();
    const isSelected =
      selectedDate &&
      date.getDate() === selectedDate.getDate() &&
      date.getMonth() === selectedDate.getMonth() &&
      date.getFullYear() === selectedDate.getFullYear();
    const isSunday = date.getDay() === 0; // Sunday

    const dayDiv = document.createElement("div");
    dayDiv.textContent = day;
    dayDiv.className = `calendar-day ${isSunday ? "disabled" : "selectable"} ${
      isSelected ? "selected" : ""
    } text-sm md:text-base lg:text-lg font-body`;

    if (!isSunday) {
      dayDiv.onclick = () => selectDate(date, suffix);
    }
    calendarGrid.appendChild(dayDiv);
  }
}

// Render time slots
function renderTimeSlots(suffix = "") {
  const timeSlotsGrid = getElement("time-slots-grid", suffix);
  const selectedDateDisplay = getElement("selected-date-display", suffix);
  timeSlotsGrid.innerHTML = "";

  if (selectedDate) {
    const dateOptions = {
      weekday: "long",
      day: "numeric",
      month: "long",
    };
    selectedDateDisplay.textContent = selectedDate.toLocaleDateString(
      "es-ES",
      dateOptions
    );

    timeSlots.forEach((time) => {
      const timeButton = document.createElement("button");
      timeButton.className = `time-slot-button ${
        selectedTime === time ? "selected" : ""
      } text-sm md:text-base lg:text-lg font-body`;
      timeButton.textContent = time;
      timeButton.onclick = () => selectTime(time, suffix);
      timeSlotsGrid.appendChild(timeButton);
    });
  } else {
    selectedDateDisplay.textContent =
      "Por favor, selecciona una fecha primero.";
  }
}

// Select date
function selectDate(date, suffix) {
  selectedDate = date;
  selectedTime = null; // Reset time when date changes
  renderCalendar("-mobile");
  renderCalendar("-desktop");
  renderTimeSlots("-mobile");
  renderTimeSlots("-desktop");
  switchTab("time", suffix); // Switch to time tab after date selection
  checkAppointmentComplete();
}

// Select time
function selectTime(time, suffix) {
  selectedTime = time;
  renderTimeSlots("-mobile");
  renderTimeSlots("-desktop");
  checkAppointmentComplete();
}

//border-primario text-boton-secundario-texto
//border-borde text-boton-suave-texto
// Switch tabs
function switchTab(tab, suffix) {
  activeTab = tab;
  const dateTab = getElement("tab-date", suffix);
  const timeTab = getElement("tab-time", suffix);
  const dateView = getElement("date-view", suffix);
  const timeView = getElement("time-view", suffix);

  if (tab === "date") {
    dateTab.classList.add("border-primario", "text-boton-secundario-texto");
    dateTab.classList.remove("border-borde", "text-boton-suave-texto");
    timeTab.classList.remove("border-primario", "text-boton-secundario-texto");
    timeTab.classList.add("border-borde", "text-boton-suave-texto");
    dateView.style.display = "block";
    timeView.style.display = "none";
  } else {
    timeTab.classList.add("border-primario", "text-boton-secundario-texto");
    timeTab.classList.remove("border-borde", "text-boton-suave-texto");
    dateTab.classList.remove("border-primario", "text-boton-secundario-texto");
    dateTab.classList.add("border-borde", "text-boton-suave-texto");
    dateView.style.display = "none";
    timeView.style.display = "block";
  }
}

// Check if appointment is complete
function checkAppointmentComplete() {
  if (selectedDate && selectedTime) {
    getElement("appointment-summary", "-mobile").style.display = "block";
    getElement("appointment-summary", "-desktop").style.display = "block";
    updateSummary();
  } else {
    getElement("appointment-summary", "-mobile").style.display = "none";
    getElement("appointment-summary", "-desktop").style.display = "none";
  }
}

// Update summary
function updateSummary() {
  const specialist = specialists[currentSpecialist];

  // Update specialty category in header
  getElement("specialty-category", "").textContent = specialist.specialty;

  // Update mobile summary
  getElement("summary-specialty", "-mobile").textContent = specialist.specialty;
  getElement("summary-doctor", "-mobile").textContent = specialist.name;

  // Update desktop summary
  getElement("summary-specialty", "-desktop").textContent =
    specialist.specialty;
  getElement("summary-doctor", "-desktop").textContent = specialist.name;

  if (selectedDate && selectedTime) {
    const dateOptions = {
      weekday: "long",
      day: "numeric",
      month: "long",
      year: "numeric",
    };
    const formattedDate = selectedDate.toLocaleDateString("es-ES", dateOptions);
    getElement("summary-date", "-mobile").textContent = formattedDate;
    getElement("summary-time", "-mobile").textContent = selectedTime;
    getElement("summary-date", "-desktop").textContent = formattedDate;
    getElement("summary-time", "-desktop").textContent = selectedTime;
  }
}

// Select specialist
function selectSpecialist(index) {
  currentSpecialist = index;
  selectedDate = null; // Reset selections when specialist changes
  selectedTime = null;
  generateSpecialistNav();
  generateSpecialistDetailsMobile();
  generateSpecialistDetailsDesktop();
  generateCertifications("-mobile");
  generateCertifications("-desktop");
  renderCalendar("-mobile");
  renderCalendar("-desktop");
  renderTimeSlots("-mobile");
  renderTimeSlots("-desktop");
  switchTab("date", "-mobile"); // Always start on date tab
  switchTab("date", "-desktop");
  checkAppointmentComplete();
}

// Initialize page
function init() {
  generateSpecialistNav();
  generateSpecialistDetailsMobile();
  generateSpecialistDetailsDesktop();
  generateCertifications("-mobile");
  generateCertifications("-desktop");
  renderCalendar("-mobile");
  renderCalendar("-desktop");
  renderTimeSlots("-mobile");
  renderTimeSlots("-desktop");
  switchTab("date", "-mobile");
  switchTab("date", "-desktop");
  checkAppointmentComplete(); // Hide summary initially

  // Add event listeners for month navigation
  getElement("prev-month", "-mobile").addEventListener("click", () => {
    if (currentMonth === 0) {
      currentMonth = 11;
      currentYear--;
    } else {
      currentMonth--;
    }
    renderCalendar("-mobile");
    renderCalendar("-desktop");
  });
  getElement("next-month", "-mobile").addEventListener("click", () => {
    if (currentMonth === 11) {
      currentMonth = 0;
      currentYear++;
    } else {
      currentMonth++;
    }
    renderCalendar("-mobile");
    renderCalendar("-desktop");
  });
  getElement("prev-month", "-desktop").addEventListener("click", () => {
    if (currentMonth === 0) {
      currentMonth = 11;
      currentYear--;
    } else {
      currentMonth--;
    }
    renderCalendar("-mobile");
    renderCalendar("-desktop");
  });
  getElement("next-month", "-desktop").addEventListener("click", () => {
    if (currentMonth === 11) {
      currentMonth = 0;
      currentYear++;
    } else {
      currentMonth++;
    }
    renderCalendar("-mobile");
    renderCalendar("-desktop");
  });

  // Add event listeners for tabs
  getElement("tab-date", "-mobile").addEventListener("click", () =>
    switchTab("date", "-mobile")
  );
  getElement("tab-time", "-mobile").addEventListener("click", () =>
    switchTab("time", "-mobile")
  );
  getElement("tab-date", "-desktop").addEventListener("click", () =>
    switchTab("date", "-desktop")
  );
  getElement("tab-time", "-desktop").addEventListener("click", () =>
    switchTab("time", "-desktop")
  );
}

// Run initialization when page loads
document.addEventListener("DOMContentLoaded", init);
