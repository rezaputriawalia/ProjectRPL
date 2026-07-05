import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.Alpine = Alpine;

Alpine.start();

/*
|--------------------------------------------------------------------------
| Sidebar Mobile
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", () => {

    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const toggle = document.getElementById("sidebarToggle");

    if (!sidebar || !overlay || !toggle) {
        return;
    }

    function openSidebar() {
        sidebar.classList.add("show");
        overlay.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeSidebar() {
        sidebar.classList.remove("show");
        overlay.classList.remove("show");
        document.body.style.overflow = "";
    }

    toggle.addEventListener("click", () => {

        if (sidebar.classList.contains("show")) {
            closeSidebar();
        } else {
            openSidebar();
        }

    });

    overlay.addEventListener("click", closeSidebar);

    document.querySelectorAll(".sigap-nav-item").forEach(item => {

        item.addEventListener("click", () => {

            if (window.innerWidth < 992) {
                closeSidebar();
            }

        });

    });

    window.addEventListener("resize", () => {

        if (window.innerWidth >= 992) {
            closeSidebar();
        }

    });

});