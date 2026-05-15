/**
 * Reputation Management Page Scripts
 *
 * @package reviewsservice
 */

(function () {
  "use strict";

  /**
   * =========================================================
   * PLATFORM MODAL SYSTEM
   * =========================================================
   */

  const modal = document.querySelector("[data-rm-modal]");
  const modalContent = document.querySelector("[data-rm-modal-content]");
  const closeButtons = document.querySelectorAll("[data-rm-modal-close]");
  const openButtons = document.querySelectorAll("[data-rm-modal-open]");
  const dataScript = document.getElementById("rm-platform-data");

  if (!modal || !modalContent || !dataScript) {
    return;
  }

  let platforms = [];

  try {
    platforms = JSON.parse(dataScript.textContent || "[]");
  } catch (error) {
    platforms = [];
  }

  const escapeHTML = function (value) {
    return String(value || "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  };

  const renderIncludedList = function (items) {
    if (!Array.isArray(items) || items.length === 0) {
      return "";
    }

    return items
      .map(function (item) {
        return `
                    <li class="flex items-start gap-3 text-sm font-semibold leading-6 text-[#374151]">
                        <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00C853] text-xs font-black text-white">✓</span>
                        <span>${escapeHTML(item)}</span>
                    </li>
                `;
      })
      .join("");
  };

  const renderPlatformModal = function (platform) {
    return `
            <div class="pr-10">
                <div class="flex items-start gap-4">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl text-sm font-black text-white" style="background:${escapeHTML(platform.accent)};">
                        ${escapeHTML(platform.icon)}
                    </span>

                    <div>
                        <p class="text-sm font-black uppercase tracking-[0.12em] text-[#00A344]">
                            Platform Details
                        </p>

                        <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#0D0F12]">
                            ${escapeHTML(platform.name)}
                        </h2>

                        <p class="mt-3 text-base font-semibold leading-7 text-[#374151]">
                            ${escapeHTML(platform.short)}
                        </p>
                    </div>
                </div>

                <div class="mt-7 grid gap-5">
                    <section class="rounded-3xl bg-[#F3F4F6] p-6">
                        <h3 class="text-lg font-black text-[#0D0F12]">Overview</h3>
                        <p class="mt-3 text-base font-medium leading-8 text-[#374151]">
                            ${escapeHTML(platform.overview)}
                        </p>
                    </section>

                    <section class="rounded-3xl border border-red-100 bg-red-50 p-6">
                        <h3 class="text-lg font-black text-red-600">Customer Problem</h3>
                        <p class="mt-3 text-base font-medium leading-8 text-[#374151]">
                            ${escapeHTML(platform.problem)}
                        </p>
                    </section>

                    <section class="rounded-3xl border border-[#00C853]/20 bg-[#00C853]/5 p-6">
                        <h3 class="text-lg font-black text-[#00A344]">Our Solution</h3>
                        <p class="mt-3 text-base font-medium leading-8 text-[#374151]">
                            ${escapeHTML(platform.solution)}
                        </p>
                    </section>

                    <section class="rounded-3xl border border-[#E5E7EB] bg-white p-6">
                        <h3 class="text-lg font-black text-[#0D0F12]">What’s Included</h3>
                        <ul class="mt-4 space-y-3">
                            ${renderIncludedList(platform.included)}
                        </ul>
                    </section>

                    <section class="rounded-3xl bg-[#0D0F12] p-6 text-white">
                        <h3 class="text-lg font-black text-white">Expected Business Outcome</h3>
                        <p class="mt-3 text-base font-medium leading-8 text-white/75">
                            ${escapeHTML(platform.outcome)}
                        </p>
                    </section>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="/contact/" class="inline-flex items-center justify-center rounded-full bg-[#00C853] px-6 py-3 text-sm font-bold text-[#0D0F12] transition hover:bg-[#00E676]">
                            Get Free Audit →
                        </a>

                        <button type="button" class="inline-flex items-center justify-center rounded-full border border-[#E5E7EB] px-6 py-3 text-sm font-bold text-[#374151] transition hover:bg-[#F3F4F6]" data-rm-modal-close>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
  };

  const openModal = function (platformId) {
    const platform = platforms.find(function (item) {
      return item.id === platformId;
    });

    if (!platform) {
      return;
    }

    modalContent.innerHTML = renderPlatformModal(platform);
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.style.overflow = "hidden";
  };

  const closeModal = function () {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    modalContent.innerHTML = "";
    document.body.style.overflow = "";
  };

  openButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      openModal(button.getAttribute("data-platform-id"));
    });
  });

  closeButtons.forEach(function (button) {
    button.addEventListener("click", closeModal);
  });

  modal.addEventListener("click", function (event) {
    if (event.target === modal) {
      closeModal();
    }

    if (event.target.closest("[data-rm-modal-close]")) {
      closeModal();
    }
  });

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && !modal.classList.contains("hidden")) {
      closeModal();
    }
  });
})();
