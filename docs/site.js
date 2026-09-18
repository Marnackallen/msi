document.addEventListener('DOMContentLoaded', () => {
    const mobileMenu = document.getElementById('mobile-menu');
    const navLinks = document.getElementById('nav-links');
    const dropdowns = document.querySelectorAll('.has-dropdown');
    const fadeTargets = document.querySelectorAll('.fade-up');

    if (mobileMenu && navLinks) {
        mobileMenu.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

    dropdowns.forEach((dropdown) => {
        const trigger = dropdown.querySelector('.nav-item-title');

        if (!trigger) {
            return;
        }

        trigger.addEventListener('click', (event) => {
            if (window.innerWidth > 900) {
                return;
            }

            event.preventDefault();
            dropdown.classList.toggle('active');
        });
    });

    if (!fadeTargets.length || typeof IntersectionObserver === 'undefined') {
        fadeTargets.forEach((element) => {
            element.classList.add('is-visible');
        });
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    fadeTargets.forEach((element) => {
        observer.observe(element);
    });
});
