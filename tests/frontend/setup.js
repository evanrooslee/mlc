// Test setup for frontend tests
import { beforeEach } from "vitest";

// Mock DOM elements that might be needed
beforeEach(() => {
    // Reset DOM
    document.body.innerHTML = "";

    // Mock Image constructor for image loading tests
    global.Image = class {
        constructor() {
            setTimeout(() => {
                if (this.onload) this.onload();
            }, 0);
        }
    };
});
