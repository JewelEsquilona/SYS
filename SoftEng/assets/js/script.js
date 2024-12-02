(function() {
    const menutoggle = document.querySelector(".menutoggle");
    const closemenu = document.querySelector(".close");
    const sidebar = document.querySelector(".bg-sidebar");
    const barhamburger = document.querySelector(".fa-bars");

    // Function for login and sign up
    if (menutoggle) {
        menutoggle.addEventListener("click", function() {
            sidebar.classList.toggle("hidden-sidebar");
            menutoggle.classList.toggle("fa-rotate-270");
            sidebar.classList.remove("hidden-sidebar-mobile");
        });
    }

    if (barhamburger) {
        barhamburger.addEventListener("click", function() {
            sidebar.classList.remove("hidden-sidebar-mobile");
        });
    }

    if (closemenu) {
        closemenu.addEventListener("click", function() {
            sidebar.classList.add("hidden-sidebar-mobile");
        });
    }
})();
