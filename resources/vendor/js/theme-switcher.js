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
            const coreCss = document.querySelector(".core-css");
            const themeCss = document.querySelector(".theme-css");
            const coreDarkCss = document.querySelector(".core-dark-css");
            const themeDarkCss = document.querySelector(".theme-dark-css");
            const themeIcon = document.getElementById("theme-icon");


            if (theme === "dark") {
                if (coreCss) coreCss.disabled = true;
                if (themeCss) themeCss.disabled = true;
                if (coreDarkCss) coreDarkCss.disabled = false;
                if (themeDarkCss) themeDarkCss.disabled = false;
                if (themeIcon) themeIcon.className = "bx bx-moon bx-sm"; // Change icon
            } else {
                if (coreCss) coreCss.disabled = false;
                if (themeCss) themeCss.disabled = false;
                if (coreDarkCss) coreDarkCss.disabled = true;
                if (themeDarkCss) themeDarkCss.disabled = true;
                if (themeIcon) themeIcon.className = "bx bx-sun bx-sm"; // Change icon
            }

            localStorage.setItem("theme", theme);
        }
    // Set the initial theme
    updateTheme(currentTheme);
});

