/**
 * Optimized Popular Classes Pagination Component
 * Minimal, efficient JavaScript for single-packet navigation
 */
class PopularClassesPagination {
    constructor(packets) {
        this.packets = packets || [];
        this.currentIndex = 0;
        this.defaultImage =
            window.defaultPacketImage || "/images/hero-illustration.png";

        if (!Array.isArray(this.packets) || this.packets.length === 0) {
            console.error("Invalid packets data");
            return;
        }

        this.init();
    }

    init() {
        this.cacheElements();
        this.updateDisplay();
        this.bindEvents();
    }

    cacheElements() {
        // Cache DOM elements for better performance
        this.elements = {
            image: document.getElementById("packet-image"),
            title: document.getElementById("packet-title"),
            detailBtn: document.getElementById("detail-btn"),
            prevBtn: document.getElementById("prev-btn"),
            nextBtn: document.getElementById("next-btn"),
            loading: document.getElementById("image-loading"),
        };
    }

    updateDisplay() {
        const packet = this.packets[this.currentIndex];
        if (!packet) return;

        const { image, title, detailBtn, loading } = this.elements;

        // Update image with lazy loading optimization
        if (image) {
            if (loading) loading.style.display = "flex";
            image.src = packet.image_url || this.defaultImage;
            image.alt = packet.title || "Packet Image";
        }

        // Update title
        if (title) {
            title.textContent = packet.title || "Untitled Packet";
        }

        // Update detail link
        if (detailBtn && packet.id) {
            detailBtn.href = `/beli-paket/${packet.id}`;
        }

        this.updateNavigation();
    }

    updateNavigation() {
        const { prevBtn, nextBtn } = this.elements;
        if (!prevBtn || !nextBtn) return;

        const isFirst = this.currentIndex === 0;
        const isLast = this.currentIndex === this.packets.length - 1;

        // Update disabled state and styles efficiently
        this.toggleButton(prevBtn, isFirst);
        this.toggleButton(nextBtn, isLast);
    }

    toggleButton(btn, disabled) {
        btn.disabled = disabled;
        btn.classList.toggle("text-gray-300", disabled);
        btn.classList.toggle("text-[#01A8DC]", !disabled);
        btn.classList.toggle("hover:text-[#0195c9]", !disabled);
    }

    bindEvents() {
        const { prevBtn, nextBtn } = this.elements;

        if (prevBtn) {
            prevBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.navigate(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.navigate(1);
            });
        }

        // Keyboard navigation
        document.addEventListener("keydown", (e) => {
            if (e.key === "ArrowLeft") this.navigate(-1);
            else if (e.key === "ArrowRight") this.navigate(1);
        });
    }

    navigate(direction) {
        const newIndex = this.currentIndex + direction;
        if (newIndex >= 0 && newIndex < this.packets.length) {
            this.currentIndex = newIndex;
            this.updateDisplay();
        }
    }
}

// Global image handlers (minimal)
window.handleImageError = function (img) {
    img.src = window.defaultPacketImage || "/images/hero-illustration.png";
    const loading = document.getElementById("image-loading");
    if (loading) loading.style.display = "none";
};

window.handleImageLoad = function (img) {
    const loading = document.getElementById("image-loading");
    if (loading) loading.style.display = "none";
};

// Export for module systems or global use
if (typeof module !== "undefined" && module.exports) {
    module.exports = PopularClassesPagination;
} else {
    window.PopularClassesPagination = PopularClassesPagination;
}
