/*MOBILERIA LEANDRA — main.js (CLEAN VERSION)*/

document.addEventListener('DOMContentLoaded', () => {

  /* ================= NAVBAR ================= */

  const navbar = document.getElementById('navbar');
  const navLinks = document.querySelectorAll('.nav-link');

  window.addEventListener('scroll', () => {

    if (navbar) {
      if (window.scrollY > 60) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    }

    updateActiveNav();
  });

  /* ================= ACTIVE NAV ================= */

  function updateActiveNav() {

    const sections = document.querySelectorAll('section[id]');
    let current = '';

    sections.forEach(section => {

      const top = section.offsetTop - 120;

      if (window.scrollY >= top) {
        current = section.getAttribute('id');
      }

    });

    navLinks.forEach(link => {

      link.classList.remove('active');

      if (link.getAttribute('href') === `#${current}`) {
        link.classList.add('active');
      }

    });

  }

  /* ================= HAMBURGER ================= */

  const hamburger = document.getElementById('hamburger');
  const navLinksEl = document.getElementById('nav-links');

  if (hamburger && navLinksEl) {

    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      navLinksEl.classList.toggle('open');
    });

  }

  /* ================= SMOOTH SCROLL ================= */

  document.querySelectorAll('a[href^="#"]').forEach(a => {

    a.addEventListener('click', (e) => {

      const target = document.querySelector(a.getAttribute('href'));

      if (target) {

        e.preventDefault();

        window.scrollTo({
          top: target.offsetTop - 70,
          behavior: 'smooth'
        });

      }

    });

  });

  /* ================= SLIDER (SAFE) ================= */

  const slides = document.querySelectorAll('.hero-slide');
  const dotsContainer = document.getElementById('sliderDots');

  let currentSlide = 0;
  let interval;

  if (slides.length && dotsContainer) {

    slides.forEach((_, i) => {

      const dot = document.createElement('button');
      dot.className = i === 0 ? 'slider-dot active' : 'slider-dot';

      dot.addEventListener('click', () => goToSlide(i));

      dotsContainer.appendChild(dot);

    });

    function goToSlide(i) {

      if (!slides[currentSlide]) return;

      slides[currentSlide].classList.remove('active');
      dotsContainer.children[currentSlide].classList.remove('active');

      currentSlide = i;

      slides[currentSlide].classList.add('active');
      dotsContainer.children[currentSlide].classList.add('active');

    }

    function nextSlide() {
      goToSlide((currentSlide + 1) % slides.length);
    }

    interval = setInterval(nextSlide, 5000);

  }

  /* ================= FILTER ================= */

  const filterBtns = document.querySelectorAll('.filter-btn');
  const cards = document.querySelectorAll('.produkt-card, .produkte-page-card');

  filterBtns.forEach(btn => {

    btn.addEventListener('click', () => {

      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.filter;

      cards.forEach(card => {

        if (filter === 'all' || card.dataset.cat === filter) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }

      });

    });

  });

  /* ================= CONTACT FORM (FIXED 100%) ================= */

  const contactForm = document.getElementById('contactForm');
  const formSuccess = document.getElementById('formSuccess');
  const formErrorMsg = document.getElementById('formErrorMsg');
  const submitBtn = document.getElementById('submitBtn');

  if (!contactForm) return;

  contactForm.addEventListener('submit', async (e) => {

    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const project = document.getElementById('project').value.trim();
    const number = document.getElementById('number').value.trim();
    const message = document.getElementById('message').value.trim();

    if (!name || !email || !project || !number || !message) {
      if (formErrorMsg) {
        formErrorMsg.textContent = 'Plotësoni të gjitha fushat.';
        formErrorMsg.style.display = 'block';
      }
      return;
    }

    submitBtn.disabled = true;

    const btnText = submitBtn.querySelector('.btn-text');
    if (btnText) btnText.textContent = 'Duke dërguar...';

    if (formSuccess) formSuccess.style.display = 'none';
    if (formErrorMsg) formErrorMsg.style.display = 'none';

    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('project', project);
    formData.append('number', number);
    formData.append('message', message);

    try {
      const response = await fetch('/mobilerialeandra/php/contact.php', {
    
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      console.log(data);

      if (data.success) {

        if (formSuccess) {
          formSuccess.style.display = 'block';
          formSuccess.textContent = 'Mesazhi u dërgua me sukses, do te ju kontaktojme se shpejti!';
        }

        contactForm.style.display = 'none';

      } else {

        if (formErrorMsg) {
          formErrorMsg.textContent = data.message || 'Gabim gjatë dërgimit.';
          formErrorMsg.style.display = 'block';
        }

      }

    } catch (err) {

      console.log(err);

      if (formErrorMsg) {
        formErrorMsg.textContent = 'Gabim serveri.';
        formErrorMsg.style.display = 'block';
      }

    } finally {

      submitBtn.disabled = false;

      if (btnText) btnText.textContent = 'Dërgo Mesazhin →';

    }

  });

});




const topButton = document.getElementById("topBtn");

window.addEventListener("scroll", () => {
    if (window.scrollY > 200) {
        topButton.style.display = "block";
    } else {
        topButton.style.display = "none";
    }
});

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}