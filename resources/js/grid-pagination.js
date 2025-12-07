class GridPagination {
    constructor(
        items,
        containerId,
        prevBtnId,
        nextBtnId,
        pageIndicatorId,
        itemsPerPage = 5
    ) {
        this.items = items || [];
        this.container = document.getElementById(containerId);
        this.prevBtn = document.getElementById(prevBtnId);
        this.nextBtn = document.getElementById(nextBtnId);
        this.pageIndicator = document.getElementById(pageIndicatorId);
        this.itemsPerPage = itemsPerPage;
        this.currentPage = 1;
        this.totalPages = Math.ceil(this.items.length / this.itemsPerPage);

        if (!this.container) {
            console.error(`Container with ID ${containerId} not found`);
            return;
        }

        this.init();
    }

    init() {
        this.render();
        this.updateControls();
        this.bindEvents();
    }

    render() {
        this.container.innerHTML = "";
        const start = (this.currentPage - 1) * this.itemsPerPage;
        const end = start + this.itemsPerPage;
        const pageItems = this.items.slice(start, end);

        pageItems.forEach((item) => {
            const card = this.createCard(item);
            this.container.appendChild(card);
        });

        if (this.pageIndicator) {
            this.pageIndicator.textContent = `${this.currentPage} of ${this.totalPages}`;
        }
    }

    createCard(item) {
        const limitString = (str, limit = 50) => {
            return str.length > limit ? str.substring(0, limit) + "..." : str;
        };

        const div = document.createElement("div");
        div.className = "bg-white rounded-lg shadow-md overflow-hidden";
        div.innerHTML = `
            <img src="${item.image_url}" alt="${item.title}" class="w-full h-40 object-cover">
            <div class="p-2">
                <h3 class="font-medium text-left">${limitString(item.title, 40)}</h3>
            </div>
        `;
        return div;
    }

    updateControls() {
        if (this.prevBtn) {
            this.prevBtn.disabled = this.currentPage === 1;
            this.prevBtn.style.opacity = this.currentPage === 1 ? "0.5" : "1";
            this.prevBtn.style.cursor =
                this.currentPage === 1 ? "not-allowed" : "pointer";
        }

        if (this.nextBtn) {
            this.nextBtn.disabled = this.currentPage === this.totalPages;
            this.nextBtn.style.opacity =
                this.currentPage === this.totalPages ? "0.5" : "1";
            this.nextBtn.style.cursor =
                this.currentPage === this.totalPages
                    ? "not-allowed"
                    : "pointer";
        }
    }

    bindEvents() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener("click", (e) => {
                e.preventDefault();
                if (this.currentPage > 1) {
                    this.currentPage--;
                    this.render();
                    this.updateControls();
                }
            });
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener("click", (e) => {
                e.preventDefault();
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                    this.render();
                    this.updateControls();
                }
            });
        }
    }
}

window.GridPagination = GridPagination;
