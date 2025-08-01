/**
 * PopularClassesPagination - Handles single-packet navigation for popular classes section
 *
 * Requirements covered:
 * - 2.1: Display exactly 1 packet per page
 * - 2.2: Provide pagination controls (previous/next arrows)
 * - 2.3: Disable previous arrow on first packet
 * - 2.4: Disable next arrow on last packet
 * - 2.5: Navigate without page reload
 */
class PopularClassesPagination {
    constructor(packets, options = {}) {
        this.packets = packets || [];
        this.currentIndex = 0;

        // DOM element selectors
        this.selectors = {
            container: options.container || "#popular-classes-container",
            image: options.image || "#packet-image",
            title: options.title || "#packet-title",
            detailBtn: options.detailBtn || "#detail-btn",
            prevBtn: options.prevBtn || "#prev-btn",
            nextBtn: options.nextBtn || "#next-btn",
            ...options.selectors,
        };

        // Default image fallback
        this.defaultImage =
            options.defaultImage || "/images/default-packet.png";

        this.init();
    }

    /**
     * Initialize the pagination component
     */
    init() {
        if (this.packets.length === 0) {
            this.showEmptyState();
            return;
        }

        this.bindEvents();
        this.updateDisplay();
    }

    /**
     * Bind event listeners to navigation buttons
     */
    bindEvents() {
        const prevBtn = document.querySelector(this.selectors.prevBtn);
        const nextBtn = document.querySelector(this.selectors.nextBtn);

        if (prevBtn) {
            prevBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.previous();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.next();
            });
        }

        // Handle keyboard navigation
        document.addEventListener("keydown", (e) => {
            if (e.key === "ArrowLeft") {
                e.preventDefault();
                this.previous();
            } else if (e.key === "ArrowRight") {
                e.preventDefault();
                this.next();
            }
        });
    }

    /**
     * Navigate to the next packet
     */
    next() {
        if (this.canGoNext()) {
            this.currentIndex++;
            this.updateDisplay();
        }
    }

    /**
     * Navigate to the previous packet
     */
    previous() {
        if (this.canGoPrevious()) {
            this.currentIndex--;
            this.updateDisplay();
        }
    }

    /**
     * Check if can navigate to next packet
     * @returns {boolean}
     */
    canGoNext() {
        return this.currentIndex < this.packets.length - 1;
    }

    /**
     * Check if can navigate to previous packet
     * @returns {boolean}
     */
    canGoPrevious() {
        return this.currentIndex > 0;
    }

    /**
     * Update the display with current packet information
     */
    updateDisplay() {
        if (this.packets.length === 0) {
            this.showEmptyState();
            return;
        }

        const currentPacket = this.packets[this.currentIndex];

        this.updatePacketImage(currentPacket);
        this.updatePacketTitle(currentPacket);
        this.updateDetailLink(currentPacket);
        this.updateNavigationButtons();
    }

    /**
     * Update packet image with fallback handling
     * @param {Object} packet - Current packet data
     */
    updatePacketImage(packet) {
        const imageElement = document.querySelector(this.selectors.image);
        if (!imageElement) return;

        const imageUrl = packet.image_url || this.defaultImage;

        // Create a new image to test if it loads
        const testImage = new Image();
        testImage.onload = () => {
            imageElement.src = imageUrl;
            imageElement.alt = `${packet.title} - Packet Image`;
        };
        testImage.onerror = () => {
            imageElement.src = this.defaultImage;
            imageElement.alt = "Default Packet Image";
        };
        testImage.src = imageUrl;
    }

    /**
     * Update packet title with overflow handling
     * @param {Object} packet - Current packet data
     */
    updatePacketTitle(packet) {
        const titleElement = document.querySelector(this.selectors.title);
        if (!titleElement) return;

        titleElement.textContent = packet.title || "Untitled Packet";
        titleElement.title = packet.title || "Untitled Packet"; // Tooltip for long titles
    }

    /**
     * Update detail button link
     * @param {Object} packet - Current packet data
     */
    updateDetailLink(packet) {
        const detailBtn = document.querySelector(this.selectors.detailBtn);
        if (!detailBtn) return;

        if (packet.id) {
            detailBtn.href = `/beli-paket/${packet.id}`;
            detailBtn.style.display = "inline-block";
        } else {
            detailBtn.style.display = "none";
        }
    }

    /**
     * Update navigation button states
     */
    updateNavigationButtons() {
        const prevBtn = document.querySelector(this.selectors.prevBtn);
        const nextBtn = document.querySelector(this.selectors.nextBtn);

        if (prevBtn) {
            prevBtn.disabled = !this.canGoPrevious();
            prevBtn.classList.toggle("disabled", !this.canGoPrevious());
        }

        if (nextBtn) {
            nextBtn.disabled = !this.canGoNext();
            nextBtn.classList.toggle("disabled", !this.canGoNext());
        }
    }

    /**
     * Show empty state when no packets are available
     */
    showEmptyState() {
        const container = document.querySelector(this.selectors.container);
        if (!container) return;

        const emptyStateHtml = `
            <div class="empty-state">
                <p>Tidak ada kelas populer tersedia saat ini.</p>
            </div>
        `;

        // Hide navigation elements
        const prevBtn = document.querySelector(this.selectors.prevBtn);
        const nextBtn = document.querySelector(this.selectors.nextBtn);
        const detailBtn = document.querySelector(this.selectors.detailBtn);

        if (prevBtn) prevBtn.style.display = "none";
        if (nextBtn) nextBtn.style.display = "none";
        if (detailBtn) detailBtn.style.display = "none";

        // Show empty state message
        const packetDisplay = container.querySelector("#packet-display");
        if (packetDisplay) {
            packetDisplay.innerHTML = emptyStateHtml;
        }
    }

    /**
     * Go to specific packet by index
     * @param {number} index - Target packet index
     */
    goToPacket(index) {
        if (index >= 0 && index < this.packets.length) {
            this.currentIndex = index;
            this.updateDisplay();
        }
    }

    /**
     * Get current packet data
     * @returns {Object|null}
     */
    getCurrentPacket() {
        return this.packets[this.currentIndex] || null;
    }

    /**
     * Get pagination info
     * @returns {Object}
     */
    getPaginationInfo() {
        return {
            currentIndex: this.currentIndex,
            totalPackets: this.packets.length,
            canGoNext: this.canGoNext(),
            canGoPrevious: this.canGoPrevious(),
            currentPacket: this.getCurrentPacket(),
        };
    }

    /**
     * Update packets data and refresh display
     * @param {Array} newPackets - New packets array
     */
    updatePackets(newPackets) {
        this.packets = newPackets || [];
        this.currentIndex = 0;
        this.updateDisplay();
    }

    /**
     * Destroy the pagination component
     */
    destroy() {
        // Remove event listeners
        const prevBtn = document.querySelector(this.selectors.prevBtn);
        const nextBtn = document.querySelector(this.selectors.nextBtn);

        if (prevBtn) {
            prevBtn.removeEventListener("click", this.previous);
        }
        if (nextBtn) {
            nextBtn.removeEventListener("click", this.next);
        }

        document.removeEventListener("keydown", this.handleKeydown);
    }
}

// Export for use in other modules
export default PopularClassesPagination;

// Also make available globally for direct script usage
window.PopularClassesPagination = PopularClassesPagination;
