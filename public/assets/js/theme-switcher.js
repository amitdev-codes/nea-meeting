document.addEventListener("DOMContentLoaded", function() {
    const themeSwitcher = document.querySelector(".dropdown-style-switcher");
    const toggleButton = document.querySelector(".layout-menu-toggle");
    const sidebar = document.getElementById("layout-menu");
    const currentTheme = localStorage.getItem("theme") || "light";

        // Add event listeners to theme switcher items
        themeSwitcher.querySelectorAll(".dropdown-item").forEach(item => {
            item.addEventListener("click", function() {
                const selectedTheme = this.getAttribute("data-theme");
                updateTheme(selectedTheme);

                // Highlight the selected item
                themeSwitcher.querySelectorAll(".dropdown-item").forEach(i => i.classList.remove("active"));
                this.classList.add("active");
            });
        });
        toggleButton.addEventListener("click", function() {
            sidebar.classList.toggle("collapsed");
        });

    function updateTheme(theme) {
        if (theme === "dark") {
            document.querySelector(".core-css").disabled = true;
            document.querySelector(".theme-css").disabled = true;
            document.querySelector(".core-dark-css").disabled = false;
            document.querySelector(".theme-dark-css").disabled = false;
            document.getElementById("theme-icon").className = "bx bx-moon bx-sm"; // Change icon
        } else {
            document.querySelector(".core-css").disabled = false;
            document.querySelector(".theme-css").disabled = false;
            document.querySelector(".core-dark-css").disabled = true;
            document.querySelector(".theme-dark-css").disabled = true;
            document.getElementById("theme-icon").className = "bx bx-sun bx-sm"; // Change icon
        }
        localStorage.setItem("theme", theme);
    }

    // Set the initial theme
    updateTheme(currentTheme);


});


