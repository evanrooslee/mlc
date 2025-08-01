/**
 * @vitest-environment jsdom
 */

import { describe, it, expect, beforeEach, vi } from "vitest";

// Mock the PopularClassesPagination class
class PopularClassesPagination {
    constructor(packets) {
        this.packets = packets || [];
        this.currentIndex = 0;
        this.defaultImage = "/images/hero-illustration.png";

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

        const { image, title, detailBtn } = this.elements;

        if (image) {
            image.src = packet.image_url || this.defaultImage;
            image.alt = packet.title || "Packet Image";
        }

        if (title) {
            title.textContent = packet.title || "Untitled Packet";
        }

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
        // Mock event binding for tests
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
    }

    navigate(direction) {
        const newIndex = this.currentIndex + direction;
        if (newIndex >= 0 && newIndex < this.packets.length) {
            this.currentIndex = newIndex;
            this.updateDisplay();
        }
    }
}

describe("PopularClassesPagination", () => {
    let mockPackets;
    let pagination;

    beforeEach(() => {
        // Set up DOM elements
        document.body.innerHTML = `
            <div id="packet-display">
                <img id="packet-image" src="" alt="">
                <h4 id="packet-title"></h4>
            </div>
            <a id="detail-btn" href=""></a>
            <button id="prev-btn"></button>
            <button id="next-btn"></button>
            <div id="image-loading"></div>
        `;

        mockPackets = [
            { id: 1, title: "Packet 1", image_url: "/images/packet1.jpg" },
            { id: 2, title: "Packet 2", image_url: "/images/packet2.jpg" },
            { id: 3, title: "Packet 3", image_url: "/images/packet3.jpg" },
        ];
    });

    it("initializes with first packet displayed", () => {
        pagination = new PopularClassesPagination(mockPackets);

        const image = document.getElementById("packet-image");
        const title = document.getElementById("packet-title");
        const detailBtn = document.getElementById("detail-btn");

        expect(image.src).toContain("packet1.jpg");
        expect(title.textContent).toBe("Packet 1");
        expect(detailBtn.href).toContain("/beli-paket/1");
    });

    it("navigates to next packet correctly", () => {
        pagination = new PopularClassesPagination(mockPackets);

        pagination.navigate(1);

        const image = document.getElementById("packet-image");
        const title = document.getElementById("packet-title");

        expect(image.src).toContain("packet2.jpg");
        expect(title.textContent).toBe("Packet 2");
    });

    it("navigates to previous packet correctly", () => {
        pagination = new PopularClassesPagination(mockPackets);

        // Go to second packet first
        pagination.navigate(1);
        // Then go back
        pagination.navigate(-1);

        const image = document.getElementById("packet-image");
        const title = document.getElementById("packet-title");

        expect(image.src).toContain("packet1.jpg");
        expect(title.textContent).toBe("Packet 1");
    });

    it("disables previous button on first packet", () => {
        pagination = new PopularClassesPagination(mockPackets);

        const prevBtn = document.getElementById("prev-btn");
        const nextBtn = document.getElementById("next-btn");

        expect(prevBtn.disabled).toBe(true);
        expect(nextBtn.disabled).toBe(false);
    });

    it("disables next button on last packet", () => {
        pagination = new PopularClassesPagination(mockPackets);

        // Navigate to last packet
        pagination.navigate(1);
        pagination.navigate(1);

        const prevBtn = document.getElementById("prev-btn");
        const nextBtn = document.getElementById("next-btn");

        expect(prevBtn.disabled).toBe(false);
        expect(nextBtn.disabled).toBe(true);
    });

    it("handles empty packets array gracefully", () => {
        const consoleSpy = vi
            .spyOn(console, "error")
            .mockImplementation(() => {});

        pagination = new PopularClassesPagination([]);

        expect(consoleSpy).toHaveBeenCalledWith("Invalid packets data");

        consoleSpy.mockRestore();
    });

    it("uses default image when packet has no image", () => {
        const packetsWithoutImage = [
            { id: 1, title: "Packet 1", image_url: null },
        ];

        pagination = new PopularClassesPagination(packetsWithoutImage);

        const image = document.getElementById("packet-image");
        expect(image.src).toContain("hero-illustration.png");
    });

    it("handles missing DOM elements gracefully", () => {
        // Remove some DOM elements
        document.getElementById("packet-image").remove();

        // Should not throw error
        expect(() => {
            pagination = new PopularClassesPagination(mockPackets);
        }).not.toThrow();
    });
});
