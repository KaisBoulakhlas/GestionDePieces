export const toggleActiveClassNav = () => {
    const navItems = document.querySelectorAll(".nav-item");
    if(navItems.length === 1){
        navItems[0].className = navItems[0].className.replace("nav-item", "nav-item active");
    }
    for (let i = 0; i < navItems.length; i++) {
        navItems[i].addEventListener("click", function() {
            const current = document.querySelectorAll(".active");
            current[0].className = current[0].className.replace("active", "");
            this.className += " active";
        });
    }
}

