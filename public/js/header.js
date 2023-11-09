const lang = document.querySelector('.header-lang'),
menuBurger = document.querySelector('.menu-burger'),
mobileMenu = document.querySelector('.mobile-menu'),
mobileMenuLists = document.querySelectorAll('.mobile-menu ul li'),
header = document.querySelector('.header'),
body = document.querySelector('body');

lang.addEventListener('click', () => {
    lang.classList.toggle('showLang')
});

menuBurger.addEventListener('click', () => {
    menuBurger.classList.toggle('change');
    mobileMenu.classList.toggle('showMobileMenu');
    body.classList.toggle('showScroll')
});

mobileMenuLists.forEach(btn => {
    btn.addEventListener('click', () => {
        menuBurger.classList.toggle('change');
        mobileMenu.classList.toggle('showMobileMenu');
        body.classList.toggle('showScroll')
    })
});