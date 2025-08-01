# PopularClassesPagination Component

## Overview

The `PopularClassesPagination` component provides single-packet navigation for the popular classes section, allowing users to browse through packets one at a time with previous/next controls.

## Features

-   Display exactly 1 packet per page
-   Previous/Next navigation controls
-   Automatic boundary checks (disable buttons at first/last packet)
-   Keyboard navigation support (Arrow keys)
-   Image loading with fallback handling
-   Empty state handling
-   No page reload navigation

## Usage

### Basic Usage

```javascript
// Initialize with packets data
const packets = [
    { id: 1, title: "Packet 1", image_url: "/images/packet1.jpg" },
    { id: 2, title: "Packet 2", image_url: "/images/packet2.jpg" },
    { id: 3, title: "Packet 3", image_url: "/images/packet3.jpg" },
];

const pagination = new PopularClassesPagination(packets);
```

### Custom Configuration

```javascript
const pagination = new PopularClassesPagination(packets, {
    container: "#my-container",
    image: "#my-image",
    title: "#my-title",
    detailBtn: "#my-detail-btn",
    prevBtn: "#my-prev-btn",
    nextBtn: "#my-next-btn",
    defaultImage: "/images/my-default.png",
});
```

## Required HTML Structure

```html
<div id="popular-classes-container">
    <div id="packet-display">
        <img id="packet-image" src="" alt="" />
        <h4 id="packet-title"></h4>
    </div>
    <div class="pagination-controls">
        <button id="prev-btn">←</button>
        <button id="next-btn">→</button>
    </div>
    <a id="detail-btn" href="">Lihat Detail</a>
</div>
```

## API Methods

### Navigation

-   `next()` - Navigate to next packet
-   `previous()` - Navigate to previous packet
-   `goToPacket(index)` - Go to specific packet by index

### State Queries

-   `canGoNext()` - Check if can navigate forward
-   `canGoPrevious()` - Check if can navigate backward
-   `getCurrentPacket()` - Get current packet data
-   `getPaginationInfo()` - Get complete pagination state

### Data Management

-   `updatePackets(newPackets)` - Update packets array and reset to first
-   `updateDisplay()` - Refresh the display with current packet

### Lifecycle

-   `destroy()` - Clean up event listeners

## Events

The component automatically handles:

-   Click events on navigation buttons
-   Keyboard navigation (Arrow Left/Right)
-   Image loading with fallback

## Requirements Compliance

-   ✅ 2.1: Display exactly 1 packet per page
-   ✅ 2.2: Provide pagination controls (previous/next arrows)
-   ✅ 2.3: Disable previous arrow on first packet
-   ✅ 2.4: Disable next arrow on last packet
-   ✅ 2.5: Navigate without page reload
