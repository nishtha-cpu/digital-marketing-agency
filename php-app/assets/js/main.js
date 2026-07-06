document.addEventListener('DOMContentLoaded', () => {

  // Initialize Lucide icons (UMD build uses window.lucide)
  const initIcons = () => {
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
      window.lucide.createIcons();
    }
  };
  initIcons();

  // ─── Smooth Scroll ────────────────────────────────────────────────────
  const smoothScrollTo = (href) => {
    const id = href.replace('#', '');
    const el = document.getElementById(id);
    if (!el) return;
    const offset = 104; // orange bar (~32px) + white nav (~72px)
    const top = el.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: 'smooth' });
  };

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (!href || href === '#') return;
      e.preventDefault();
      smoothScrollTo(href);
      // Close mobile menu if open
      closeMobileMenu();
    });
  });

  // ─── Mobile Menu ──────────────────────────────────────────────────────
  const menuBtn  = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const menuIcon = document.getElementById('mobile-menu-icon');
  let menuOpen = false;

  function openMobileMenu() {
    menuOpen = true;
    mobileMenu.classList.remove('hidden');
    mobileMenu.classList.add('flex');
    menuIcon.setAttribute('data-lucide', 'x');
    initIcons();
  }

  function closeMobileMenu() {
    menuOpen = false;
    if (mobileMenu) {
      mobileMenu.classList.add('hidden');
      mobileMenu.classList.remove('flex');
    }
    if (menuIcon) {
      menuIcon.setAttribute('data-lucide', 'menu');
      initIcons();
    }
  }

  if (menuBtn && mobileMenu) {
    menuBtn.addEventListener('click', () => {
      if (menuOpen) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });
  }

  // ─── Sticky Navbar Shadow ─────────────────────────────────────────────
  const navbar = document.getElementById('main-navbar');
  if (navbar) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 40) {
        navbar.classList.add('shadow-lg');
        navbar.classList.remove('shadow-md');
      } else {
        navbar.classList.add('shadow-md');
        navbar.classList.remove('shadow-lg');
      }
    });
  }

  // ─── Testimonial Active State ─────────────────────────────────────────
  const testimonialCards = document.querySelectorAll('.testimonial-card');
  testimonialCards.forEach((card, index) => {
    card.addEventListener('click', () => {
      testimonialCards.forEach(c => {
        c.style.boxShadow = '';
        c.style.outline   = '';
      });
      card.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1)';
      card.style.outline   = '2px solid rgba(212,98,10,0.2)';
    });
  });

  // ─── Contact Form AJAX ────────────────────────────────────────────────
  const contactForm = document.getElementById('contact-form');
  const formStatus  = document.getElementById('form-status');
  const submitBtn   = document.getElementById('submit-btn');

  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const fd = new FormData(contactForm);
      const payload = {
        name:             ((fd.get('firstName') || '') + ' ' + (fd.get('lastName') || '')).trim(),
        email:            fd.get('email'),
        service_interest: fd.get('interest'),
        message:          fd.get('message')
      };

      if (!fd.get('firstName') || !payload.email || !payload.message) {
        showStatus('error', 'Please fill in all required fields (First Name, Email, and Message).');
        return;
      }

      showStatus('info', 'Sending your message…');
      if (submitBtn) submitBtn.disabled = true;

      try {
        const baseUrl = window.BASE_URL || '';
        const res = await fetch(`${baseUrl}/api/leads/index.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const result = await res.json();
        if (result.success) {
          showStatus('success', 'Your message has been sent successfully!');
          contactForm.reset();
        } else {
          showStatus('error', result.error || 'Failed to send message.');
        }
      } catch (err) {
        console.error('Contact form error:', err);
        showStatus('error', 'An error occurred. Please try again later.');
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  }

  function showStatus(type, message) {
    if (!formStatus) return;
    formStatus.className = 'p-4 rounded-xl text-sm border';
    if (type === 'success') {
      formStatus.style.cssText = 'background:#ecfdf5;color:#065f46;border-color:#a7f3d0;display:block;padding:1rem;border-radius:0.75rem;margin-top:0.5rem;';
    } else if (type === 'error') {
      formStatus.style.cssText = 'background:#fef2f2;color:#991b1b;border-color:#fecaca;display:block;padding:1rem;border-radius:0.75rem;margin-top:0.5rem;';
    } else {
      formStatus.style.cssText = 'background:#eff6ff;color:#1e40af;border-color:#bfdbfe;display:block;padding:1rem;border-radius:0.75rem;margin-top:0.5rem;';
    }
    formStatus.textContent = message;
  }

  // ─── Newsletter Form AJAX ─────────────────────────────────────────────
  const newsletterForm      = document.getElementById('newsletter-form');
  const newsletterStatus    = document.getElementById('newsletter-status');
  const newsletterSubmitBtn = document.getElementById('newsletter-submit-btn');

  if (newsletterForm) {
    newsletterForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      const email = document.getElementById('newsletter-email')?.value.trim();
      if (!email) {
        showNewsletterStatus('error', 'Please enter a valid email address.');
        return;
      }

      showNewsletterStatus('info', 'Subscribing…');
      if (newsletterSubmitBtn) newsletterSubmitBtn.disabled = true;

      try {
        const baseUrl = window.BASE_URL || '';
        const res = await fetch(`${baseUrl}/api/newsletter/subscribe.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email })
        });
        const result = await res.json();
        if (result.success) {
          showNewsletterStatus('success', result.message || 'Subscribed successfully! Thank you.');
          newsletterForm.reset();
        } else {
          showNewsletterStatus('error', result.message || 'Failed to subscribe. Please try again.');
        }
      } catch (err) {
        console.error('Newsletter error:', err);
        showNewsletterStatus('error', 'An error occurred. Please try again later.');
      } finally {
        if (newsletterSubmitBtn) newsletterSubmitBtn.disabled = false;
      }
    });
  }

  function showNewsletterStatus(type, message) {
    if (!newsletterStatus) return;
    newsletterStatus.style.display = 'block';
    if (type === 'success') {
      newsletterStatus.style.color = '#86efac';
    } else if (type === 'error') {
      newsletterStatus.style.color = '#fca5a5';
    } else {
      newsletterStatus.style.color = 'rgba(255,255,255,0.8)';
    }
    newsletterStatus.textContent = message;
  }

});
