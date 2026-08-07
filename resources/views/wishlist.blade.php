@extends('layouts.app')
@section('content')
<style>
  .btn{
    white-space: nowrap;
     min-width: 130px;
  }
   .remove-cart svg{
    transform: translate(20px, 0px);
}
  @media (max-width: 768px) {
  .remove-cart svg{
    transform: translate(13px, -15px);
}}
  </style>
 <main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Wishlist</h2>
     @if(Cart::instance('wishlist')->content()->count()>0)
      <div class="shopping-cart">
        <div class="cart-table__wrapper">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th style="position: relative; right: -20px;">Action</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($items as $item )
              <tr>
                <td>
                  <div class="shopping-cart__product-item">
                    <img loading="lazy" src="{{asset('uploads/products/thumbnails')}}/{{$item->model->image}}" width="120" height="120" alt="{{$item->name}}" />
                  </div>
                </td>
                <td>
                  <div class="shopping-cart__product-item__detail">
                    <h4>{{$item->name}}</h4>
                    {{-- <ul class="shopping-cart__product-item__options">
                      <li>Color: Yellow</li>
                      <li>Size: L</li> --}}
                    </ul>
                  </div>
                </td>
                <td>
                  <span class="shopping-cart__product-price">EGP {{ number_format($item->price, 0) }}</span>
                </td>
                <td style="position: relative; right: -20px;">
                  {{$item->qty}}
                </td>
                <td>
                    <div class="row">
             <div class="col-6">
              <form action="{{ route('wishlist.move.to.cart',$item->rowId) }}" method="POST" class="move-to-cart-form">
             @csrf
                 <button class="btn btn-sm btn-warning w-100">
                     Move To Cart
                 </button>
             </form>
    </div>
    <div class="col-6">
        <form action="{{ route('wishlist.remove',$item->rowId) }}" method="POST" class="remove-wishlist-form" data-product="{{ $item->id }}">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" value="{{ $item->id }}">
            <input type="hidden" name="name" value="{{ $item->name }}">
            <input type="hidden" name="price" value="{{ $item->price }}">
            <input type="hidden" name="quantity" value="{{ $item->qty }}">
             <button type="submit" class="remove-cart border-0 bg-transparent">
            <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676">
            <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z"/>
            <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z"/>
            </svg>
             </button>
        </form>
    </div>
</div>
                </td>
              </tr>
               @endforeach
            </tbody>
          </table>
          <div class="cart-table-footer">
            <form method="POST" action="{{route('wishlist.item.clear')}}">
                @csrf
                @method('DELETE')
            <button type="submit" class="btn btn-light">CLEAR WISHLIST</button>
            </form>
          </div>
        </div>
        @else
          <div class="row"></div>
            <div class="col-md-12"></div>
            <p> No item found in your wishlist</p>
            <a href="{{route('shop.index')}}" class="btn btn-info">Wishist Now</a>
        @endif
       
      </div>
    </section>
  </main>
@endsection
@push('scripts')
<script>
$(function () {

    // ==========================
    // Remove From Wishlist Ajax
    // ==========================
    $('.remove-wishlist-form').submit(function (e) {

        e.preventDefault();

        let form = $(this);

        $.ajax({

            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),

            success: function (response) {

                // تحديث عداد الوش ليست
                $('.js-wishlist-count').text(response.count);

                // حذف الصف من الجدول
                form.closest('tr').remove();

                // لو مفيش منتجات خالص اعمل ريفريش عشان تظهر رسالة Empty Wishlist
                if ($('.cart-table tbody tr').length == 0) {
                    location.reload();
                }

            },

            error: function (xhr) {

                console.log(xhr.responseText);

            }

        });

    });


    // ==========================
    // Move To Cart Ajax
    // ==========================
    $('.move-to-cart-form').submit(function (e) {

        e.preventDefault();

        let form = $(this);

        $.ajax({

            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),

            success: function (response) {

                // تحديث عداد الكارت
                $('.js-cart-count').text(response.cart_count);

                // تحديث عداد الوش ليست
                $('.js-wishlist-count').text(response.wishlist_count);

                // حذف المنتج من الجدول
                form.closest('tr').remove();

                // لو الوش ليست فضيت اعمل ريفريش
                if ($('.cart-table tbody tr').length == 0) {
                    location.reload();
                }

            },

            error: function (xhr) {

                console.log(xhr.responseText);

            }

        });

    });

});
</script>
@endpush