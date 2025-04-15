/**
 * Nepali Date Picker
 * A lightweight datepicker component for Nepali Calendar (BS)
 */
class NepaliDatePicker {
    constructor(options) {
        this.options = {
            inputSelector: null,
            dateFormat: 'YYYY-MM-DD',
            containerClass: 'nepali-datepicker-container',
            language: 'ne',
            closeOnSelect: true,
            ...options
        };

        this.currentBsYear = 2080; // Default - should be updated with actual year
        this.currentBsMonth = 12;  // Default - should be updated with actual month
        this.selectedDate = null;
        this.isOpen = false;
        this.container = null;
        this.targetInput = null;

        this.monthNames = {
            ne: [
                'बैशाख', 'जेठ', 'असार', 'श्रावण', 'भदौ', 'असोज',
                'कार्तिक', 'मंसिर', 'पुष', 'माघ', 'फागुन', 'चैत्र'
            ],
            en: [
                'Baishakh', 'Jestha', 'Ashadh', 'Shrawan', 'Bhadra', 'Ashwin',
                'Kartik', 'Mangsir', 'Poush', 'Magh', 'Falgun', 'Chaitra'
            ]
        };

        this.dayNames = {
            ne: ['आइत', 'सोम', 'मंगल', 'बुध', 'बिही', 'शुक्र', 'शनि'],
            en: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
        };

        this.init();
    }

    init() {
        // Find target input
        if (typeof this.options.inputSelector === 'string') {
            this.targetInput = document.querySelector(this.options.inputSelector);
        } else if (this.options.inputSelector instanceof HTMLElement) {
            this.targetInput = this.options.inputSelector;
        }

        if (!this.targetInput) {
            console.error('Nepali DatePicker: No valid input element found');
            return;
        }

        // Make the input read-only to prevent direct editing
        this.targetInput.setAttribute('readonly', 'readonly');
        this.targetInput.classList.add('nepali-datepicker-input');

        // Create calendar container
        this.createContainer();

        // Add event listeners
        this.addEventListeners();

        // Try to initialize with current Nepali date
        this.fetchCurrentNepaliDate();
    }

    fetchCurrentNepaliDate() {
        // In a real implementation, this would call your server API to get current Nepali date
        // For now, we'll use our default values set in constructor
        
        // Example of how you might implement this with fetch:
        /*
        fetch('/api/current-nepali-date')
            .then(response => response.json())
            .then(data => {
                this.currentBsYear = data.year;
                this.currentBsMonth = data.month;
                this.renderCalendar();
            })
            .catch(error => console.error('Error fetching Nepali date:', error));
        */
        
        // For demonstration, we'll use preset values
        this.renderCalendar();
    }

    createContainer() {
        // Create container element
        this.container = document.createElement('div');
        this.container.className = this.options.containerClass;
        this.container.style.display = 'none';
        
        // Position the calendar container below the input
        const inputRect = this.targetInput.getBoundingClientRect();
        this.container.style.position = 'absolute';
        this.container.style.zIndex = '1000';
        
        // Append to body instead of directly after input for better positioning
        document.body.appendChild(this.container);
        
        // Initial positioning (will be updated when opened)
        this.updateContainerPosition();
    }
    
    updateContainerPosition() {
        if (!this.container) return;
        
        const inputRect = this.targetInput.getBoundingClientRect();
        this.container.style.top = (inputRect.bottom + window.scrollY) + 'px';
        this.container.style.left = (inputRect.left + window.scrollX) + 'px';
        this.container.style.width = inputRect.width + 'px';
    }

    addEventListeners() {
        // Open calendar when clicking on the input
        this.targetInput.addEventListener('click', () => {
            this.toggle();
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (this.isOpen && 
                e.target !== this.targetInput && 
                !this.container.contains(e.target)) {
                this.close();
            }
        });

        // Update position on scroll or resize
        window.addEventListener('scroll', () => this.updateContainerPosition());
        window.addEventListener('resize', () => this.updateContainerPosition());
    }

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        if (!this.isOpen) {
            this.updateContainerPosition(); // Make sure positioning is correct
            this.container.style.display = 'block';
            this.renderCalendar();
            this.isOpen = true;
        }
    }

    close() {
        if (this.isOpen) {
            this.container.style.display = 'none';
            this.isOpen = false;
        }
    }

    renderCalendar() {
        if (!this.container) return;

        // Get calendar data for current month
        this.fetchCalendarData(this.currentBsYear, this.currentBsMonth)
            .then(calendarData => {
                // Create calendar HTML
                let html = this.generateCalendarHeader(calendarData);
                html += this.generateCalendarBody(calendarData);
                
                this.container.innerHTML = html;
                
                // Add event listeners to the new elements
                this.addCalendarEventListeners();
            })
            .catch(error => {
                console.error('Error rendering calendar:', error);
                this.container.innerHTML = '<div class="error">Failed to load calendar</div>';
            });
    }

    fetchCalendarData(year, month) {

        // Actual implementation would be:
      
        return fetch(`/api/calendar/${year}/${month}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch calendar data');
                }
                return response.json();
            });
    
    }

    generateCalendarHeader(calendarData) {
        const month = this.monthNames[this.options.language][calendarData.month - 1];
        
        return `
        <div class="nepali-datepicker-header">
            <div class="nepali-datepicker-controls">
                <button type="button" class="prev-month" title="Previous Month">&lt;</button>
                <div class="current-month-year">${month} ${calendarData.bs_year}</div>
                <button type="button" class="next-month" title="Next Month">&gt;</button>
            </div>
            <div class="nepali-datepicker-days">
                ${this.dayNames[this.options.language].map(day => 
                    `<div class="day-name">${day}</div>`
                ).join('')}
            </div>
        </div>`;
    }

    generateCalendarBody(calendarData) {
        let html = '<div class="nepali-datepicker-body">';
        let dayCount = 1;
        const totalDays = calendarData.days;
        const startDay = calendarData.startDay;
        const totalCells = Math.ceil((totalDays + startDay) / 7) * 7;
        
        // Generate dates
        for (let i = 0; i < totalCells; i++) {
            if (i % 7 === 0) html += '<div class="datepicker-row">';
            
            if (i < startDay || dayCount > totalDays) {
                // Empty cell
                html += '<div class="datepicker-day empty"></div>';
            } else {
                // Day cell
                const isToday = dayCount === calendarData.today;
                const isSelected = this.selectedDate && 
                                  this.selectedDate.year === calendarData.bs_year && 
                                  this.selectedDate.month === calendarData.month && 
                                  this.selectedDate.day === dayCount;
                const englishDate = calendarData.english_dates[dayCount] || '';
                
                html += `
                <div class="datepicker-day ${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''}" 
                     data-date="${dayCount}" 
                     data-year="${calendarData.bs_year}" 
                     data-month="${calendarData.month}">
                    <div class="nepali-date">${dayCount}</div>
                    <div class="english-date">${englishDate}</div>
                </div>`;
                
                dayCount++;
            }
            
            if (i % 7 === 6) html += '</div>';
        }
        
        html += '</div>';
        return html;
    }

    addCalendarEventListeners() {
        // Previous month button
        const prevBtn = this.container.querySelector('.prev-month');
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                this.prevMonth();
            });
        }
        
        // Next month button
        const nextBtn = this.container.querySelector('.next-month');
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                this.nextMonth();
            });
        }
        
        // Date selection
        const dayElements = this.container.querySelectorAll('.datepicker-day:not(.empty)');
        dayElements.forEach(dayEl => {
            dayEl.addEventListener('click', () => {
                const day = parseInt(dayEl.dataset.date);
                const month = parseInt(dayEl.dataset.month);
                const year = parseInt(dayEl.dataset.year);
                
                this.selectDate(year, month, day);
            });
        });
    }

    selectDate(year, month, day) {
        this.selectedDate = { year, month, day };
        
        // Update the input value
        if (this.targetInput) {
            const formattedDate = this.formatDate(year, month, day);
            this.targetInput.value = formattedDate;
            
            // Dispatch change event for any linked form validation
            const event = new Event('change', { bubbles: true });
            this.targetInput.dispatchEvent(event);
        }
        
        // Close datepicker if configured to do so
        if (this.options.closeOnSelect) {
            this.close();
        } else {
            // Just re-render to show the selection
            this.renderCalendar();
        }
    }

    formatDate(year, month, day) {
        // Use the specified date format
        let format = this.options.dateFormat;
        
        // Replace format tokens
        format = format.replace('YYYY', year);
        format = format.replace('MM', month.toString().padStart(2, '0'));
        format = format.replace('DD', day.toString().padStart(2, '0'));
        
        return format;
    }

    prevMonth() {
        this.currentBsMonth--;
        if (this.currentBsMonth < 1) {
            this.currentBsMonth = 12;
            this.currentBsYear--;
        }
        this.renderCalendar();
    }

    nextMonth() {
        this.currentBsMonth++;
        if (this.currentBsMonth > 12) {
            this.currentBsMonth = 1;
            this.currentBsYear++;
        }
        this.renderCalendar();
    }
}