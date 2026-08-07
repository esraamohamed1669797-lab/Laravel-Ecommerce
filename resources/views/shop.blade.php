@extends('layouts.app')
@section('content')
    <style>
        .brand-list li,
        .category-list li {
            line-height: 20px;
        }

        .brand-list li .chk-brand,
        .category-list li .chk-category {
            width: 1rem;
            height: 1rem;
            color: #e4e4e4;
            border: 0.125rem solid currentColor;
            border-radius: 0;
            margin-right: 0.75rem;
        }

        .filled-heart {
            color: orange;
        }

        .category-list ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .category-list .list-item {
            padding: 2px 0;
        }

        .subcategory-list {
            padding-left: 22px !important;
            margin-top: 2px;
        }

        .price-box {
            min-height: 90px;
        }

        .price-new {
            color: #000;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .price-old {
            margin-bottom: 3px;
        }

        .discount {
            color: #dc3545;
        }
        
    </style>
    <main class="pt-90">
       <section class="shop-main container-fluid px-4 d-flex flex-column flex-lg-row pt-4 pt-xl-5">
            <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
                <div class="aside-header d-flex d-lg-none align-items-center">
                    <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
                    <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
                </div>

                <div class="pt-4 pt-lg-0"></div>
                <div class="accordion" id="categories-list">
                    <div class="accordion-item mb-4 pb-3">
                        <h5 class="accordion-header" id="accordion-heading-1">
                            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button"
                                data-bs-toggle="collapse" data-bs-target="#accordion-filter-1" aria-expanded="true"
                                aria-controls="accordion-filter-1">
                                Product Categories
                                <svg class="accordion-button__icon type2" viewBox="0 0 10 6"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                        <path
                                            d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                    </g>
                                </svg>
                            </button>
                        </h5>
                        <div id="accordion-filter-1" class="accordion-collapse collapse show border-0"
                            aria-labelledby="accordion-heading-1" data-bs-parent="#categories-list">
                            <div class="accordion-body px-0 pb-0 pt-3 category-list">
                                <ul class="list list-inline mb-0">
                                    @foreach ($categories as $category)
                                        <li class="list-item">
                                            <div class="d-flex justify-content-between">
                                                <span class="menu-link py-1">
                                                    <input type="checkbox" class="chk-category" name="categories"
                                                        value="{{ $category->id }}"
                                                        @if (in_array($category->id, explode(',', $f_categories))) checked="checked" @endif>
                                                    <strong>{{ $category->name }}</strong>
                                                </span>
                                                <span>{{ $category->products_count }}</span>
                                            </div>
                                            @if ($category->children->count() > 0)
                                                <ul class="subcategory-list">
                                                    @foreach ($category->children as $child)
                                                        <li class="list-item">

                                                            <div class="d-flex justify-content-between">
                                                                <span class="menu-link py-1">
                                                                    <input type="checkbox" class="chk-category"
                                                                        name="categories" value="{{ $child->id }}"
                                                                        @if (in_array($child->id, explode(',', $f_categories))) checked="checked" @endif>
                                                                    {{ $child->name }}
                                                                </span>
                                                                <span>{{ $child->products_count }}</span>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion" id="brand-filters">
                    <div class="accordion-item mb-4 pb-3">
                        <h5 class="accordion-header" id="accordion-heading-brand">
                            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button"
                                data-bs-toggle="collapse" data-bs-target="#accordion-filter-brand" aria-expanded="true"
                                aria-controls="accordion-filter-brand">
                                Brands
                                <svg class="accordion-button__icon type2" viewBox="0 0 10 6"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                        <path
                                            d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                    </g>
                                </svg>
                            </button>
                        </h5>
                        <div id="accordion-filter-brand" class="accordion-collapse collapse show border-0"
                            aria-labelledby="accordion-heading-brand" data-bs-parent="#brand-filters">
                            <ul class="list list-inline mb-0 brand-list">
                                @foreach ($brands as $brand)
                                    <li class="list-item">
                                        <span class="menu-link py-1">
                                            <input type="checkbox" name="brands" value="{{ $brand->id }}"
                                                class="chk-brand"
                                                @if (in_array($brand->id, explode(',', $f_brands))) checked="checked" @endif>
                                            {{ $brand->name }}
                                        </span>
                                        <span class="text-right float-end">
                                            {{ $brand->products_count }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="accordion" id="price-filters">
                    <div class="accordion-item mb-4">
                        <h5 class="accordion-header mb-2" id="accordion-heading-price">
                            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button"
                                data-bs-toggle="collapse" data-bs-target="#accordion-filter-price" aria-expanded="true"
                                aria-controls="accordion-filter-price">
                                Price
                                <svg class="accordion-button__icon type2" viewBox="0 0 10 6"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                        <path
                                            d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                    </g>
                                </svg>
                            </button>
                        </h5>
                        <div id="accordion-filter-price" class="accordion-collapse collapse show border-0"
                            aria-labelledby="accordion-heading-price" data-bs-parent="#price-filters">
                            <input class="price-range-slider" type="text" name="price_range" value=""
                                data-slider-min="{{ $minPrice }}" data-slider-max="{{ $maxPrice }}"
                                data-slider-step="5" data-slider-value="[{{ $min_price }},{{ $max_price }}]"
                                data-currency="$" />
                            <div class="price-range__info d-flex align-items-center mt-2">
                                <div class="me-auto">
                                    <span class="text-secondary">Min Price: </span>
                                    <span class="price-range__min">EGP {{ number_format($min_price, 0) }}</span>
                                </div>
                                <div>
                                    <span class="text-secondary">Max Price: </span>
                                    <span class="price-range__max">EGP {{ number_format($max_price, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shop-list flex-grow-1">
                <div class="swiper-container js-swiper-slider slideshow slideshow_small slideshow_split"
                    data-settings='{
            "autoplay": {
              "delay": 5000
            },
            "slidesPerView": 1,
            "effect": "fade",
            "loop": true,
            "pagination": {
              "el": ".slideshow-pagination",
              "type": "bullets",
              "clickable": true
            }
          }'>
                    <div class="swiper-wrapper">
                        @foreach ($slides as $slide)
                            <div class="swiper-slide">
                                <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
                                    <div class="slide-split_text position-relative d-flex align-items-center"
                                        style="background-color: #ffff;">
                                        <div class="slideshow-text container p-3 p-xl-5">
                                            <h2
                                                class="text-uppercase section-title fw-normal mb-1 animate animate_fade animate_btt animate_delay-2" style="color: #b4562d;">
                                                <strong>{{ $slide->title }}</strong>
                                            </h2>
                                            <p class="mb-0 animate animate_fade animate_btt animate_delay-5 fw-bold" >
                                                Explore Now</h6>
                                        </div>
                                    </div>
                                    <div class="slide-split_media position-relative">
                                        <div class="slideshow-bg" style="background-color: #fff;">
                                            <img loading="lazy" src="{{ asset('uploads/slides/7.jpg') }}" width="630"
                                                height="450" alt="{{ $slide->title }}"
                                                class="slideshow-bg__img object-fit-cover" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="container p-3 p-xl-5">
                        <div
                            class="slideshow-pagination d-flex align-items-center position-absolute bottom-0 mb-4 pb-xl-2">
                        </div>

                    </div>
                </div>

                <div class="mb-3 pb-2 pb-xl-3"></div>

                <div class="d-flex justify-content-between mb-4 pb-md-2">
                    <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                        <a href="{{ route('home.index') }}"
                            class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                        <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                        <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
                    </div>

                    <div
                        class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                        <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0"
                            aria-label="Page Size" id="pagesize" name="pagesize" style="margin-right: 20px">
                            <option value="12" {{ $size == 12 ? 'selected' : '' }}>Show</option>
                            <option value="24" {{ $size == 24 ? 'selected' : '' }}>24</option>
                            <option value="48" {{ $size == 48 ? 'selected' : '' }}>48</option>
                            <option value="102" {{ $size == 102 ? 'selected' : '' }}>102</option>

                        </select>

                        <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0"
                            aria-label="Sort Items" name="orderby" id="orderby">
                            <option value="-1" {{ $order == -1 ? 'selected' : '' }}>Default</option>
                            <option value="1" {{ $order == 1 ? 'selected' : '' }}>Date, New To Old</option>
                            <option value="2" {{ $order == 2 ? 'selected' : '' }}>Date, Old To New</option>
                            <option value="3" {{ $order == 3 ? 'selected' : '' }}>price, Low To High</option>
                            <option value="4" {{ $order == 4 ? 'selected' : '' }}>Price, High To Low</option>

                        </select>

                        <div class="shop-asc__seprator mx-3 bg-light d-none d-md-block order-md-0"></div>

                        <div class="col-size align-items-center order-1 d-none d-lg-flex">
                            <span class="text-uppercase fw-medium me-2">View</span>
                            <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid"
                                data-cols="2">2</button>
                            <button class="btn-link fw-medium me-2 js-cols-size" data-target="products-grid"
                                data-cols="3">3</button>
                            <button class="btn-link fw-medium js-cols-size" data-target="products-grid"
                                data-cols="4">4</button>
                        </div>

                        <div class="shop-filter d-flex align-items-center order-0 order-md-3 d-lg-none">
                            <button class="btn-link btn-link_f d-flex align-items-center ps-0 js-open-aside"
                                data-aside="shopFilter">
                                <svg class="d-inline-block align-middle me-2" width="14" height="10"
                                    viewBox="0 0 14 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_filter" />
                                </svg>
                                <span class="text-uppercase fw-medium d-inline-block align-middle">Filter</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="products-container">
                    @include('shop-products')
                </div>
            </div>
        </section>
    </main>
    <form id="frmfilter" method="GET" action="{{ route('shop.index') }}">
        <input type="hidden" name="page" value="{{ $products->currentPage() }}">
        <input type="hidden" name="size" id="size" value="{{ $size }}" />
        <input type="hidden" name="order" id="order" value="{{ $order }}" />
        <input type="hidden" name="brands" id="hdnBrands" />
        <input type="hidden" name="categories" id="hdnCategories" />
        <input type="hidden" name="min" id="hdnMinPrice" value="{{ $min_price }}" />
        <input type="hidden" name="max" id="hdnMaxPrice" value="{{ $max_price }}" />

    </form>
@endsection
@push('scripts')
    <script>
        $(function() {

            // ==========================
            // Page Size
            // ==========================
            $('#pagesize').on('change', function() {

                $('#size').val($(this).val());
                filterProducts();

            });


            // ==========================
            // Order By
            // ==========================
            $("#orderby").on("change", function() {

                $("#order").val($(this).val());
                filterProducts();

            });


            // ==========================
            // Brands
            // ==========================
            $("input[name='brands']").on("change", function() {

                let brands = "";

                $("input[name='brands']:checked").each(function() {

                    if (brands == "") {
                        brands += $(this).val();
                    } else {
                        brands += "," + $(this).val();
                    }

                });

                $("#hdnBrands").val(brands);

                filterProducts();
            });


            // ==========================
            // Categories (Ajax)
            // ==========================
            $("input[name='categories']").on("change", function() {

                let categories = "";

                $("input[name='categories']:checked").each(function() {

                    if (categories == "") {
                        categories += $(this).val();
                    } else {
                        categories += "," + $(this).val();
                    }

                });

                $("#hdnCategories").val(categories);

                filterProducts();

            });


            // ==========================
            // Price
            // ==========================
            $("[name='price_range']").on("change", function() {

                let min = $(this).val().split(',')[0];
                let max = $(this).val().split(',')[1];

                $(".price-range__min").text("$" + min);
                $(".price-range__max").text("$" + max);

                $("#hdnMinPrice").val(min);
                $("#hdnMaxPrice").val(max);

                filterProducts();

            });

        });
        // ==========================
        // Ajax Function
        // ==========================
        function filterProducts() {

            $.ajax({

                url: $("#frmfilter").attr("action"),
                type: "GET",
                data: $("#frmfilter").serialize(),

                success: function(response) {

                    $("#products-container").html(response);

                },

                error: function(xhr) {

                    console.log(xhr.responseText);

                }

            });

        }
        $(document).on("click", ".pagination a", function(e) {

            e.preventDefault();

            let url = $(this).attr("href");

            let page = new URL(url).searchParams.get("page");

            $("input[name='page']").val(page);

            filterProducts();

        });
    </script>
@endpush
