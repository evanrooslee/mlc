{{-- 
    SEO Meta Tags Partial
    
    Usage in pages:
    @section('title', 'Your Page Title')
    @section('meta_description', 'Your page description')
    @section('og_image', asset('images/your-image.jpg'))
    @section('keywords', 'keyword1, keyword2, keyword3')
--}}

{{-- Title --}}
<title>{{ $title ?? 'MLC Online Study - Bimbel Online Matematika & Fisika Terjangkau' }}</title>

{{-- Meta Description --}}
<meta name="description"
    content="{{ $metaDescription ?? 'MLC Online Study menyediakan bimbel online matematika dan fisika untuk SMP & SMA dengan harga terjangkau. Belajar mudah dipahami dengan tutor berpengalaman.' }}">

{{-- Keywords (optional, less important for modern SEO but can help) --}}
@hasSection('keywords')
    <meta name="keywords" content="@yield('keywords')">
@else
    <meta name="keywords"
        content="bimbel online, les online, matematika, fisika, SMP, SMA, belajar online, kursus online, tutor matematika, bimbingan belajar">
@endif

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonical ?? url()->current() }}">

{{-- Open Graph Tags --}}
<meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'MLC Online Study - Bimbel Online Matematika & Fisika') }}">
<meta property="og:description"
    content="{{ $ogDescription ?? ($metaDescription ?? 'Bimbel online matematika dan fisika terjangkau dengan metode pembelajaran yang mudah dipahami.') }}">
<meta property="og:image" content="{{ $ogImage ?? asset('images/mlc-logo-colored.png') }}">
<meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
<meta property="og:type" content="{{ $ogType ?? 'website' }}">
<meta property="og:site_name" content="MLC Online Study">
<meta property="og:locale" content="id_ID">

{{-- Twitter Card Tags --}}
<meta name="twitter:card" content="{{ $twitterCard ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $twitterTitle ?? ($ogTitle ?? ($title ?? 'MLC Online Study')) }}">
<meta name="twitter:description"
    content="{{ $twitterDescription ?? ($ogDescription ?? ($metaDescription ?? 'Bimbel online matematika dan fisika terjangkau.')) }}">
<meta name="twitter:image" content="{{ $twitterImage ?? ($ogImage ?? asset('images/mlc-logo-colored.png')) }}">

{{-- Additional Meta Tags --}}
<meta name="author" content="MLC Online Study">
<meta name="robots" content="{{ $robots ?? 'index, follow' }}">

{{-- Structured Data (JSON-LD) - Organization & WebSite Schema --}}
@if (!isset($noSchema) || !$noSchema)
    @php
        $logoUrl = asset('images/mlc-logo-colored.png');
        $siteUrl = config('app.url');
    @endphp
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "EducationalOrganization",
      "@id": "{{ $siteUrl }}#organization",
      "name": "MLC Online Study",
      "alternateName": "MLC",
      "url": "{{ $siteUrl }}",
      "logo": {
        "@type": "ImageObject",
        "url": "{{ $logoUrl }}",
        "width": 512,
        "height": 512
      },
      "description": "Bimbel online matematika dan fisika untuk SMP & SMA dengan harga terjangkau dan metode pembelajaran yang mudah dipahami.",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+62-816-81-1020",
        "contactType": "Customer Service",
        "email": "mlconlinestudy@gmail.com",
        "availableLanguage": ["Indonesian"]
      },
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "ID"
      },
      "sameAs": [
        "https://www.instagram.com/mlconlinestudy",
        "https://www.tiktok.com/@mlconlinestudy"
      ]
    },
    {
      "@type": "WebSite",
      "@id": "{{ $siteUrl }}#website",
      "url": "{{ $siteUrl }}",
      "name": "MLC Online Study",
      "publisher": {
        "@id": "{{ $siteUrl }}#organization"
      }
    }
  ]
}
</script>
@endif

{{-- Additional page-specific JSON-LD --}}
@hasSection('schema')
    @yield('schema')
@endif
