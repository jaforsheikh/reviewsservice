document.addEventListener("DOMContentLoaded", function () {
  const thumbs = document.querySelectorAll(".rs-thumb-btn");
  const mainImage = document.querySelector(".rs-main-product-image");

  if (!thumbs.length || !mainImage) return;

  thumbs.forEach((thumb) => {
    thumb.addEventListener("click", function () {
      const imageUrl = this.getAttribute("data-full-image");

      if (imageUrl) {
        mainImage.src = imageUrl;
      }

      thumbs.forEach((item) => {
        item.classList.remove("border-[#00C853]", "border-2");
      });

      this.classList.add("border-[#00C853]", "border-2");
    });
  });
});
