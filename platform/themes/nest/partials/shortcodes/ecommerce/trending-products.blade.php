@if ((int) $shortcode->limit)
    @php
        $products = get_trending_products([
            'take'      => (int) $shortcode->limit,
            'with'      => ['slugable'],
        ] + EcommerceHelper::withReviewsParams());
    @endphp
    @if ($products->count())
        <section class="bg-grey-1 section-padding pt-100 pb-80">
            <div class="container">
                <h1 class="mb-80 text-center">{!! BaseHelper::clean($shortcode->title) !!}</h1>

                @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.product-items-loop', ['products' => $products, 'perRow' => (int)$shortcode->per_row > 0 ? (int)$shortcode->per_row : 4])
            </div>
        </section>
    @endif
@endif
