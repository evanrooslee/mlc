<div>
    @isset($jsPath)
        <script>{!! file_get_contents($jsPath) !!}</script>
    @endisset
    @isset($cssPath)
        <style>{!! file_get_contents($cssPath) !!}</style>
    @endisset

    <div
            x-data="LivewireUIModal()"
            x-on:close.stop="setShowPropertyTo(false)"
            x-on:keydown.escape.window="show && closeModalOnEscape()"
            x-show="show"
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
    >
        <div class="flex items-center justify-center min-h-dvh px-4 pt-4 pb-10 text-center sm:block sm:p-0">
            <!-- Modal backdrop -->
            <div
                    x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-all transform"
            >
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                    x-show="show && showActiveComponent"
                    x-transition:enter="transition ease-in-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in-out duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    x-bind:class="modalWidth"
                    class="inline-block w-full align-bottom bg-white rounded text-left overflow-auto shadow-lg transform transition-all max-w-lg mx-auto sm:my-8 sm:align-middle sm:w-full relative z-10"
                    id="modal-container"
                    x-trap.noscroll.inert="show && showActiveComponent"
                    aria-modal="true"
                    @keydown.escape.window="closeModalOnEscape()"
            >
                @forelse($components as $id => $component)
                    <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                        @livewire($component['name'], $component['arguments'], key($id))
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
