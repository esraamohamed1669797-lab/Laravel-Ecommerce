    <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
        @foreach ($products as $product)
            @php
                if ($product->sale_price) {
                    $discountAmount = ($product->regular_price * $product->sale_price) / 100;
                    $finalPrice = $product->regular_price - $discountAmount;
                }
            @endphp

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="border text-center p-4 h-100 position-relative">

                    {{-- Wishlist --}}
                    <form action="{{ route('wishlist.toggle') }}" method="POST"
                        class="wishlist-form position-absolute top-0 end-0 m-2" data-product="{{ $product->id }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="price"
                            value="{{ $product->sale_price ? $finalPrice : $product->regular_price }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-sm border-0 bg-transparent">
                            <i
                                class="fa {{ Cart::instance('wishlist')->content()->where('id', $product->id)->count() ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                        </button>
                    </form>
                    {{-- Product Image --}}
                    <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                        <img src="{{ asset('uploads/products/' . $product->image) }}" class="img-fluid mb-3"
                            style="height:220px;width:100%;object-fit:contain" alt="{{ $product->name }}">
                    </a>
                    {{-- Price --}}
                    <div class="price-box mb-3">

                        @if ($product->sale_price)
                            <h3 class="price-new">
                                EGP {{ number_format($finalPrice, 0) }}
                            </h3>

                            <p class="price-old">
                                <del>EGP {{ number_format($product->regular_price, 0) }}</del>
                            </p>

                            <small class="discount">
                                {{ $product->sale_price }}% OFF
                            </small>
                        @else
                            <h3 class="price-new">
                              EGP {{ number_format($product->regular_price, 0) }}
                            </h3>

                            <!-- مكان فاضي يحافظ على نفس الارتفاع -->
                            <p class="price-old invisible">
                                <del>$0</del>
                            </p>

                            <small class="discount invisible">
                                0% OFF
                            </small>
                        @endif

                    </div>
                    {{-- Product Name --}}
                    <p class="product-title mt-2 mb-3">
                        {{ $product->name }}
                    </p>
                    {{-- Add To Cart --}}
                    @if (Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                        <a href="{{ route('cart.index') }}" class="btn go-cart-btn mb-3 w-100">
                            <i class="fa fa-shopping-cart me-2"></i>
                            Go to Cart
                        </a>
                    @else
                        <form method="POST" action="{{ route('cart.add') }}" class="add-to-cart-form"
                            data-product="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price"
                                value="{{ $product->sale_price ? $finalPrice : $product->regular_price }}">

                            <button type="submit" class="btn add-cart-btn mb-3 w-100">
                                <i class="fa fa-shopping-cart me-2"></i>
                                Add to Cart
                            </button>
                        </form>
                    @endif
                    {{-- Bottom Buttons --}}
                    <hr>
                    <div class="d-flex justify-content-between small">

    <form action="{{ route('wishlist.toggle') }}"
          method="POST"
          class="wishlist-form d-inline"
          data-product="{{ $product->id }}">
        @csrf

        <input type="hidden" name="id" value="{{ $product->id }}">
        <input type="hidden" name="name" value="{{ $product->name }}">
        <input type="hidden" name="price"
            value="{{ $product->sale_price ? $finalPrice : $product->regular_price }}">
        <input type="hidden" name="quantity" value="1">

        <button type="submit" class="border-0 bg-transparent text-secondary text-decoration-none p-0">
          <i class="fa {{ Cart::instance('wishlist')->content()->where('id',$product->id)->count() ? 'fa-heart text-danger': 'fa-heart-o' }}"></i>
          <span>Add to wishlist</span>
      </button>
    </form>

    <a href="#" class="text-secondary text-decoration-none">
        <i class="fa fa-exchange"></i>
        Add to compare
    </a>

</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="divider"></div>
    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
