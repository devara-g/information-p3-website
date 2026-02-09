function toggleMenu() {
  const navLinks = document.querySelector(".nav-links");
  const mobileMenu = document.querySelector(".mobile-menu");

  navLinks.classList.toggle("active");
  mobileMenu.classList.toggle("active");
}

// Scroll header effect
window.addEventListener("scroll", function () {
  const header = document.querySelector("header");
  if (window.scrollY > 50) {
    header.classList.add("scrolled");
  } else {
    header.classList.remove("scrolled");
  }
});

document.addEventListener("DOMContentLoaded", function () {
  // Mobile menu toggle
  const mobileMenu = document.querySelector(".mobile-menu");
  if (mobileMenu) {
    mobileMenu.addEventListener("click", toggleMenu);
  }

  // Close mobile menu when clicking outside
  document.addEventListener("click", function (event) {
    const navLinks = document.querySelector(".nav-links");
    const mobileMenuBtn = document.querySelector(".mobile-menu");
    const nav = document.querySelector("nav");

    if (navLinks && navLinks.classList.contains("active")) {
      if (!nav.contains(event.target)) {
        navLinks.classList.remove("active");
        mobileMenuBtn.classList.remove("active");
      }
    }
  });

  // Close mobile menu when clicking a link
  const navLinksItems = document.querySelectorAll(".nav-links a");
  navLinksItems.forEach((link) => {
    link.addEventListener("click", function (e) {
      const navLinks = document.querySelector(".nav-links");
      const mobileMenuBtn = document.querySelector(".mobile-menu");

      // Don't close menu if it's a dropdown parent link on mobile
      const isDropdownParent =
        this.parentElement.classList.contains("dropdown") &&
        this.nextElementSibling &&
        this.nextElementSibling.classList.contains("dropdown-menu") &&
        window.innerWidth <= 768;

      if (!isDropdownParent && navLinks.classList.contains("active")) {
        navLinks.classList.remove("active");
        mobileMenuBtn.classList.remove("active");

        // Close all dropdowns
        document.querySelectorAll(".dropdown").forEach((dropdown) => {
          dropdown.classList.remove("active");
        });
      }
    });
  });

  // Dropdown functionality
  const dropdowns = document.querySelectorAll(".dropdown");
  dropdowns.forEach((dropdown) => {
    const dropdownLink = dropdown.querySelector("a");
    const dropdownMenu = dropdown.querySelector(".dropdown-menu");

    if (dropdownLink && dropdownMenu) {
      // For mobile - click to toggle
      dropdownLink.addEventListener("click", function (e) {
        // Only prevent default on mobile or when menu is present
        if (window.innerWidth <= 768) {
          e.preventDefault();
          e.stopPropagation();

          // Close other dropdowns
          dropdowns.forEach((otherDropdown) => {
            if (otherDropdown !== dropdown) {
              otherDropdown.classList.remove("active");
            }
          });

          // Toggle current dropdown
          dropdown.classList.toggle("active");
        }
      });

      // For desktop - hover
      if (window.innerWidth > 768) {
        dropdown.addEventListener("mouseenter", () => {
          dropdownMenu.style.opacity = "1";
          dropdownMenu.style.visibility = "visible";
          dropdownMenu.style.transform = "translateY(0)";
        });
        dropdown.addEventListener("mouseleave", () => {
          dropdownMenu.style.opacity = "0";
          dropdownMenu.style.visibility = "hidden";
          dropdownMenu.style.transform = "translateY(10px)";
        });
      }
    }
  });

  // Parallax effect and logo rotation for hero section
  const heroSection = document.querySelector(".hero");
  const heroLogo = document.querySelector(".principal-photo");

  if (heroSection) {
    window.addEventListener("scroll", () => {
      const scrollPosition = window.scrollY;

      // background parallax
      heroSection.style.backgroundPositionY = scrollPosition * 0.3 + "px";

      // logo rotation via CSS variable
      if (heroLogo) {
        heroLogo.style.setProperty(
          "--scroll-rotate",
          scrollPosition * 0.5 + "deg",
        );
      }
    });
  }

  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Animation on scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  }, observerOptions);

  // Observe elements for animation
  const animatedElements = document.querySelectorAll(
    ".news-card, .about-card, .gallery-item, .structure-card",
  );
  animatedElements.forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(20px)";
    el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    observer.observe(el);
  });

  // Contact form handling
  const contactForm = document.querySelector("form");
  if (contactForm) {
    contactForm.addEventListener("submit", function (e) {
      e.preventDefault();
      alert(
        "Terima kasih! Pesan Anda telah terkirim. Kami akan merespons dalam 24 jam.",
      );
      contactForm.reset();
    });
  }

  // Admin button
  const adminBtn = document.querySelector(".admin-btn");
  if (adminBtn) {
    adminBtn.addEventListener("click", () => {
      console.log("Redirecting to admin panel...");
    });
  }

  // Counter Animation
  const counters = document.querySelectorAll(".counter");
  const counterObserverOptions = {
    threshold: 0.5,
    rootMargin: "0px",
  };

  const counterObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const counter = entry.target;
        const target = +counter.getAttribute("data-target");
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps

        let currentCount = 0;
        const updateCount = () => {
          if (currentCount < target) {
            currentCount += increment;
            counter.innerText = Math.ceil(currentCount);
            requestAnimationFrame(updateCount);
          } else {
            counter.innerText = target;
          }
        };

        updateCount();
        observer.unobserve(counter);
      }
    });
  }, counterObserverOptions);

  counters.forEach((counter) => {
    counterObserver.observe(counter);
  });
});
