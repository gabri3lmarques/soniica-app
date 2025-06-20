
    const userAvatar = document.querySelector(".user-avatar");
    const userSubMenu = document.querySelector(".user-sub-menu");

    if (userAvatar && userSubMenu) {
        userAvatar.addEventListener("click", function (event) {
            userSubMenu.classList.toggle("active");
        });

        document.addEventListener("click", function (event) {
            if (!userAvatar.contains(event.target) && !userSubMenu.contains(event.target)) {
                userSubMenu.classList.remove("active");
            }
        });
    }
