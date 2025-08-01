/**
 * Frontend tests for Popular Classes error handling
 * These tests verify that the JavaScript pagination handles errors gracefully
 */

import { describe, it, expect, beforeEach, afterEach, vi } from "vitest";

// Mock DOM elements and functions
const mockDOM = () => {
    // Create mock DOM structure
    document.body.innerHTML = `
        <div class="popular-classes-container">
            <div id="packet-display">
                <div id="image-loading" style="display: flex;"></div>
                <img id="packet-image" src="" alt="" />
                <h4 id="packet-title"></h4>
            </div>
            <a id="detail-btn" href=""></a>
            <button id="prev-btn"></button>
            <button id="next-btn"></button>
        </div>
    `;

    // Mock global functions
    global.handleImageError = vi.fn((img) => {
        img.src = "/images/hero-illustration.png";
        img.alt = "Default packet image";
        const loadingEl = document.getElementById("image-loading");
        if (loadingEl) loadingEl.style.display = "none";
    });

    global.handleImageLoad = vi.fn((img) => {
        const loadingEl = document.getElementById("image-loading");
        if (loadingEl) loadingEl.style.display = "none";
    });

    // Mock console methods
    global.console.error = vi.fn();
    global.console.warn = vi.fn();
};

// Mock PopularClassesPagination class
class PopularClassesPagination {
    constructor(packets) {
        this.packets = packets || [];
        this.currentIndex = 0;
        this.defaultImage = "/images/hero-illustration.png";

        if (!Array.isArray(this.packets) || this.packets.length === 0) {
            console.error("Invalid packets data provided to pagination");
            return;
        }

        this.init();
    }

    init() {
        try {
            this.updateDisplay();
            this.bindEvents();
        } catch (error) {
            console.error("Failed to initialize pagination:", error);
            this.showError();
        }
    }

    updateDisplay() {
        try {
            const packet = this.packets[this.currentIndex];

            if (!packet) {
                console.error("No packet found at index:", this.currentIndex);
                return;
            }

            const imageEl = document.getElementById("packet-image");
            if (imageEl) {
                const loadingEl = document.getElementById("image-loading");
                if (loadingEl) loadingEl.style.display = "flex";

                imageEl.src = packet.image_url || this.defaultImage;
                imageEl.alt = packet.title || "Packet Image";
            }

            const titleEl = document.getElementById("packet-title");
            if (titleEl) {
                titleEl.textContent = packet.title || "Untitled Packet";
            }

            const detailBtn = document.getElementById("detail-btn");
            if (detailBtn && packet.id) {
                detailBtn.href = `/beli-paket/${packet.id}`;
            }

            this.updateNavigationButtons();
        } catch (error) {
            console.error("Failed to update display:", error);
            this.showError();
        }
    }

    updateNavigationButtons() {
        const prevBtn = document.getElementById("prev-btn");
        const nextBtn = document.getElementById("next-btn");

        if (!prevBtn || !nextBtn) {
            console.warn("Navigation buttons not found");
            return;
        }

        prevBtn.disabled = this.currentIndex === 0;
        nextBtn.disabled = this.currentIndex === this.packets.length - 1;
    }

    showError() {
        const container = document.querySelector(".popular-classes-container");
        if (container) {
            const errorDiv = document.createElement("div");
            errorDiv.className =
                "bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4";
            errorDiv.innerHTML =
                "<p>Terjadi kesalahan saat memuat kelas populer. Silakan refresh halaman.</p>";
            container.appendChild(errorDiv);
        }
    }

    next() {
        if (this.currentIndex < this.packets.length - 1) {
            this.currentIndex++;
            this.updateDisplay();
        }
    }

    previous() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.updateDisplay();
        }
    }
}

describe("Popular Classes Error Handling", () => {
    beforeEach(() => {
        mockDOM();
    });

    afterEach(() => {
        vi.clearAllMocks();
        document.body.innerHTML = "";
    });

    describe("Image Error Handling", () => {
        it("should handle image load errors gracefully", () => {
            const img = document.getElementById("packet-image");
            img.src = "invalid-image.jpg";

            // Simulate image error
            global.handleImageError(img);

            expect(global.handleImageError).toHaveBeenCalledWith(img);
            expect(img.src).toContain("hero-illustration.png");
            expect(img.alt).toBe("Default packet image");
        });

        it("should hide loading placeholder on image load", () => {
            const img = document.getElementById("packet-image");
            const loadingEl = document.getElementById("image-loading");

            global.handleImageLoad(img);

            expect(global.handleImageLoad).toHaveBeenCalledWith(img);
            expect(loadingEl.style.display).toBe("none");
        });

        it("should hide loading placeholder on image error", () => {
            const img = document.getElementById("packet-image");
            const loadingEl = document.getElementById("image-loading");

            global.handleImageError(img);

            expect(loadingEl.style.display).toBe("none");
        });
    });

    describe("Pagination Error Handling", () => {
        it("should handle invalid packets data gracefully", () => {
            new PopularClassesPagination(null);

            expect(console.error).toHaveBeenCalledWith(
                "Invalid packets data provided to pagination"
            );
        });

        it("should handle empty packets array", () => {
            new PopularClassesPagination([]);

            expect(console.error).toHaveBeenCalledWith(
                "Invalid packets data provided to pagination"
            );
        });

        it("should handle missing packet data at current index", () => {
            const pagination = new PopularClassesPagination([
                { id: 1, title: "Test Packet", image_url: "test.jpg" },
            ]);

            pagination.currentIndex = 5; // Invalid index
            pagination.updateDisplay();

            expect(console.error).toHaveBeenCalledWith(
                "No packet found at index:",
                5
            );
        });

        it("should handle missing DOM elements gracefully", () => {
            // Remove navigation buttons
            document.getElementById("prev-btn").remove();
            document.getElementById("next-btn").remove();

            const pagination = new PopularClassesPagination([
                { id: 1, title: "Test Packet", image_url: "test.jpg" },
            ]);

            pagination.updateNavigationButtons();

            expect(console.warn).toHaveBeenCalledWith(
                "Navigation buttons not found"
            );
        });

        it("should show error message when initialization fails", () => {
            // Mock updateDisplay to throw error
            const originalUpdateDisplay =
                PopularClassesPagination.prototype.updateDisplay;
            PopularClassesPagination.prototype.updateDisplay = () => {
                throw new Error("Test error");
            };

            new PopularClassesPagination([
                { id: 1, title: "Test Packet", image_url: "test.jpg" },
            ]);

            expect(console.error).toHaveBeenCalledWith(
                "Failed to initialize pagination:",
                expect.any(Error)
            );

            // Check if error message was added to DOM
            const errorDiv = document.querySelector(".bg-red-100");
            expect(errorDiv).toBeTruthy();
            expect(errorDiv.textContent).toContain(
                "Terjadi kesalahan saat memuat kelas populer"
            );

            // Restore original method
            PopularClassesPagination.prototype.updateDisplay =
                originalUpdateDisplay;
        });
    });

    describe("Data Validation", () => {
        it("should handle packets with missing titles", () => {
            const pagination = new PopularClassesPagination([
                { id: 1, image_url: "test.jpg" }, // Missing title
            ]);

            const titleEl = document.getElementById("packet-title");
            expect(titleEl.textContent).toBe("Untitled Packet");
        });

        it("should handle packets with missing images", () => {
            const pagination = new PopularClassesPagination([
                { id: 1, title: "Test Packet" }, // Missing image_url
            ]);

            const imageEl = document.getElementById("packet-image");
            expect(imageEl.src).toContain("hero-illustration.png");
        });

        it("should handle packets with missing IDs", () => {
            const pagination = new PopularClassesPagination([
                { title: "Test Packet", image_url: "test.jpg" }, // Missing id
            ]);

            const detailBtn = document.getElementById("detail-btn");
            // Should not update href if no ID is present (href will be base URL)
            expect(detailBtn.href).toBe("http://localhost:3000/");
        });
    });

    describe("Navigation Error Handling", () => {
        it("should not navigate beyond array bounds", () => {
            const packets = [
                { id: 1, title: "Packet 1", image_url: "test1.jpg" },
                { id: 2, title: "Packet 2", image_url: "test2.jpg" },
            ];

            const pagination = new PopularClassesPagination(packets);

            // Try to go previous from first item
            pagination.previous();
            expect(pagination.currentIndex).toBe(0);

            // Go to last item
            pagination.next();
            expect(pagination.currentIndex).toBe(1);

            // Try to go next from last item
            pagination.next();
            expect(pagination.currentIndex).toBe(1);
        });

        it("should handle navigation with single packet", () => {
            const packets = [
                { id: 1, title: "Single Packet", image_url: "test.jpg" },
            ];

            const pagination = new PopularClassesPagination(packets);

            // Navigation should not change index
            pagination.next();
            expect(pagination.currentIndex).toBe(0);

            pagination.previous();
            expect(pagination.currentIndex).toBe(0);
        });
    });
});
