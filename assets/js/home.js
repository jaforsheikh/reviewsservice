document.addEventListener("DOMContentLoaded", function () {
  const slides = Array.from(document.querySelectorAll(".rs-home-hero-slide"));
  const dots = Array.from(document.querySelectorAll(".rs-home-hero-dot"));

  const label = document.getElementById("rsHeroLabel");
  const title = document.getElementById("rsHeroTitle");
  const text = document.getElementById("rsHeroText");

  if (!slides.length) return;

  let current = 0;
  let timer = null;

  function showSlide(index) {
    const active = slides[index];

    slides.forEach((slide, slideIndex) => {
      slide.style.opacity = slideIndex === index ? "1" : "0";
      slide.style.transform =
        slideIndex === index ? "scale(1.04)" : "scale(1.15)";
      slide.style.zIndex = slideIndex === index ? "2" : "1";
      slide.style.transition = "opacity 1200ms ease, transform 5200ms ease";
    });

    dots.forEach((dot, dotIndex) => {
      dot.style.backgroundColor =
        dotIndex === index ? "#FFC107" : "rgba(255,255,255,0.35)";
      dot.style.width = dotIndex === index ? "38px" : "12px";
    });

    [label, title, text].forEach((item) => {
      if (!item) return;
      item.style.opacity = "0";
      item.style.transform = "translateY(18px)";
      item.style.transition = "all 360ms ease";
    });

    setTimeout(() => {
      if (label) label.textContent = active.dataset.label;
      if (title) title.textContent = active.dataset.title;
      if (text) text.textContent = active.dataset.text;

      [label, title, text].forEach((item, itemIndex) => {
        if (!item) return;

        setTimeout(() => {
          item.style.opacity = "1";
          item.style.transform = "translateY(0)";
          item.style.transition = "all 700ms cubic-bezier(0.22, 1, 0.36, 1)";
        }, itemIndex * 100);
      });
    }, 220);

    current = index;
  }

  function startSlider() {
    clearInterval(timer);
    timer = setInterval(function () {
      showSlide((current + 1) % slides.length);
    }, 5200);
  }

  dots.forEach((dot, index) => {
    dot.addEventListener("click", function () {
      showSlide(index);
      startSlider();
    });
  });

  showSlide(0);
  startSlider();
});
// Hero JS Slider
(function () {
    const slides = document.querySelectorAll('.rs-reputation-hero .absolute.inset-0');
    if (!slides.length) return;

    let current = 0;

    setInterval(() => {
        slides[current].classList.remove('opacity-100','scale-[1.05]');
        slides[current].classList.add('opacity-0','scale-100');

        current = (current + 1) % slides.length;

        slides[current].classList.add('opacity-100','scale-[1.05]');
        slides[current].classList.remove('opacity-0','scale-100');
    }, 7000);
})();
