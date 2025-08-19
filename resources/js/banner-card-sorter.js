/**
 * Banner Card Drag & Drop Sorter
 * Provides drag and drop functionality for banner card management
 */

window.bannerCardSorter = function () {
    return {
        dragging: false,
        draggedId: null,
        draggedElement: null,
        placeholder: null,
        dragCounter: 0,

        init() {
            this.setupDragAndDrop();

            // Listen for Livewire updates to reinitialize
            this.$watch("$wire.bannerCards", () => {
                this.$nextTick(() => {
                    this.setupDragAndDrop();
                });
            });
        },

        setupDragAndDrop() {
            const container = this.$refs.sortableContainer;
            if (!container) return;

            // Remove existing event listeners to prevent duplicates
            this.removeEventListeners();

            // Add event listeners to all cards
            this.addCardEventListeners();

            // Add container event listeners
            this.addContainerEventListeners();
        },

        removeEventListeners() {
            const container = this.$refs.sortableContainer;
            if (!container) return;

            const cards = container.querySelectorAll("[data-card-id]");
            cards.forEach((card) => {
                // Clone and replace to remove all event listeners
                const newCard = card.cloneNode(true);
                card.parentNode.replaceChild(newCard, card);
            });
        },

        addCardEventListeners() {
            const cards =
                this.$refs.sortableContainer.querySelectorAll("[data-card-id]");

            cards.forEach((card) => {
                card.draggable = true;

                card.addEventListener("dragstart", (e) =>
                    this.handleDragStart(e, card)
                );
                card.addEventListener("dragend", (e) =>
                    this.handleDragEnd(e, card)
                );
            });
        },

        addContainerEventListeners() {
            const container = this.$refs.sortableContainer;

            container.addEventListener("dragover", (e) =>
                this.handleDragOver(e)
            );
            container.addEventListener("drop", (e) => this.handleDrop(e));
            container.addEventListener("dragenter", (e) =>
                this.handleDragEnter(e)
            );
            container.addEventListener("dragleave", (e) =>
                this.handleDragLeave(e)
            );
        },

        handleDragStart(e, card) {
            this.dragging = true;
            this.draggedId = parseInt(card.dataset.cardId);
            this.draggedElement = card;
            this.dragCounter = 0;

            // Create placeholder
            this.createPlaceholder(card);

            e.dataTransfer.effectAllowed = "move";
            e.dataTransfer.setData("text/html", card.outerHTML);

            // Add visual feedback
            setTimeout(() => {
                if (this.dragging) {
                    card.classList.add("opacity-50", "scale-95");
                }
            }, 0);
        },

        handleDragEnd(e, card) {
            this.dragging = false;
            this.draggedId = null;
            this.dragCounter = 0;

            // Restore original card appearance
            card.classList.remove("opacity-50", "scale-95");

            // Remove placeholder
            this.removePlaceholder();
            this.draggedElement = null;
        },

        handleDragEnter(e) {
            e.preventDefault();
            this.dragCounter++;
        },

        handleDragLeave(e) {
            this.dragCounter--;
        },

        handleDragOver(e) {
            if (!this.dragging) return;

            e.preventDefault();
            e.dataTransfer.dropEffect = "move";

            const container = this.$refs.sortableContainer;
            const afterElement = this.getDragAfterElement(
                container,
                e.clientX,
                e.clientY
            );

            if (this.placeholder) {
                if (afterElement == null) {
                    container.appendChild(this.placeholder);
                } else {
                    container.insertBefore(this.placeholder, afterElement);
                }
            }
        },

        handleDrop(e) {
            if (!this.dragging) return;

            e.preventDefault();

            // Get new order based on placeholder position
            const newOrder = this.getNewCardOrder();

            // Call Livewire method to update order
            if (newOrder.length > 0 && this.hasOrderChanged(newOrder)) {
                this.$wire.call("reorderCards", newOrder);
            }
        },

        createPlaceholder(card) {
            this.placeholder = card.cloneNode(true);
            this.placeholder.classList.add(
                "opacity-50",
                "scale-95",
                "pointer-events-none",
                "bg-blue-50"
            );
            this.placeholder.style.border = "2px dashed #3B82F6";

            // Add placeholder indicator
            const indicator = document.createElement("div");
            indicator.className =
                "absolute inset-0 flex items-center justify-center bg-blue-50 bg-opacity-75";
            indicator.innerHTML = `
                <div class="text-blue-600 font-medium text-sm">
                    <svg class="w-6 h-6 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                    Lepas di sini
                </div>
            `;
            this.placeholder.style.position = "relative";
            this.placeholder.appendChild(indicator);
        },

        removePlaceholder() {
            if (this.placeholder && this.placeholder.parentNode) {
                this.placeholder.parentNode.removeChild(this.placeholder);
            }
            this.placeholder = null;
        },

        getDragAfterElement(container, x, y) {
            const draggableElements = [
                ...container.querySelectorAll(
                    "[data-card-id]:not(.pointer-events-none)"
                ),
            ];

            return draggableElements.reduce(
                (closest, child) => {
                    if (child === this.draggedElement) return closest;

                    const box = child.getBoundingClientRect();

                    // Calculate distance based on grid layout
                    const centerX = box.left + box.width / 2;
                    const centerY = box.top + box.height / 2;

                    const distanceX = x - centerX;
                    const distanceY = y - centerY;
                    const distance = Math.sqrt(
                        distanceX * distanceX + distanceY * distanceY
                    );

                    if (distance < closest.distance) {
                        // Determine if we should insert before or after based on position
                        const insertBefore =
                            distanceY < 0 || (distanceY === 0 && distanceX < 0);
                        return {
                            distance: distance,
                            element: insertBefore
                                ? child
                                : child.nextElementSibling,
                        };
                    } else {
                        return closest;
                    }
                },
                { distance: Number.POSITIVE_INFINITY }
            ).element;
        },

        getNewCardOrder() {
            const container = this.$refs.sortableContainer;
            const cards = container.querySelectorAll(
                "[data-card-id]:not(.pointer-events-none)"
            );

            return Array.from(cards).map((card) =>
                parseInt(card.dataset.cardId)
            );
        },

        hasOrderChanged(newOrder) {
            const currentOrder = this.$wire.bannerCards.map((card) => card.id);

            if (newOrder.length !== currentOrder.length) return true;

            return newOrder.some((id, index) => id !== currentOrder[index]);
        },
    };
};

// Auto-reinitialize when Livewire updates
document.addEventListener("livewire:navigated", () => {
    setTimeout(() => {
        const sorterElements = document.querySelectorAll(
            '[x-data*="bannerCardSorter"]'
        );
        sorterElements.forEach((element) => {
            if (
                element._x_dataStack &&
                element._x_dataStack[0].setupDragAndDrop
            ) {
                element._x_dataStack[0].setupDragAndDrop();
            }
        });
    }, 100);
});
