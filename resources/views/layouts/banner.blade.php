{{-- Banner Component - Full width image banner --}}
@if ($banner && $banner->image)
    <div class="w-full h-16 md:h-20 lg:h-24 overflow-hidden bg-gradient-to-r from-slate-800 to-slate-900">
        <div class="relative w-full h-full">
            {{-- Banner Image from Database --}}
            <img src="{{ asset($banner->image) }}" alt="Banner"
                class="w-full h-full object-cover object-center">
        </div>
    </div>
@endif
