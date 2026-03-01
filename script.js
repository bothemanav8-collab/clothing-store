document.addEventListener("DOMContentLoaded", function(){

    /* ================= REVEAL + 3D CARDS ================= */

    const cards = document.querySelectorAll(".card, .product-card");

    if (cards.length && "IntersectionObserver" in window) {

        const observer = new IntersectionObserver((entries) => {

            entries.forEach((entry, index) => {

                if (entry.isIntersecting) {

                    setTimeout(() => {
                        entry.target.classList.add("show");
                    }, index * 120);

                    observer.unobserve(entry.target);
                }

            });

        }, { threshold: 0.12 });

        cards.forEach(card => observer.observe(card));
    }

    /* ================= 3D Hover Effect ================= */

    cards.forEach(card => {

        card.addEventListener("mousemove", function(e){

            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 18;
            const rotateY = (centerX - x) / 18;

            card.style.transform =
                `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px)`;

        });

        card.addEventListener("mouseleave", function(){
            card.style.transform = "";
        });

    });

    /* ================= DARK / LIGHT MODE ================= */

    const toggleBtn = document.getElementById("themeToggle");

    if(toggleBtn){

        if(localStorage.getItem("theme") === "light"){
            document.body.classList.add("light-mode");
            toggleBtn.innerHTML = "☀️";
        } else {
            toggleBtn.innerHTML = "🌙";
        }

        toggleBtn.addEventListener("click", () => {

            document.body.classList.toggle("light-mode");

            if(document.body.classList.contains("light-mode")){
                localStorage.setItem("theme", "light");
                toggleBtn.innerHTML = "☀️";
            } else {
                localStorage.setItem("theme", "dark");
                toggleBtn.innerHTML = "🌙";
            }

        });
    }

    /* ================= NAVBAR SCROLL ================= */

    const navbar = document.getElementById("navbar");

    if(navbar){
        window.addEventListener("scroll", function(){
            navbar.classList.toggle("scrolled", window.scrollY > 50);
        });
    }

    /* ================= MOBILE MENU ================= */

    const menuToggle = document.getElementById("menuToggle");
    const navLinks = document.getElementById("navLinks");

    if(menuToggle){
        menuToggle.addEventListener("click", function(e){
            e.stopPropagation();
            navLinks.classList.toggle("active");
        });
    }

    /* ================= PROFILE DROPDOWN (FIXED) ================= */

    const profileMenu = document.getElementById("profileMenu");
    const dropdown = document.getElementById("dropdownMenu");

    if(profileMenu && dropdown){

        profileMenu.addEventListener("click", function(e){
            e.stopPropagation();
            dropdown.classList.toggle("show");
        });

        document.addEventListener("click", function(e){
            if(!profileMenu.contains(e.target)){
                dropdown.classList.remove("show");
            }
        });
    }

    /* ================= STAR RATING ================= */

    const stars = document.querySelectorAll(".star");
    const ratingInput = document.getElementById("ratingValue");

    if(stars.length && ratingInput){

        stars.forEach((star, index) => {

            star.addEventListener("click", function(){

                const value = this.getAttribute("data-value");
                ratingInput.value = value;

                stars.forEach(s => s.classList.remove("active"));

                for(let i = 0; i < value; i++){
                    stars[i].classList.add("active");
                }

            });

        });
    }

});
/* ================= MAGNETIC + GLOW BUTTON ================= */

document.querySelectorAll(".hero-btn").forEach(button => {

    button.addEventListener("mousemove", function(e){

        const rect = button.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;

        const moveX = x * 0.3; 
        const moveY = y * 0.3;

        button.style.transform = `translate(${moveX}px, ${moveY}px)`;
    });

    button.addEventListener("mouseleave", function(){
        button.style.transform = "translate(0,0)";
    });

});

document.addEventListener("DOMContentLoaded", function() {
    const searchIcon = document.getElementById("search-icon");
    const searchWrapper = document.querySelector(".search-wrapper");

    if(searchIcon){
        searchIcon.addEventListener("click", () => {
            searchWrapper.classList.toggle("active");
        });
    }
});
