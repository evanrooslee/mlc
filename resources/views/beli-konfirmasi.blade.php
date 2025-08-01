@extends('layouts.header-only')
@section('title', 'Konfirmasi Pembayaran')
@section('content')

    <body class="bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="font-quicksand font-bold text-2xl text-black mb-8">Pemesanan Berhasil!</h1>

                <div class="flex justify-center mb-8">
                    <img src="{{ asset('images/success-illustration.png') }}" alt="Success Illustration" class="w-80 h-80">
                </div>

                {{-- Countdown Timer --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 max-w-md mx-auto">
                    <p class="font-quicksand text-sm text-yellow-800 mb-2">
                        Anda akan dialihkan ke WhatsApp dalam:
                    </p>
                    <div class="text-2xl font-bold text-yellow-600" id="countdown">5</div>
                    <p class="font-quicksand text-xs text-yellow-700 mt-2">
                        detik
                    </p>
                </div>

                <p class="font-quicksand text-base text-black max-w-md mx-auto mb-10">
                    Silakan lakukan pembayaran melalui transfer bank. Setelah ini, Anda akan dialihkan ke WhatsApp kami
                    untuk menyelesaikan pembayaran.
                </p>

                <a href="{{ route('home') }}"
                    class="inline-block bg-[#01A8DC] text-white font-quicksand font-bold text-base px-10 py-3 rounded-full hover:bg-cyan-600 transition-colors">
                    Kembali
                </a>
            </div>
        </div>

        <script>
            let countdown = 5;
            const countdownElement = document.getElementById('countdown');
            
            const userName = '{{ Auth::user()->name ?? "Customer" }}';
            const whatsappNumber = '6281286939189';
            
            const message = `Halo, saya ${userName}. Saya telah melakukan pemesanan paket belajar. Mohon konfirmasi untuk proses pembayaran selanjutnya. Terima kasih.`;
            
            const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
            
            const timer = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    // Redirect ke WhatsApp
                    window.open(whatsappUrl, '_blank');
                    
                    document.querySelector('.bg-yellow-50').innerHTML = `
                        <p class="font-quicksand text-sm text-green-800">
                            WhatsApp telah dibuka! Jika tidak terbuka otomatis, <a href="${whatsappUrl}" target="_blank" class="text-blue-600 underline">klik di sini</a>.
                        </p>
                    `;
                    document.querySelector('.bg-yellow-50').classList.remove('bg-yellow-50', 'border-yellow-200');
                    document.querySelector('.bg-yellow-50').classList.add('bg-green-50', 'border-green-200');
                }
            }, 1000);
            
            window.addEventListener('beforeunload', () => {
                clearInterval(timer);
            });
        </script>
    </body>
@endsection