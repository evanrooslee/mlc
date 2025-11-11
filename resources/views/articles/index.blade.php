@extends('layouts.app')

@section('content')
    <!-- Articles Listing Page -->
    <section class="py-16 bg-[#FAFAFA]">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold text-center text-[#180746] mb-4">Artikel Bimbel Online</h1>
            <p class="text-center text-gray-600 mb-12 max-w-3xl mx-auto text-lg">
                Tips, trik, dan panduan belajar matematika & fisika untuk siswa SMP dan SMA
            </p>

            @if ($articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($articles as $article)
                        <article
                            class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            @if ($article->image)
                                <a href="{{ route('artikel.show', $article->slug) }}">
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
                                        width="400" height="225" loading="lazy" decoding="async"
                                        class="w-full h-48 object-cover"
                                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                                </a>
                            @endif

                            <div class="p-6">
                                @if ($article->is_starred)
                                    <span
                                        class="inline-block bg-yellow-400 text-white text-xs font-semibold px-3 py-1 rounded-full mb-3">
                                        Artikel Unggulan
                                    </span>
                                @endif

                                <h2 class="text-xl font-bold text-[#180746] mb-3 hover:text-blue-600">
                                    <a href="{{ route('artikel.show', $article->slug) }}">
                                        {{ $article->title }}
                                    </a>
                                </h2>

                                @if ($article->content)
                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        {{ Str::limit(strip_tags($article->content), 150) }}
                                    </p>
                                @endif

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">{{ $article->source }}</span>
                                    <a href="{{ route('artikel.show', $article->slug) }}"
                                        class="text-[#01A8DC] font-semibold hover:underline">
                                        Baca Selengkapnya →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">Belum ada artikel tersedia saat ini.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
