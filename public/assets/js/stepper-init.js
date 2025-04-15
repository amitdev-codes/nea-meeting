// Place this in a separate JavaScript file, e.g., 'stepper-init.js'
function initializeStepper() {
    if (typeof Stepper !== 'undefined') {
        const stepperElement = document.querySelector('.bs-stepper');
        if (stepperElement) {
            const stepper = new Stepper(stepperElement);

            // Additional stepper functionality
            const stepTriggers = document.querySelectorAll('.step-trigger');
            stepTriggers.forEach(function(trigger, index) {
                trigger.addEventListener('click', function(event) {
                    event.preventDefault();
                    stepper.to(index + 1);
                });
            });

            console.log('Stepper initialized successfully');
        } else {
            console.warn('Stepper element not found');
        }
    } else {
        console.warn('Stepper library not loaded');
    }
}

// Function to ensure both DOM and Stepper script are loaded
function ensureStepperInitialization() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeStepper);
    } else {
        initializeStepper();
    }
}

// Try to initialize immediately
ensureStepperInitialization();

// Also try after a short delay to ensure script is loaded
setTimeout(ensureStepperInitialization, 1000);
