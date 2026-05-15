/**
 * Reviews Service — Global JS
 * Runs on every page.
 * Homepage-specific JS lives in assets/js/home.js
 */

(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    // ── Lazy image fade-in ──────────────────────────────────────────────────
    if ("IntersectionObserver" in window) {
      var lazyImgs = document.querySelectorAll('img[loading="lazy"]');
      var imgObserver = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add("loaded");
              imgObserver.unobserve(entry.target);
            }
          });
        },
        { rootMargin: "200px" },
      );

      lazyImgs.forEach(function (img) {
        if (img.complete) {
          img.classList.add("loaded");
        } else {
          imgObserver.observe(img);
          img.addEventListener("load", function () {
            img.classList.add("loaded");
          });
        }
      });
    }

    // ── Mobile menu ─────────────────────────────────────────────────────────
    var menuToggle = document.querySelector("[data-menu-toggle]");
    var mobileMenu = document.querySelector("[data-mobile-menu]");
    var menuClose = document.querySelector("[data-menu-close]");

    if (menuToggle && mobileMenu) {
      function openMenu() {
        mobileMenu.classList.remove("hidden");
        mobileMenu.setAttribute("aria-hidden", "false");
        menuToggle.setAttribute("aria-expanded", "true");
        document.body.style.overflow = "hidden";
      }

      function closeMenu() {
        mobileMenu.classList.add("hidden");
        mobileMenu.setAttribute("aria-hidden", "true");
        menuToggle.setAttribute("aria-expanded", "false");
        document.body.style.overflow = "";
      }

      menuToggle.addEventListener("click", openMenu);
      if (menuClose) menuClose.addEventListener("click", closeMenu);

      mobileMenu.addEventListener("click", function (e) {
        if (e.target === mobileMenu) closeMenu();
      });

      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeMenu();
      });
    }

    // ── Sticky nav ──────────────────────────────────────────────────────────
    var siteNav = document.querySelector("[data-sticky-nav]");

    if (siteNav) {
      window.addEventListener(
        "scroll",
        function () {
          if (window.scrollY > 40) {
            siteNav.classList.add("rs-nav-scrolled");
          } else {
            siteNav.classList.remove("rs-nav-scrolled");
          }
        },
        { passive: true },
      );
    }

    // ── FAQ accordion (global — used on FAQ page and front-page) ───────────
    // Note: front-page.php has its own inline FAQ JS.
    // This runs on OTHER pages (FAQ page template, etc.)
    var faqLists = document.querySelectorAll("[data-faq-list]");

    faqLists.forEach(function (list) {
      var buttons = list.querySelectorAll("[data-faq-trigger]");

      buttons.forEach(function (btn) {
        btn.addEventListener("click", function () {
          var expanded = this.getAttribute("aria-expanded") === "true";
          var panelId = this.getAttribute("aria-controls");
          var panel = document.getElementById(panelId);
          var icon = this.querySelector("[data-faq-icon]");

          // Close all in this list
          buttons.forEach(function (b) {
            b.setAttribute("aria-expanded", "false");
            var p = document.getElementById(b.getAttribute("aria-controls"));
            if (p) p.classList.add("hidden");
            var ic = b.querySelector("[data-faq-icon]");
            if (ic) ic.textContent = "+";
          });

          // Open clicked if it was closed
          if (!expanded && panel) {
            this.setAttribute("aria-expanded", "true");
            panel.classList.remove("hidden");
            if (icon) icon.textContent = "−";
          }
        });
      });
    });
  });
})();
