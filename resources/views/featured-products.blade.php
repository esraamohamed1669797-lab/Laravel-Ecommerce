<div class="row">
                    @foreach ($fproducts as $fproduct)
                        @php
                            if ($fproduct->sale_price) {
                                $discountAmount = ($fproduct->regular_price * $fproduct->sale_price) / 100;
                                $finalPrice = $fproduct->regular_price - $discountAmount;
                            }
                        @endphp

                        <div class="col-6 col-md-4 col-lg-3 mb-4">
                            <div class="border text-center p-3 pt-5 h-100 position-relative">

                                {{-- Wishlist --}}
                                <form action="{{ route('wishlist.toggle') }}" method="POST"
                                    class="wishlist-form position-absolute top-0 end-0 m-2"
                                    data-product="{{ $fproduct->id }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $fproduct->id }}">
                                    <input type="hidden" name="name" value="{{ $fproduct->name }}">
                                    <input type="hidden" name="price"
                                        value="{{ $fproduct->sale_price ? $finalPrice : $fproduct->regular_price }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm border-0 bg-transparent">
                                        <i
                                            class="fa {{ Cart::instance('wishlist')->content()->where('id', $fproduct->id)->count() ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                                    </button>
                                </form>
                                {{-- صورة المنتج --}}
                                <a href="{{ route('shop.product.details', ['product_slug' => $fproduct->slug]) }}">
                                    <img src="{{ asset('uploads/products/' . $fproduct->image) }}" class="img-fluid mb-3"
                                        style="height:220px;width:100%;object-fit:contain" alt="{{ $fproduct->name }}">
                                </a>

                                {{-- السعر --}}
                                <div class="price-box mb-3">

                                    @if ($fproduct->sale_price)
                                        <h3 class="price-new">
                                            EGP {{ number_format($finalPrice, 0) }}
                                        </h3>

                                        <p class="price-old">
                                            <del>EGP {{ number_format($fproduct->regular_price, 0) }}</del>
                                        </p>

                                        <small class="discount">
                                            {{ $fproduct->sale_price }}% OFF
                                        </small>
                                    @else
                                        <h3 class="price-new">
                                           EGP {{ number_format($fproduct->regular_price, 0) }}
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

                                {{-- اسم المنتج --}}
                                <p class="product-title mt-2 mb-3">
                                    {{ $fproduct->name }}
                                </p>

                                {{-- السلة --}}
                                @if (Cart::instance('cart')->content()->where('id', $fproduct->id)->count() > 0)
                                    <a href="{{ route('cart.index') }}" class="btn go-cart-btn mb-3 w-100">
                                        <i class="fa fa-shopping-cart me-2"></i>
                                        Go to Cart
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('cart.add') }}" class="add-to-cart-form"
                                        data-product="{{ $fproduct->id }}">
                                        @csrf

                                        <input type="hidden" name="id" value="{{ $fproduct->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="name" value="{{ $fproduct->name }}">
                                        <input type="hidden" name="price"
                                            value="{{ $fproduct->sale_price ? $finalPrice : $fproduct->regular_price }}">

                                        <button type="submit" class="btn add-cart-btn mb-3 w-100">
                                            <i class="fa fa-shopping-cart me-2"></i>
                                            Add to Cart
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div><!-- /.row -->
                 <div class="d-flex justify-content-center mt-4 featured-pagination">
                   {{ $fproducts->links('pagination::bootstrap-5') }}
                       </div>