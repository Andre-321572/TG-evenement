
(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all)
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  /**
   * Easy on scroll event listener
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Mobile nav toggle
   */
  on('click', '.mobile-nav-toggle', function(e) {
    let navbar = select('#navbar');
    if (navbar) {
      navbar.classList.toggle('navbar-mobile')
    }
    this.classList.toggle('bi-list')
    this.classList.toggle('bi-x')
  })

  /**
   * Mobile nav dropdowns activate
   */
  on('click', '.navbar .dropdown > a', function(e) {
    let navbar = select('#navbar');
    if (navbar && navbar.classList.contains('navbar-mobile')) {
      e.preventDefault()
      this.nextElementSibling.classList.toggle('dropdown-active')
    }
  }, true)

  /**
   * Testimonials slider — only if Swiper is loaded
   */
  window.addEventListener('load', () => {
    if (typeof Swiper !== 'undefined') {
      try {
        new Swiper('.testimonials-carousel', {
          speed: 400,
          loop: true,
          autoplay: {
            delay: 5000,
            disableOnInteraction: false
          },
          pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true
          }
        });
      } catch(e) { /* silently ignore if no carousel found */ }

      try {
        new Swiper('.portfolio-details-slider', {
          speed: 400,
          autoplay: {
            delay: 5000,
            disableOnInteraction: false
          },
          pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true
          }
        });
      } catch(e) { /* silently ignore */ }
    }

    /**
     * Portfolio isotope and filter — only if Isotope is loaded
     */
    if (typeof Isotope !== 'undefined') {
      try {
        let portfolioContainer = select('.portfolio-container');
        if (portfolioContainer) {
          let portfolioIsotope = new Isotope(portfolioContainer, {
            itemSelector: '.portfolio-wrap',
            layoutMode: 'fitRows'
          });

          let portfolioFilters = select('#portfolio-flters li', true);
          on('click', '#portfolio-flters li', function(e) {
            e.preventDefault();
            portfolioFilters.forEach(function(el) {
              el.classList.remove('filter-active');
            });
            this.classList.add('filter-active');
            portfolioIsotope.arrange({ filter: this.getAttribute('data-filter') });
            if (typeof AOS !== 'undefined') AOS.refresh();
          }, true);
        }
      } catch(e) { /* silently ignore */ }
    }

    /**
     * Portfolio lightbox — only if GLightbox is loaded
     */
    if (typeof GLightbox !== 'undefined') {
      try {
        GLightbox({ selector: '.portfolio-lightbox' });
      } catch(e) { /* silently ignore */ }
    }

    /**
     * Animation on scroll — only if AOS is loaded
     */
    if (typeof AOS !== 'undefined') {
      try {
        AOS.init({
          duration: 1000,
          easing: "ease-in-out",
          once: true,
          mirror: false
        });
      } catch(e) { /* silently ignore */ }
    }

    /**
     * Pure Counter — only if PureCounter is loaded
     */
    if (typeof PureCounter !== 'undefined') {
      try { new PureCounter(); } catch(e) { /* silently ignore */ }
    }
  });

})()
