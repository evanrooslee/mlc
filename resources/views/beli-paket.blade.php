@extends('layouts.header-only')
@section('title', 'Beli Paket')
@section('content')

    <body class="bg-cyan-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Back Button --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8">
                <div class="bg-[#01a8dc] p-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-left">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                </div>
                <span class="font-quicksand font-medium text-xl">Kembali</span>
            </a>

            <form action="{{ route('proses-pembayaran')}}" method="POST">
                @csrf
                <div class="flex flex-col lg:flex-row justify-center gap-16">
                    {{-- Left Column: User Info Display (Read Only) --}}
                    <div class="bg-white p-6 rounded-lg shadow-md w-full lg:w-2/5 xl:w-1/3">
                        <h2 class="font-quicksand font-bold text-xl mb-7">Informasi Pembeli</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block font-quicksand font-medium text-base mb-2">Nama Siswa</label>
                                <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                                    <span class="font-quicksand text-gray-700">{{ Auth::user()->name ?? 'Nama Siswa' }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block font-quicksand font-medium text-base mb-2">Nomor HP Siswa</label>
                                <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                                    <span class="font-quicksand text-gray-700">{{ Auth::user()->phone_number ?? 'Nomor HP tidak tersedia' }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block font-quicksand font-medium text-base mb-2">Nama Ayah/Ibu</label>
                                <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                                    @php
                                        $firstName = explode(' ', Auth::user()->name ?? '')[0];
                                        $parentName = $firstName ? 'Mr/Mrs ' . $firstName : 'Nama Orang Tua tidak tersedia';
                                    @endphp
                                    <span class="font-quicksand text-gray-700">{{ $parentName }}</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block font-quicksand font-medium text-base mb-2">Nomor HP Ayah/Ibu</label>
                                <div class="bg-gray-50 border border-gray-300 rounded-md p-3">
                                    <span class="font-quicksand text-gray-700">{{ Auth::user()->parents_phone_number ?? 'Nomor HP Orang Tua tidak tersedia' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden inputs untuk form submission --}}
                        <input type="hidden" name="student_name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="student_phone" value="{{ Auth::user()->phone_number }}">
                        <input type="hidden" name="parent_name" value="@php echo Auth::user()->name ? 'Mr/Mrs ' . explode(' ', Auth::user()->name)[0] : ''; @endphp">
                        <input type="hidden" name="parent_phone" value="{{ Auth::user()->parents_phone_number }}">

                        <h2 class="font-quicksand font-medium text-base mt-6 mb-2">Kode Diskon</h2>

                        {{-- Discount Input Form tetap bisa digunakan --}}
                        <div class="mb-4">
                            <div class="flex gap-2">
                                <input type="text" id="discount_code" name="discount_code" value="{{ old('discount_code') }}"
                                    class="flex-1 border border-gray-400 rounded-md shadow-sm p-2 focus:ring-cyan-500 focus:border-cyan-500 font-quicksand"
                                    placeholder="Masukkan kode diskon">
                                <button type="button" onclick="applyDiscount()" 
                                    class="bg-[#01a8dc] text-white px-4 py-2 rounded-md hover:bg-cyan-600 font-quicksand font-medium">
                                    Terapkan
                                </button>
                            </div>
                            
                            {{-- Error message --}}
                            <div id="discount-error" class="text-red-500 text-sm mt-1 hidden font-quicksand"></div>
                            
                            {{-- Success message --}}
                            <div id="discount-success" class="text-green-500 text-sm mt-1 hidden font-quicksand"></div>
                        </div>
                    </div>

                    {{-- Right Column: Packet Details --}}
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full lg:w-2/5 xl:w-1/3 self-start">
                        <h2 class="font-quicksand font-bold text-xl text-center mb-6">Detail Pemesanan</h2>

                        <h3 class="font-quicksand font-bold text-base mb-4">{{ $packet->title }}</h3>
                        
                        {{-- Display packet benefits --}}
                        <ul class="space-y-3 mb-6">
                            @foreach ($benefits as $benefit)
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-cyan-500 shrink-0" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-quicksand text-sm">{{ trim($benefit) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Package info --}}
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex flex-wrap gap-2">
                                @if ($packet->type === 'premium')
                                    <span class="px-2 py-1 bg-yellow-400 text-white text-xs rounded-full font-quicksand font-bold">Premium</span>
                                @endif
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-quicksand font-bold">
                                    Kelas {{ $packet->grade }}
                                </span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-quicksand font-bold">
                                    {{ $packet->subject }}
                                </span>
                            </div>
                        </div>

                        <ul class="list-disc list-inside text-sm text-gray-600 font-quicksand space-y-1 mb-6">
                            <li>Setelah membuat pesanan, anda akan diarahkan ke WhatsApp</li>
                            <li>Pembayaran akan diselesaikan di WhatsApp via transfer bank</li>
                        </ul>

                        <div class="space-y-2 pt-4">
                            <div class="flex justify-between font-quicksand text-base">
                                <span class="text-gray-600">Harga Kelas</span>
                                <span id="original-price">Rp{{ number_format($originalPrice, 0, ',', '.') }}</span>
                            </div>
                            
                            <div id="discount-row" class="flex justify-between font-quicksand text-base {{ $discountPercentage > 0 ? '' : 'hidden' }}">
                                <span class="text-red-500">Diskon (<span id="discount-percentage">{{ $discountPercentage }}</span>%)</span>
                                <span class="text-red-500" id="discount-amount">-Rp{{ number_format($discountAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="border-t mt-4 pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="font-quicksand font-medium text-base">Total</span>
                                <span class="font-quicksand font-bold text-xl" id="final-price">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                            </div>
                            <button type="submit"
                                class="w-full bg-[#01a8dc] text-white font-quicksand font-bold py-3 rounded-full hover:bg-cyan-600 transition-colors">
                                Buat Pesanan
                            </button>
                        </div>

                        <input type="hidden" name="packet_id" value="{{ $packet->id }}">
                        <input type="hidden" name="final_price" id="final-price-input" value="{{ $finalPrice }}">
                        <input type="hidden" name="applied_discount_code" id="applied-discount-code" value="">
                        <input type="hidden" name="discount_percentage" id="discount-percentage-input" value="{{ $discountPercentage }}">
                    </div>
                </div>
            </form>
        </div>

        <script>
        const originalPrice = {{ $originalPrice }};
        let currentDiscount = {{ $discountPercentage }};
        let appliedDiscountCode = '';

        async function applyDiscount() {
            const discountInput = document.getElementById('discount_code');
            const discountCode = discountInput.value.toUpperCase().trim();
            const errorDiv = document.getElementById('discount-error');
            const successDiv = document.getElementById('discount-success');
            
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            
            if (!discountCode) {
                showError('Silakan masukkan kode diskon');
                return;
            }
            
            try {
                const response = await fetch('{{ route("validate-discount") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        discount_code: discountCode
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    currentDiscount = result.discount.percentage;
                    appliedDiscountCode = result.discount.code;
                    
                    updatePricing();
                    showSuccess(result.message);
                } else {
                    showError(result.message);
                }
            } catch (error) {
                showError('Terjadi kesalahan. Silakan coba lagi.');
                console.error('Error:', error);
            }
        }

        function updatePricing() {
            const discountAmount = (originalPrice * currentDiscount) / 100;
            const finalPrice = originalPrice - discountAmount;
            
            document.getElementById('discount-percentage').textContent = currentDiscount;
            document.getElementById('discount-amount').textContent = '-Rp' + formatNumber(discountAmount);
            document.getElementById('final-price').textContent = 'Rp' + formatNumber(finalPrice);
            
            document.getElementById('final-price-input').value = finalPrice;
            document.getElementById('applied-discount-code').value = appliedDiscountCode;
            document.getElementById('discount-percentage-input').value = currentDiscount;
            
            const discountRow = document.getElementById('discount-row');
            if (currentDiscount > 0) {
                discountRow.classList.remove('hidden');
            } else {
                discountRow.classList.add('hidden');
            }
        }

        function showError(message) {
            const errorDiv = document.getElementById('discount-error');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function showSuccess(message) {
            const successDiv = document.getElementById('discount-success');
            successDiv.textContent = message;
            successDiv.classList.remove('hidden');
        }

        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        document.getElementById('discount_code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyDiscount();
            }
        });

        function clearDiscount() {
            currentDiscount = 0;
            appliedDiscountCode = '';
            document.getElementById('discount_code').value = '';
            updatePricing();
            
            document.getElementById('discount-error').classList.add('hidden');
            document.getElementById('discount-success').classList.add('hidden');
        }

        async function loadAvailableDiscounts() {
            try {
                const response = await fetch('{{ route("get-available-discounts") }}');
                const discounts = await response.json();
                
                updateDiscountDisplay(discounts);
            } catch (error) {
                console.error('Error loading discounts:', error);
            }
        }

        function updateDiscountDisplay(discounts) {
            const container = document.querySelector('.grid.grid-cols-2.gap-2.text-xs');
            container.innerHTML = '';
            
            discounts.forEach(discount => {
                if (discount.is_valid) {
                    const discountEl = document.createElement('div');
                    discountEl.className = 'bg-white p-2 rounded border cursor-pointer hover:bg-gray-50';
                    discountEl.innerHTML = `
                        <span class="font-bold text-blue-600">${discount.code}</span>
                        <span class="text-gray-600">- ${discount.percentage}%</span>
                    `;
                    discountEl.onclick = () => {
                        document.getElementById('discount_code').value = discount.code;
                    };
                    container.appendChild(discountEl);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', loadAvailableDiscounts);
        </script>
    </body>
@endsection