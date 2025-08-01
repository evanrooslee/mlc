@extends('layouts.admin-dashboard')

@section('title', 'Pengaturan')

@section('content')
    <div x-data="{
        activeTab: 'paket-belajar',
        tabs: ['paket-belajar', 'banner', 'video-pembelajaran', 'artikel'],
        focusedTabIndex: 0,
        handleKeydown(event) {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                this.focusedTabIndex = this.focusedTabIndex > 0 ? this.focusedTabIndex - 1 : this.tabs.length - 1;
                this.activeTab = this.tabs[this.focusedTabIndex];
                this.$refs['tab-' + this.focusedTabIndex].focus();
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                this.focusedTabIndex = this.focusedTabIndex < this.tabs.length - 1 ? this.focusedTabIndex + 1 : 0;
                this.activeTab = this.tabs[this.focusedTabIndex];
                this.$refs['tab-' + this.focusedTabIndex].focus();
            } else if (event.key === 'Home') {
                event.preventDefault();
                this.focusedTabIndex = 0;
                this.activeTab = this.tabs[0];
                this.$refs['tab-0'].focus();
            } else if (event.key === 'End') {
                event.preventDefault();
                this.focusedTabIndex = this.tabs.length - 1;
                this.activeTab = this.tabs[this.focusedTabIndex];
                this.$refs['tab-' + this.focusedTabIndex].focus();
            }
        },
        setActiveTab(tab, index) {
            this.activeTab = tab;
            this.focusedTabIndex = index;
        }
    }">
        <h2 class="text-xl font-medium mb-6">Atur Konten Landing Page</h2>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex flex-wrap space-x-2 sm:space-x-8" role="tablist" aria-label="Pengaturan konten landing page">
                <button @click="setActiveTab('paket-belajar', 0)" @keydown="handleKeydown" x-ref="tab-0"
                    :class="activeTab === 'paket-belajar' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    :aria-selected="activeTab === 'paket-belajar'" :tabindex="activeTab === 'paket-belajar' ? '0' : '-1'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-['Quicksand',_sans-serif] text-sm sm:text-[16px] font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    role="tab" aria-controls="paket-belajar-panel">
                    Paket Belajar
                </button>
                <button @click="setActiveTab('banner', 1)" @keydown="handleKeydown" x-ref="tab-1"
                    :class="activeTab === 'banner' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    :aria-selected="activeTab === 'banner'" :tabindex="activeTab === 'banner' ? '0' : '-1'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-['Quicksand',_sans-serif] text-sm sm:text-[16px] font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    role="tab" aria-controls="banner-panel">
                    Banner
                </button>
                <button @click="setActiveTab('video-pembelajaran', 2)" @keydown="handleKeydown" x-ref="tab-2"
                    :class="activeTab === 'video-pembelajaran' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    :aria-selected="activeTab === 'video-pembelajaran'"
                    :tabindex="activeTab === 'video-pembelajaran' ? '0' : '-1'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-['Quicksand',_sans-serif] text-sm sm:text-[16px] font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    role="tab" aria-controls="video-pembelajaran-panel">
                    Video Pembelajaran
                </button>
                <button @click="setActiveTab('artikel', 3)" @keydown="handleKeydown" x-ref="tab-3"
                    :class="activeTab === 'artikel' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    :aria-selected="activeTab === 'artikel'" :tabindex="activeTab === 'artikel' ? '0' : '-1'"
                    class="whitespace-nowrap py-2 px-1 border-b-2 font-['Quicksand',_sans-serif] text-sm sm:text-[16px] font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    role="tab" aria-controls="artikel-panel">
                    Artikel
                </button>
            </nav>
        </div>

        <!-- Tab Content Sections -->
        <div class="tab-content">
            <!-- Paket Belajar Tab Content -->
            <div x-show="activeTab === 'paket-belajar'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" role="tabpanel"
                id="paket-belajar-panel" aria-labelledby="tab-0" :aria-hidden="activeTab !== 'paket-belajar'">
                <!-- Dynamic Grid Layout for Paket Belajar -->
                @livewire('admin.components.package-grid-component')
            </div>

            <!-- Banner Tab Content -->
            <div x-show="activeTab === 'banner'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" role="tabpanel" id="banner-panel"
                aria-labelledby="tab-1" :aria-hidden="activeTab !== 'banner'">
                @livewire('admin.components.banner-management-component')
            </div>

            <!-- Video Pembelajaran Tab Content -->
            <div x-show="activeTab === 'video-pembelajaran'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" role="tabpanel"
                id="video-pembelajaran-panel" aria-labelledby="tab-2" :aria-hidden="activeTab !== 'video-pembelajaran'">
                @livewire('admin.components.video-management-component')
            </div>

            <!-- Artikel Tab Content -->
            <div x-show="activeTab === 'artikel'" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" role="tabpanel" id="artikel-panel"
                aria-labelledby="tab-3" :aria-hidden="activeTab !== 'artikel'">
                @livewire('admin.components.article-management-component')
            </div>
        </div>
    </div>
@endsection
