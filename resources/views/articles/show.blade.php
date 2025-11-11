@extends('layouts.app')

@section('content')
    <!-- Article Detail Page -->
    <article class="py-16 bg-[#FAFAFA]">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Breadcrumb -->
            <nav class="mb-8 text-sm">
                <ol class="flex items-center space-x-2 text-gray-600">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-600">Beranda</a></li>
                    <li>/</li>
                    <li><a href="{{ route('artikel.index') }}" class="hover:text-blue-600">Artikel</a></li>
                    <li>/</li>
                    <li class="text-gray-800">{{ Str::limit($article->title, 50) }}</li>
                </ol>
            </nav>

            <!-- Article Header -->
            <header class="mb-8">
                @if ($article->is_starred)
                    <span class="inline-block bg-yellow-400 text-white text-xs font-semibold px-3 py-1 rounded-full mb-3">
                        Artikel Unggulan
                    </span>
                @endif

                <h1 class="text-4xl font-bold text-[#180746] mb-4">{{ $article->title }}</h1>

                <div class="flex items-center text-gray-600 text-sm space-x-4">
                    <span>Sumber: {{ $article->source }}</span>
                    <span>•</span>
                    <time datetime="{{ $article->created_at->toISOString() }}">
                        {{ $article->created_at->format('d F Y') }}
                    </time>
                </div>
            </header>

            <!-- Featured Image -->
            @if ($article->image)
                <figure class="mb-8">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" width="896"
                        height="504" loading="eager" decoding="async" class="w-full h-auto rounded-lg shadow-lg"
                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                </figure>
            @endif

            <!-- Article Content -->
            <div class="prose prose-lg max-w-none mb-12">
                @if ($article->content)
                    {!! nl2br(e($article->content)) !!}
                @else
                    <p class="text-gray-600 mb-4">
                        Artikel ini tersedia di sumber aslinya. Klik tombol di bawah untuk membaca selengkapnya.
                    </p>
                @endif
            </div>

            <!-- External Link (if exists) -->
            @if ($article->url)
                <div class="mb-12 p-6 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-gray-700 mb-3">Baca artikel lengkap di sumber aslinya:</p>
                    <a href="{{ $article->url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-block bg-[#01A8DC] text-white font-semibold px-6 py-3 rounded-full hover:bg-blue-700 transition-colors">
                        Buka Artikel Asli →
                    </a>
                </div>
            @endif

            <!-- Related Articles -->
            @if ($relatedArticles->count() > 0)
                <section class="mt-16 pt-16 border-t border-gray-200">
                    <h2 class="text-2xl font-bold text-[#180746] mb-8">Artikel Terkait</h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedArticles as $related)
                            <article
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                @if ($related->image)
                                    <a href="{{ route('artikel.show', $related->slug) }}">
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}"
                                            width="320" height="180" loading="lazy" decoding="async"
                                            class="w-full h-40 object-cover"
                                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                                    </a>
                                @endif

                                <div class="p-4">
                                    <h3 class="font-bold text-[#180746] mb-2 hover:text-blue-600">
                                        <a href="{{ route('artikel.show', $related->slug) }}">
                                            {{ Str::limit($related->title, 60) }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-gray-500">{{ $related->source }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Back to Articles -->
            <div class="mt-12 text-center">
                <a href="{{ route('artikel.index') }}" class="inline-block text-[#01A8DC] font-semibold hover:underline">
                    ← Kembali ke Daftar Artikel
                </a>
            </div>
        </div>
    </article>

    <!-- Structured Data for Article -->
@section('schema')
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Article",
          "headline": "{{ $article->title }}",
          "image": "{{ $article->image ? asset('storage/' . $article->image) : asset('images/mlc-logo-colored.png') }}",
          "datePublished": "{{ $article->created_at->toISOString() }}",
          "dateModified": "{{ $article->updated_at->toISOString() }}",
          "author": {
            "@type": "Organization",
            "name": "{{ $article->source }}"
          },
          "publisher": {
            "@type": "EducationalOrganization",
            "name": "MLC Online Study",
            "logo": {
              "@type": "ImageObject",
              "url": "{{ asset('images/mlc-logo-colored.png') }}"
            }
          },
          "description": "{{ Str::limit(strip_tags($article->content ?? ''), 155) }}"
        }
        </script>

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Beranda",
              "item": "{{ route('home') }}"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "Artikel",
              "item": "{{ route('artikel.index') }}"
            },
            {
              "@type": "ListItem",
              "position": 3,
              "name": "{{ $article->title }}",
              "item": "{{ route('artikel.show', $article->slug) }}"
            }
          ]
        }
        </script>
@endsection
@endsection
