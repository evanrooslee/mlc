# SEO Improvement Tasks for MLC

This checklist is tailored to this Laravel (v12), Livewire, Tailwind, Vite project. Each task includes where to change it and the acceptance criteria. Priorities: P0 (high impact), P1 (medium), P2 (nice-to-have).

## Technical SEO

-   [ ] P0 Set correct language locale (id)

    -   Where: `resources/views/layouts/app.blade.php` (`<html lang="en">` → `lang="id"`); `config/app.php` (`'locale' => 'id'`).
    -   Accept: HTML lang="id" on all pages; date/locale helpers use Indonesian.

-   [ ] P0 Add canonical URL tags

    -   Where: Create `resources/views/partials/meta.blade.php` and include in `layouts/app.blade.php` head.
    -   Canonical: `<link rel="canonical" href="{{ url()->current() }}" />` (optionally strip query parameters).
    -   Accept: One canonical tag per page; no duplicates; points to HTTPS, no query string.

-   [ ] P0 Meta description + title templates

    -   Where: Same meta partial.
    -   Default: Use sensible defaults (sitewide) with ability to override per view via `@section('meta_description')` / `@section('title')`.
    -   Accept: Home and key pages have unique <title> and <meta name="description">.

-   [ ] P0 Robots handling for non-public pages

    -   Where: Add a small middleware (e.g., `SeoRobotsMiddleware`) or Blade conditional to output `<meta name="robots" content="noindex, nofollow">` for: `/login`, `/register`, admin routes (`/admin/*`), user areas, filter endpoints (`/packets/filter`). Also add `X-Robots-Tag: noindex` header for those routes.
    -   Accept: Those routes return a robots noindex signal.

-   [ ] P0 Sitemap.xml

    -   Where: Add `spatie/laravel-sitemap`.
        -   Install: `composer require spatie/laravel-sitemap`.
        -   Create a generator command that includes `/`, packet detail pages, landing, and other static pages, excludes auth/admin.
        -   Route `GET /sitemap.xml` serving generated sitemap.
    -   Robots: Update `public/robots.txt` to include `Sitemap: {{ config('app.url') }}/sitemap.xml`.
    -   Accept: `/sitemap.xml` loads with 200 and contains fresh URLs.

-   [ ] P0 Force HTTPS and single host (canonicalization)

    -   Where: `App\Providers\AppServiceProvider::boot()` add `\URL::forceScheme('https')` in production; add a middleware or web server rule for non-www → www (or vice versa) 301.
    -   Accept: All pages resolved on one scheme and host; HTTP 301 to HTTPS.

-   [ ] P1 Trailing slash normalization

    -   Where: Web server or middleware: enforce either always slashless or slash.
    -   Accept: Only one version is indexable; the other 301s.

-   [ ] P1 Update robots.txt
    -   Where: `public/robots.txt`.
    -   Add `Disallow: /admin/`, `/user-profile`, `/user-kelas`, `/beli-konfirmasi`, `/packets/filter` and any non-SEO endpoints. Keep everything else allowed.
    -   Accept: Robots file disallows non-public areas and references the sitemap.

## URL Structure

-   [ ] P0 Human-friendly slugs for packets

    -   Where: DB + model + routes.
        -   Migration: add `slug` to `packets` table and unique index.
        -   Model: generate slug from `title`.
        -   Routes: change `Route::get('/beli-paket/{packet_id}', ...)` to use `{packet:slug}`.
        -   Redirect: 301 from old ID URLs to new slug URLs.
    -   Accept: Packet detail pages available at `/beli-paket/{slug}`; old URLs 301 to new.

-   [ ] P1 Avoid indexing filtered/list variants
    -   Where: On listing pages (home), emit canonical to the non-filtered URL and block AJAX endpoints in `robots.txt`.
    -   Accept: Only base listing is indexed; filters aren’t indexed as separate pages.

## On-page SEO

-   [ ] P0 Heading structure and content

    -   Where: `resources/views/landing.blade.php`.
    -   Ensure one clear H1 on each page; use H2/H3 for sections (Pilih Paket, Video Pembelajaran, Artikel, etc.).
    -   Accept: Lighthouse/Axe shows a valid, logical heading structure.

-   [ ] P0 Open Graph & Twitter Card tags

    -   Where: meta partial.
    -   Provide dynamic tags per page: `og:title`, `og:description`, `og:image`, `og:url`, `twitter:card`.
    -   Accept: Link previews render with correct title/description/image for home and packet pages.

-   [ ] P1 Structured Data (JSON-LD)

    -   Where: meta partial + per-page blocks.
    -   Home: Organization schema with name, logo, `sameAs` (Instagram/TikTok URLs), contact point.
    -   Packet detail: Product/Service with name, description, price, offers, brand.
    -   BreadcrumbList for detail pages.
    -   Accept: Rich results test validates without errors.

-   [ ] P1 Internal linking

    -   Where: `layouts/header.blade.php`, `layouts/footer.blade.php`.
    -   Add a simple main nav (e.g., Paket, Video, Artikel, Kontak) linking to relevant anchors/sections or dedicated pages; convert footer product names to <a> links to category pages.
    -   Accept: Crawl paths exist to all key pages; improved internal link equity.

-   [ ] P1 Alt text and descriptive images
    -   Where: All `<img>`.
    -   Ensure descriptive `alt` for meaningful images; background images used for content should become `<img>` with alt or have appropriate `aria-label`.
    -   Accept: No missing-alt on content images; decorative images use empty alt.

## Performance (Core Web Vitals)

-   [ ] P0 Optimize and lazy-load images

    -   Where: `landing.blade.php` and other views.
    -   Add `width` and `height` attributes; `loading="lazy"` (non-hero), `decoding="async"`, and `fetchpriority="high"` on the hero.
    -   Use `<picture>` with responsive `srcset` and WebP/AVIF variants. Generate sizes server-side with `intervention/image` (already installed).
    -   Accept: LCP/CLS improvements on Lighthouse; smaller transferred bytes.

-   [ ] P0 Fonts: self-host and swap

    -   Where: Replace Google Fonts @import in `layouts/app.blade.php` with self-hosted fonts in `resources/css/app.css` using `@font-face` with `font-display: swap`. Use `preload` for woff2.
    -   Remove duplicate `@import` block and keep single `<link>` or self-hosted approach; ensure no FOIT.
    -   Accept: Fonts load fast, minimal CLS; no redundant font loads.

-   [ ] P1 Defer non-critical JS

    -   Where: Move `<script src="https://cdn.jsdelivr.net/npm/flowbite..." defer>`; ensure Vite outputs are already module/deferred.
    -   Accept: Reduced main-thread blocking time in Lighthouse.

-   [ ] P1 HTTP caching for static assets
    -   Where: Web server config (Nginx/Apache) or Laravel middleware for CDN headers.
    -   Long cache (1y) for `public/build/*`, images; short cache for HTML; include ETag.
    -   Accept: Subsequent visits show strong caching on assets.

## Content & Strategy

-   [ ] P0 Create on-site Artikel content

    -   Where: New `Article` pages under `/artikel/{slug}` instead of linking off-site.
    -   Basic CRUD + list; add Article schema; include in sitemap.
    -   Accept: At least 3 internal articles published with unique titles/descriptions.

-   [ ] P1 Unique meta for Paket categories

    -   Where: Category/grade/subject landing pages (if added) with unique title/meta description.
    -   Accept: Each category has optimized meta and intro copy.

-   [ ] P2 FAQs and How-to content
    -   Where: Add a FAQ section with FAQPage JSON-LD on relevant pages.
    -   Accept: Valid FAQ rich results.

## Analytics & Monitoring

-   [ ] P0 Add GA4 and basic conversion tracking

    -   Where: Meta partial / layout. Add `gtag.js` with consent mode and events for discount click, purchase, register.
    -   Accept: GA4 receives page_view and key events.

-   [ ] P1 Web Vitals reporting
    -   Where: Small inline module to send CWV metrics to GA4 (FID/INP, LCP, CLS) or to a custom endpoint.
    -   Accept: Dashboard has CWV metrics from real users.

## Crawl Management & Errors

-   [ ] P1 Custom 404 and 410 handling

    -   Where: Error views. Add helpful 404 with links back to Paket and Home. Use 410 for removed content.
    -   Accept: Soft-404s avoided; useful recovery path for users/bots.

-   [ ] P1 Pagination rel attributes (if used)
    -   Where: Listing pages. Add `rel="next"/"prev"` when paginating, or ensure only canonical to page 1 for infinite scroll.
    -   Accept: Pagination signals are correct.

## Security/Privacy (affects SEO trust)

-   [ ] P1 Cookie/consent banner (if required)
    -   Where: Layout.
    -   Accept: Consent shown for analytics, compliant with local regulations.

---

## Quick wins to do first (suggested order)

1. Meta partial with title/description/canonical/OG/Twitter on all pages.
2. Set `<html lang="id">` and update app locale.
3. Robots: noindex auth/admin + update robots.txt with sitemap.
4. Sitemap.xml using Spatie.
5. Packet slugs + 301 redirects.
6. Fonts: swap/self-host and defer Flowbite.
7. Image optimizations (hero fetchpriority, width/height, lazy, WebP).

## References to current code

-   Layout head: `resources/views/layouts/app.blade.php` (currently lacks description, OG/Twitter, canonical; has duplicate font @import; lang="en").
-   Home content: `resources/views/landing.blade.php` (headings, images, links, sections).
-   Header/Footer: `resources/views/layouts/header.blade.php`, `resources/views/layouts/footer.blade.php` (add nav + internal links).
-   Routes: `routes/web.php` (ID-based `beli-paket/{packet_id}` → slug recommended).
-   Robots: `public/robots.txt` (currently allows everything; add sitemap + disallows).
-   App locale: `config/app.php` (currently `'locale' => 'en'`).
-   Provider: `app/Providers/AppServiceProvider.php` (force https in prod).

## Acceptance Gate: How we’ll verify

-   Lighthouse Performance ≥ 85, SEO ≥ 95 on Home and a Packet page (mobile).
-   Google Rich Results test passes for Organization and Product pages.
-   Screaming Frog (or similar) shows correct canonicals, no duplicate titles/descriptions, and noindex on private pages.
-   `/sitemap.xml` valid and submitted in Search Console.
