@extends('layouts.app')
@section('content')
<style>
    .hero-btn{
    display:inline-block;
    background:#d8704a;
    color:#fff;
    padding:12px 30px;
    border-radius:50px;
    text-decoration:none;
    font-weight:600;
    transition:.3s;
}
.hero-btn:hover{
    background:#d8704a;
    color:#fff;
}
.hero-badge{
    display:inline-block;
    padding:4px 12px;
    background:#ffe8f6;
    color:#d8704a;
    border-radius:30px;
    font-size:13px;
    font-weight:600;
    letter-spacing:2px;
    margin-bottom:15px;}
.hero-title{
    font-size:50px;
    font-weight:500;
    margin-bottom: 10px;
}

.hero-subtitle{
    font-size:25px;
    font-weight:700;
    margin-bottom: 10px;
}

 .swiper-slide .row {
        padding-top: 0;
    }
    .overflow-hidden.position-relative.h-100,
    .swiper-container.slideshow {
        height: auto !important;
        min-height: 300px;
    }
.swiper-slide .row {
        padding-top: 50px;
    }

@media (max-width: 768px) {
    .swiper-slide .row {
        padding-top: 0;
    }
    .overflow-hidden.position-relative.h-100,
    .swiper-container.slideshow {
        height: auto !important;
        min-height: 300px;
    }
}
@media (max-width: 768px) {
    .swiper-slide .row {
        padding-top: 100px;
    }
}
@media (max-width:768px){
    .hero-title{
        font-size:15px;
        margin-bottom: 5px;
    }
    .hero-subtitle{
        font-size:13px;
        margin-bottom: 10px;
    }
    .hero-badge{
        font-size:10px;
        padding:4px 12px;
    }
    .hero-btn{
        padding:8px 20px;
        font-size:10px;
    }
    .hero-section {
    margin-top: 20px !important;
}
}
@media (min-width: 769px) {
.hero-section {
    margin-top: 60px !important;
}}
.menu-link {
    font-size: 11px
}
</style>
    <main>

        <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow"
            data-settings='{
        "autoplay": {
          "delay": 5000
        },
        "slidesPerView": 1,
        "effect": "fade",
        "loop": true
      }'>
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide">
                        <div class="overflow-hidden position-relative h-100">
                           <div class="container h-100">
    <div class="row align-items-center h-100">

       <div class="col-6  text-md-start hero-section">
            <h6 class="hero-badge">New Arrivals</h6>

            <h2 class="hero-title">{{ $slide->title }}</h2>

            <h2 class="fw-bold hero-subtitle" style="color: #d8704a">{{ $slide->subtitle }}</h2>

            <a href="{{ $slide->link }}" class="hero-btn">
                            Explore Now
                        </a>
        </div>

        <div class="col-6 text-center" >
            <img src="{{ asset('uploads/slides/'.$slide->image) }}"
                 class="img-fluid"
                 style="max-height:550px;">
        </div>

    </div>
</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="container">
                <div
                    class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5">
                </div>
            </div>
        </section>
        <div class="container mw-1620 bg-white border-radius-10">
     
            <section class="category-carousel container">
                <h2 class="section-title text-center mb-3 pb-xl-2 mb-xl-4" style="color: #d8704a">Categories</h2>

                <div class="position-relative">
                    <div class="swiper-container js-swiper-slider"
                        data-settings='{
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": 8,
              "slidesPerGroup": 1,
              "effect": "none",
              "loop": true,
              "navigation": {
                "nextEl": ".products-carousel__next-1",
                "prevEl": ".products-carousel__prev-1"
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "slidesPerGroup": 2,
                  "spaceBetween": 15
                },
                "768": {
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "spaceBetween": 30
                },
                "992": {
                  "slidesPerView": 6,
                  "slidesPerGroup": 1,
                  "spaceBetween": 45,
                  "pagination": false
                },
                "1200": {
                  "slidesPerView": 8,
                  "slidesPerGroup": 1,
                  "spaceBetween": 60,
                  "pagination": false
                }
              }
            }'>
                        <div class="swiper-wrapper ">
                            @foreach ($categories as $category)
                                <div class="swiper-slide">
                                    <img loading="lazy" class="w-100  mb-3"
                                        src="{{ asset('uploads/categories') }}/{{ $category->image }}"
                                        style="height:124px; object-fit:contain" alt="" />
                                    <div class="text-center" >
                                        <a href="{{ route('shop.index', ['categories' => $category->id]) }}"
                                            class="menu-link fw-medium" >{{ $category->name }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div><!-- /.swiper-wrapper -->
                    </div><!-- /.swiper-container js-swiper-slider -->

                    <div
                        class="products-carousel__prev products-carousel__prev-1 position-absolute top-50 d-flex align-items-center justify-content-center">
                        <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_prev_md" />
                        </svg>
                    </div><!-- /.products-carousel__prev -->
                    <div
                        class="products-carousel__next products-carousel__next-1 position-absolute top-50 d-flex align-items-center justify-content-center">
                        <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_next_md" />
                        </svg>
                    </div><!-- /.products-carousel__next -->
                </div><!-- /.position-relative -->
            </section>

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            <section class="hot-deals container">
                <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4"  style="color: #d8704a">Hot Deals</h2>
                <div class="row">
                    <div
                        class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
                        <h2>Summer Sale</h2>
                        <h2 class="fw-bold">Up to 60% Off</h2>

                        <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
                            data-date="18-3-2024" data-time="06:50">
                            <div class="day countdown-unit">
                                <span class="countdown-num d-block"></span>
                                <span class="countdown-word text-uppercase text-secondary">Days</span>
                            </div>

                            <div class="hour countdown-unit">
                                <span class="countdown-num d-block"></span>
                                <span class="countdown-word text-uppercase text-secondary">Hours</span>
                            </div>

                            <div class="min countdown-unit">
                                <span class="countdown-num d-block"></span>
                                <span class="countdown-word text-uppercase text-secondary">Mins</span>
                            </div>

                            <div class="sec countdown-unit">
                                <span class="countdown-num d-block"></span>
                                <span class="countdown-word text-uppercase text-secondary">Sec</span>
                            </div>
                        </div>

                        <a href="{{ route('shop.index') }}"
                            class="btn-link default-underline text-uppercase fw-medium mt-3">View All</a>
                    </div>
                    <div class="col-md-6 col-lg-8 col-xl-80per">
                        <div class="position-relative">
                            <div class="swiper-container js-swiper-slider"
                                data-settings='{
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "effect": "none",
                  "loop": false,
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 14
                    },
                    "768": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 3,
                      "spaceBetween": 24
                    },
                    "992": {
                      "slidesPerView": 3,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    },
                    "1200": {
                      "slidesPerView": 4,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    }
                  }
                }'>
                                <div class="swiper-wrapper">
                                    @foreach ($sproducts as $sproduct)
                                        @php
                                            if ($sproduct->sale_price) {
                                                $discountAmount =
                                                    ($sproduct->regular_price * $sproduct->sale_price) / 100;
                                                $finalPrice = $sproduct->regular_price - $discountAmount;
                                            }
                                        @endphp

                                        <div class="swiper-slide">
                                            <div class="border text-center p-3 pt-5 h-100 position-relative bg-white">

                                                {{-- Wishlist --}}
                                                <form action="{{ route('wishlist.toggle') }}" method="POST"
                                                    class="wishlist-form position-absolute top-0 end-0 m-2"
                                                    data-product="{{ $sproduct->id }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $sproduct->id }}">
                                                    <input type="hidden" name="name" value="{{ $sproduct->name }}">
                                                    <input type="hidden" name="price"
                                                        value="{{ $sproduct->sale_price ? $finalPrice : $sproduct->regular_price }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-sm border-0 bg-transparent">
                                                        <i
                                                            class="fa {{ Cart::instance('wishlist')->content()->where('id', $sproduct->id)->count() ? 'fa-heart text-danger' : 'fa-heart-o' }}"></i>
                                                    </button>
                                                </form>
                                                {{-- Image --}}
                                                <a
                                                    href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}">
                                                    <img loading="lazy"
                                                        src="{{ asset('uploads/products/' . $sproduct->image) }}"
                                                        class="img-fluid mb-3"
                                                        style="height:220px;width:100%;object-fit:contain"
                                                        alt="{{ $sproduct->name }}">
                                                </a>

                                                {{-- Price --}}
                                                <div class="price-box mb-3">

                                                    @if ($sproduct->sale_price)
                                                        <h3 class="price-new">
                                                             EGP{{ number_format($finalPrice, 0) }}
                                                        </h3>

                                                        <p class="price-old">
                                                            <del>${{ $sproduct->regular_price }}</del>
                                                        </p>

                                                        <small class="discount">
                                                            {{ $sproduct->sale_price }}% OFF
                                                        </small>
                                                    @else
                                                        <h3 class="price-new">
                                                          EGP {{ number_format($sproduct->regular_price, 0) }}
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

                                                {{-- Name --}}
                                               <p class="product-title mt-2 mb-3">
                                                    {{ $sproduct->name }}
                                               </p>

                                                {{-- Cart --}}
                                                @if (Cart::instance('cart')->content()->where('id', $sproduct->id)->count() > 0)
                                                    <a href="{{ route('cart.index') }}"
                                                        class="btn go-cart-btn mb-3 w-100">
                                                        <i class="fa fa-shopping-cart me-2"></i>
                                                        Go to Cart
                                                    </a>
                                                @else
                                                    <form method="POST" action="{{ route('cart.add') }}"
                                                        class="add-to-cart-form" data-product="{{ $sproduct->id }}">
                                                        @csrf

                                                        <input type="hidden" name="id"
                                                            value="{{ $sproduct->id }}">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <input type="hidden" name="name"
                                                            value="{{ $sproduct->name }}">
                                                        <input type="hidden" name="price"
                                                            value="{{ $sproduct->sale_price ? $finalPrice : $sproduct->regular_price }}">
                                                        <button type="submit" class="btn add-cart-btn mb-3 w-100">
                                                            <i class="fa fa-shopping-cart me-2"></i>
                                                            Add to Cart
                                                        </button>
                                                    </form>
                                                @endif

                                            </div>
                                        </div>
                                    @endforeach
                                </div> <!-- swiper-wrapper -->
                            </div> <!-- swiper-container -->
                        </div> <!-- position-relative -->
                    </div> <!-- col-md-6 col-lg-8 col-xl-80per -->
                </div> <!-- row -->


            </section>

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            {{-- <section class="category-banner container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="category-banner__item border-radius-10 mb-5 d-flex justify-content-center align-items-center"
                            style="height:300px;">
                            <img loading="lazy" class="" src="{{ asset('uploads/slides/2.jpg') }}"
                                style="height:124px; object-fit:contain" alt="" />
                            <div class="category-banner__item-mark">
                                Starting at $19
                            </div>
                            <div style="text-align: center">
                                <h3 class="mb-0">Mobile phone</h3>
                                 <a href="{{ route('shop.index') }}" class="hero-btn">
                            Explore Now
                        </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="category-banner__item border-radius-10 mb-5 d-flex justify-content-center align-items-center"
                            style="height:300px;">
                            <img loading="lazy" class="" src="{{ asset('uploads/slides/3.jpg') }}"
                                style="height:124px; object-fit:contain" alt="" />
                            <div class="category-banner__item-mark">
                                Starting at $19
                            </div>
                            <div style="text-align: center">
                                <h3 class="mb-0">Labtop</h3>
                                 <a href="{{ route('shop.index') }}" class="hero-btn">
                            Explore Now
                        </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section> --}}

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            <section class="products-grid container">
                <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4" style="color: #d8704a">Featured Products</h2>
                
                <div id="featured-products">
                    @include('featured-products')
                </div>
             
            </section>
        </div>

        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    </main>
@endsection
@push('scripts')
<script>
$(document).on("click", "#featured-products .pagination a", function(e){

    e.preventDefault();

    let url = $(this).attr("href");

    $.ajax({

        url: url,
        type: "GET",

        success:function(response){

            $("#featured-products").html(response);

        },

        error:function(xhr){

            console.log(xhr.responseText);

        }

    });

});
</script>
@endpush
