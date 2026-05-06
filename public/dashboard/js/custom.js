document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");
    const header = document.querySelector(".header");
    const overlay = document.getElementById("overlay");
    const profileButton = document.querySelector(".profile-button");
    const dropdownMenu = document.querySelector(".dropdown-menu");
    const submenuItems = document.querySelectorAll(".sidebar .has-submenu");

    function isMobileLayout() {
        return window.innerWidth <= 1024;
    }

    function setOverlayVisible(visible) {
        if (!overlay) {
            return;
        }

        overlay.classList.toggle("show", visible);
    }

    function closeMobileSidebar() {
        if (!sidebar) {
            return;
        }

        sidebar.classList.remove("show");
        setOverlayVisible(false);
    }

    function setDesktopSidebarState(collapsed) {
        if (!sidebar || !content || !header) {
            return;
        }

        document.body.classList.toggle("sidebar-collapsed", collapsed);
        sidebar.classList.toggle("collapsed", collapsed);
        content.classList.toggle("collapsed", collapsed);
        header.classList.toggle("collapsed", collapsed);
    }

    function getSubmenu(item) {
        return item ? item.querySelector(":scope > .submenu") : null;
    }

    function getSubmenuToggle(item) {
        return item ? item.querySelector(":scope > .submenu-toggle") : null;
    }

    function openSubmenu(item, animate) {
        const submenu = getSubmenu(item);
        const toggle = getSubmenuToggle(item);

        if (!submenu) {
            return;
        }

        item.classList.add("show");
        if (toggle) {
            toggle.setAttribute("aria-expanded", "true");
        }

        if (!animate) {
            submenu.style.maxHeight = "none";
            return;
        }

        submenu.style.maxHeight = "0px";
        requestAnimationFrame(function () {
            submenu.style.maxHeight = submenu.scrollHeight + "px";
        });
    }

    function closeSubmenu(item) {
        const submenu = getSubmenu(item);
        const toggle = getSubmenuToggle(item);

        if (!submenu) {
            return;
        }

        submenu.style.maxHeight = submenu.scrollHeight + "px";
        requestAnimationFrame(function () {
            item.classList.remove("show");
            submenu.style.maxHeight = "0px";
        });

        if (toggle) {
            toggle.setAttribute("aria-expanded", "false");
        }
    }

    function closeOtherSubmenus(activeItem) {
        submenuItems.forEach(function (item) {
            if (item !== activeItem && item.classList.contains("show")) {
                closeSubmenu(item);
            }
        });
    }

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", function () {
            if (isMobileLayout()) {
                const willOpen = !sidebar.classList.contains("show");
                sidebar.classList.toggle("show", willOpen);
                setOverlayVisible(willOpen);
                return;
            }

            setDesktopSidebarState(!sidebar.classList.contains("collapsed"));
        });
    }

    if (overlay) {
        overlay.addEventListener("click", closeMobileSidebar);
    }

    submenuItems.forEach(function (item) {
        const toggle = getSubmenuToggle(item);
        const submenu = getSubmenu(item);

        if (!toggle || !submenu) {
            return;
        }

        toggle.addEventListener("click", function (event) {
            event.preventDefault();

            const isOpen = item.classList.contains("show");
            closeOtherSubmenus(item);

            if (isOpen) {
                closeSubmenu(item);
                return;
            }

            openSubmenu(item, true);
        });

        submenu.addEventListener("transitionend", function (event) {
            if (event.propertyName === "max-height" && item.classList.contains("show")) {
                submenu.style.maxHeight = "none";
            }
        });
    });

    const currentUrl = window.location.pathname;
    document.querySelectorAll(".sidebar a[href]").forEach(function (item) {
        const link = item.getAttribute("href");

        if (!link || link === "#") {
            return;
        }

        const linkPath = new URL(link, window.location.href).pathname;

        if (currentUrl === linkPath) {
            const currentLi = item.closest("li");
            const parentLi = item.closest(".has-submenu");

            if (currentLi) {
                currentLi.classList.add("menu-active");
            }

            if (parentLi) {
                openSubmenu(parentLi, false);
            }
        }
    });

    window.addEventListener("resize", function () {
        if (!isMobileLayout()) {
            closeMobileSidebar();
            return;
        }

        document.body.classList.remove("sidebar-collapsed");
        sidebar.classList.remove("collapsed");
        content.classList.remove("collapsed");
        header.classList.remove("collapsed");
    });

    if (profileButton && dropdownMenu) {
        profileButton.addEventListener("click", function (event) {
            event.stopPropagation();
            dropdownMenu.classList.toggle("show");
        });

        document.addEventListener("click", function (event) {
            if (!profileButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove("show");
            }
        });
    }
});
