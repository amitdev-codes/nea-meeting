// nepali-datepicker.js
class NepaliDatePicker {
    constructor(inputId, options = {}) {
        this.input = document.getElementById(inputId);
        this.picker = null;
        this.currentYear = options.currentYear || 2081;
        this.currentMonth = options.currentMonth || 12;
        this.csrfToken = options.csrfToken || document.querySelector('meta[name="csrf-token"]').content;
        this.nepaliMonths = options.nepaliMonths || {};
        this.toNepaliDigits = options.toNepaliDigits || ((num) => num.toString());

        console.log('NepaliDatePicker initialized for input:', inputId);
        this.init();
    }

    init() {
        if (!this.input) {
            // console.error('Input element not found:', inputId);
            return;
        }

        this.picker = document.createElement('div');
        this.picker.className = 'nepali-datepicker';
        this.picker.style.cssText = `
        display: none;
        position: absolute !important;
        z-index: 10000 !important;
        background: #ffffff !important;
        border: 2px solid #dee2e6 !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        padding: 0.5rem !important;
        width: 280px !important;
        max-height: 320px !important;
        overflow: hidden !important;
        font-family: Arial, sans-serif !important;
    `;
    
        document.body.appendChild(this.picker);

        this.input.addEventListener('click', (e) => {
            e.preventDefault();
            // console.log('Input clicked, picker display:', this.picker.style.display);
            this.togglePicker();
        });

        document.addEventListener('click', (e) => {
            if (!this.picker.contains(e.target) && e.target !== this.input) {
                this.picker.style.display = 'none';
                // console.log('Picker closed (outside click)');
            }
        });
    }

    togglePicker() {
        if (this.picker.style.display === 'none' || this.picker.style.display === '') {
            this.picker.style.display = 'block';
            this.picker.style.visibility = 'visible';
            // console.log('Picker toggled to block, rendering for', this.currentYear, this.currentMonth);
            this.renderPicker();
            this.positionPicker();
        } else {
            this.picker.style.display = 'none';
            console.log('Picker toggled to none');
        }
    }

    positionPicker() {
        const rect = this.input.getBoundingClientRect();
        this.picker.style.top = `${rect.bottom + window.scrollY}px`;
        this.picker.style.left = `${rect.left + window.scrollX}px`;
        // console.log('Picker positioned at:', this.picker.style.top, this.picker.style.left);
    }

    renderPicker() {
        // console.log('Fetching calendar data for', this.currentYear, this.currentMonth);
        fetch(`/calendar/get-calendar-data/${this.currentYear}/${this.currentMonth}`, {
            headers: {
                'X-CSRF-TOKEN': this.csrfToken
            }
        })
        .then(response => {
            if (!response.ok) throw new Error(`Fetch failed with status: ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            const startDate = new Date(data.start_date || '2025-03-14');
            const daysInMonth = data.days || 30;
            const startDay = startDate.getDay();
            const today = new Date();
            const isCurrentMonth = (startDate.getMonth() === today.getMonth() && startDate.getFullYear() === today.getFullYear());

            const html = `
            <div class="datepicker-header" style="display: flex !important; justify-content: space-between !important; align-items: center !important; padding: 0.5rem !important; background: #f8f9fa !important; border-bottom: 1px solid #dee2e6 !important;">
                <select id="yearSelect">${this.generateYearOptions()}</select>
                <select id="monthSelect">${this.generateMonthOptions()}</select>
            </div>
            <div class="datepicker-grid" style="display: grid !important; grid-template-columns: repeat(7, 1fr) !important; gap: 2px !important; padding: 0.5rem !important; background: #f0f0f0 !important; width: 100% !important;">
                ${['आइत', 'सोम', 'मंगल', 'बुध', 'बिही', 'शुक्र', 'शनि'].map(day => `<div class="datepicker-day" style="text-align: center !important; padding: 0.35rem !important; font-size: 0.75rem !important; background: #fff !important;">${day}</div>`).join('')}
                ${this.renderDays(startDay, daysInMonth, isCurrentMonth, startDate)}
            </div>
        `;
            this.picker.innerHTML = html;
            console.log('Picker HTML length:', html.length);

            this.picker.querySelector('#yearSelect').addEventListener('change', (e) => {
                this.currentYear = parseInt(e.target.value);
                this.renderPicker();
            });
            this.picker.querySelector('#monthSelect').addEventListener('change', (e) => {
                this.currentMonth = parseInt(e.target.value);
                this.renderPicker();
            });

            this.picker.querySelectorAll('.datepicker-day:not(.disabled)').forEach(day => {
                day.addEventListener('click', () => {
                    const selectedDay = day.dataset.day.padStart(2, '0'); // Ensure 2 digits (e.g., "01")
                    const selectedMonth = String(this.currentMonth).padStart(2, '0'); // Ensure 2 digits
                    this.input.value = `${this.currentYear}-${selectedMonth}-${selectedDay}`;
                    this.picker.style.display = 'none';
                    console.log('Date selected:', this.input.value);
                });
            });
        })
        .catch(error => {
            console.error('Error in renderPicker:', error);
            this.picker.innerHTML = '<div class="error">Failed to load calendar data</div>';
        });
    }

    generateYearOptions() {
        let options = '';
        for (let y = this.currentYear - 25; y <= this.currentYear + 5; y++) {
            options += `<option value="${y}" ${y === this.currentYear ? 'selected' : ''}>${this.toNepaliDigits(y)}</option>`;
        }
        return options;
    }

    generateMonthOptions() {
        let options = '';
        for (let m = 1; m <= 12; m++) {
            options += `<option value="${m}" ${m === this.currentMonth ? 'selected' : ''}>${this.nepaliMonths[m]}</option>`;
        }
        return options;
    }

    renderDays(startDay, daysInMonth, isCurrentMonth, startDate) {
        // console.log('Rendering days with:', { startDay, daysInMonth, isCurrentMonth, startDate });
        let html = '';
        const today = new Date();

        for (let i = 0; i < startDay; i++) {
            html += '<div class="datepicker-day disabled" style="text-align: center !important; padding: 0.5rem !important; font-size: 0.9rem !important; color: #adb5bd !important; background: #f1f1f1 !important;"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateForDay = new Date(startDate);
            dateForDay.setDate(startDate.getDate() + day - 1);
            const isToday = isCurrentMonth && dateForDay.toDateString() === today.toDateString();
            const isSaturday = (startDay + day - 1) % 7 === 6;
            const isSunday = (startDay + day - 1) % 7 === 0;

            html += `
            <div class="datepicker-day ${isToday ? 'today' : ''} ${isSaturday ? 'saturday' : ''} ${isSunday ? 'sunday' : ''}" data-day="${day}" style="text-align: center !important; padding: 0.35rem !important; font-size: 0.75rem !important; cursor: pointer !important; border-radius: 4px !important; background: ${isToday ? 'rgba(255, 99, 71, 0.2)' : '#fff'} !important; color: ${isSaturday || isSunday ? '#dc3545' : '#000'} !important; ${isToday ? 'font-weight: 600 !important;' : ''}">
                ${this.toNepaliDigits(day)}
            </div>
        `;
        }

        console.log('Days HTML length:', html.length);
        return html;
    }
}

window.NepaliDatePicker = NepaliDatePicker;