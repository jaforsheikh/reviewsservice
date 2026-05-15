(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    if (typeof gsap === "undefined") {
      return;
    }

    if (typeof ScrollTrigger !== "undefined") {
      gsap.registerPlugin(ScrollTrigger);
    }

    foundationAnimation();
    scrollRevealAnimation();
  });

  function foundationAnimation() {
    const foundationContent = document.querySelector(".rs-foundation-content");

    if (!foundationContent) {
      return;
    }

    gsap.from(foundationContent.children, {
      y: 32,
      opacity: 0,
      duration: 0.9,
      stagger: 0.12,
      ease: "power3.out",
    });
  }

  function scrollRevealAnimation() {
    if (typeof ScrollTrigger === "undefined") {
      return;
    }

    const revealItems = document.querySelectorAll("[data-rs-reveal]");

    if (!revealItems.length) {
      return;
    }

    revealItems.forEach(function (item) {
      gsap.from(item, {
        y: 42,
        opacity: 0,
        duration: 0.85,
        ease: "power3.out",
        scrollTrigger: {
          trigger: item,
          start: "top 85%",
        },
      });
    });
  }
})();
