document.addEventListener('DOMContentLoaded', function() {
  // Menu Toggle for Mobile View
  const menuToggle = document.querySelector('.js-site-menu-toggle');
  const navbar = document.querySelector('.js-site-navbar');
  let isMenuOpen = false;

  if (menuToggle && navbar) {
      menuToggle.addEventListener('click', function() {
          if (isMenuOpen) {
              navbar.classList.remove('open');
          } else {
              navbar.classList.add('open');
          }
          isMenuOpen = !isMenuOpen;
      });
  }
});
