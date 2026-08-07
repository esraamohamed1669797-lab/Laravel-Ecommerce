@extends('layouts.app')
@section('content')
<style> 
.text-success{
  color:#278c04 !important;
}
.text-danger{
  color:#d61808 !important;
}
.shopping-cart__product-price,
.item-subtotal {
    white-space: nowrap;
    display: inline-block;
    padding: 15px 20px;
}

/* .cart-table th,
.cart-table td {
    white-space: nowrap;
    
} */
.text-success{
  color:#278c04 !important;
}
.text-danger{
  color:#d61808 !important;
}


@media (max-width: 768px) {
    .cart-table tbody tr {
        display: grid !important;
            grid-template-columns: 65px 1fr 70px !important;
        grid-template-areas:
            "img name  price"
            "img qty   price" !important;
        align-items: center !important;
        column-gap: 40px !important;
        row-gap: 6px !important;
        padding: 15px 0 !important;
        position: relative !important;
    }

    .cart-table tbody td {
        display: block !important;
        border: none !important;
        padding: 0 !important;
        white-space: normal !important;
        overflow: visible !important;
        visibility: visible !important;
    }

    .cart-table tbody td:nth-child(1) { grid-area: img; }
    .cart-table tbody td:nth-child(2) { grid-area: name; align-self: end !important; margin: 0 !important; }
    .cart-table tbody td:nth-child(3) {
        grid-area: price !important;
        justify-self: end !important;
        align-self: center !important;
        margin: 0 !important;
        display: block !important;
    }
    .cart-table tbody td:nth-child(4) { grid-area: qty; align-self: start !important; }
    .cart-table tbody td:nth-child(5) { display: none !important; }
    .cart-table tbody td:nth-child(6) { position: absolute !important; top: 15px !important; right: 0 !important; }

    .shopping-cart__product-price {
        display: inline-block !important;
        white-space: nowrap !important;
    }

    .qty-control {
        display: flex !important;
        align-items: center !important;
        border: 1px solid #999 !important;
        border-radius: 4px !important;
        width: fit-content !important;
        position: static !important;
        overflow: hidden !important;
    }

    .qty-control form { position: static !important; margin: 0 !important; }

    .qty-control .qty-input {
        order: 2 !important;
        width: 36px !important;
        height: 30px !important;
        text-align: center !important;
        border: none !important;
        border-left: 1px solid #999 !important;
        border-right: 1px solid #999 !important;
        background: transparent !important;
        
    }

    .qty-control .decrease-cart-form { order: 1 !important; }
    .qty-control .increase-cart-form { order: 3 !important; }

    .qty-control__reduce,
    .qty-control__increase {
        position: static !important;
        width: 30px !important;
        height: 5px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: none !important;
        background: transparent !important;
    }
}
@media (max-width: 768px) {
    .cart-table tbody tr {
        grid-template-columns: 75px 1fr 70px !important;
        column-gap: 12px !important;
    }

    .cart-table tbody td:nth-child(1) {
        justify-self: start !important;
    }

    .cart-table tbody td:nth-child(2) {
        padding-left: 40px !important;
    }

    .cart-table tbody td:nth-child(3) {
        align-self: center !important;
        justify-self: end !important;
    }
}
@media (max-width: 768px) {
    .cart-table tbody tr {
        grid-template-columns: 75px 1fr !important;
        grid-template-areas:
            "img name"
            "img qtyprice" !important;
    }

    .cart-table tbody td:nth-child(3) {
        grid-area: qtyprice !important;
        justify-self: start !important;
        align-self: center !important;
        display: flex !important;
        align-items: center !important;
        order: 2 !important;
    }

    .cart-table tbody td:nth-child(4) {
        grid-area: qtyprice !important;
        justify-self: start !important;
    }

    .shopping-cart__product-price {
        margin-left: 12px !important;
    }
}
@media (max-width: 768px) {
    .cart-table tbody tr {
        grid-template-columns: 75px auto 1fr !important;
        grid-template-areas:
            "img name  name"
            "img qty   price" !important;
    }

    .cart-table tbody td:nth-child(2) {
        grid-area: name !important;
    }
/* اخفي عمود السعر */
.cart-table tbody td:nth-child(3){
    display: none !important;
}

/* اظهر عمود الـ Subtotal مكانه */
.cart-table tbody td:nth-child(5){
    display: block !important;
    grid-area: price !important;
    justify-self: start !important;
    align-self: center !important;
    margin-left: 12px !important;
    margin-top: 28px !important;
}

    .cart-table tbody td:nth-child(4) {
        grid-area: qty !important;
        justify-self: start !important;
    }
    .qty-control{
    margin-left: -100px !important;
}
.cart-table tbody td:nth-child(3),
.cart-table tbody td:nth-child(4){
    transform: translateY(10px);
}
.remove-cart svg{
    transform: translate(10px, -15px);
}
}
</style>
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Cart</h2>
      <div class="checkout-steps">
        <a href="javascript:void(0)" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Shopping Bag</span>
            <em>Manage Your Items List</em>
          </span>
        </a>
        <a href="{{route('cart.checkout')}}" class="checkout-steps__item">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Shipping and Checkout</span>
            <em>Checkout Your Items List</em>
          </span>
        </a>
        <a href="{{route('cart.order.confirmation')}}" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Confirmation</span>
            <em>Review And Submit Your Order</em>
          </span>
        </a>
      </div>
      <div class="shopping-cart">
        @if($items->count()>0)
        <div class="cart-table__wrapper">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Product</th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                @foreach ($items as $item )
              <tr data-rowid="{{ $item->rowId }}">
                <td>
                  <div class="shopping-cart__product-item">
                    <img loading="lazy" src="{{asset('uploads/products/thumbnails/')}}/{{$item->model->image}}" width="120" height="120" alt="{{$item->name}}" />
                  </div>
                </td>
                <td>
                  <div class="shopping-cart__product-item__detail">
                    <h4>{{$item->name}}</h4>
                    {{-- <ul class="shopping-cart__product-item__options">
                      <li>Color: Yellow</li>
                      <li>Size: L</li>
                    </ul> --}}
                  </div>
                </td>
                <td>
                  <span class="shopping-cart__product-price"> EGP {{ number_format($item->price, 0) }}</span>
                </td>
                <td>
                  <div class="qty-control position-relative">
                    <input type="number" name="quantity" value="{{$item->qty}}" min="1" class="qty-control__number text-center qty-input">
                    <form class="decrease-cart-form" method="POST" action="{{ route('cart.qty.decrease',['rowId'=>$item->rowId]) }}">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="qty-control__reduce border-0 bg-transparent">
                          -
                      </button>
                           </form>
                    <form class="increase-cart-form" method="POST" action="{{ route('cart.qty.increase',['rowId'=>$item->rowId]) }}">
                        @csrf
                        @method('PUT')
                       <button type="submit" class="qty-control__increase border-0 bg-transparent">
                         +
                       </button>
                    </form>
                  </div>
                </td>
                <td>
                  <span class="shopping-cart__subtotal item-subtotal">EGP {{ number_format((float) str_replace(',', '', $item->subTotal()), 0) }}</span>
                </td>
                <td>
                   <form class="remove-cart-form" method="POST" action="{{ route('cart.item.remove',['rowId'=>$item->rowId]) }}">
                    @csrf
                    @method('DELETE')
                    <a href="javascript:void(0)" class="remove-cart">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                         <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z"/>
                         <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z"/>
                     </svg>
                 </a>
                </form>
                </td>
              </tr>
                @endforeach
            </tbody>
          </table>
          <div class="cart-table-footer">
            @if(!Session::has('coupon'))
             <form action="{{route('cart.coupon.apply')}}" method="POST" class="position-relative bg-body">
              @csrf
              <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code" value="">
              <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                value="APPLY COUPON">
            </form>
            @else
            <form action="{{route('cart.coupon.remove')}}" method="POST" class="position-relative bg-body">
              @csrf
              @method('DELETE')
              <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code" value="@if(Session::has('coupon')){{Session::get('coupon')['code']}} Applied!@endif">
              <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                value="REMOVE COUPON">
            </form>
            @endif
            <form method="POST" action="{{route('cart.empty')}}" class="empty-cart-form">
                @csrf
                @method('DELETE')
            <button class="btn btn-light" type="submit">CLEAR CART</button>
            </form>
          </div>
          <div>
            @if(Session::has('success'))
            <p class="text-success">{{Session::get('success')}}</p>
            @elseif (Session::has('error'))
            <p class="text-danger">{{Session::get('error')}}</p>
            @endif
          </div>
        </div>
      
        <div class="shopping-cart__totals-wrapper">
          <div class="sticky-content">
            <div class="shopping-cart__totals">
              <h3>Cart Totals</h3>
              @if(Session::has('discounts'))
              <table class="cart-totals">
                <tbody>
                  <tr>
                    <th>Subtotal</th>
                    <td>EGP {{ number_format((float) str_replace(',', '', Cart::instance('cart')->subtotal()), 0) }}</td>
                  </tr>
                   <tr>
                    <th>Discount{{Session::get('coupon')['code']}}</th>
                    <td>EGP {{ number_format((float) str_replace(',', '', Session::get('discounts')['discount']), 0) }}</td>
                  </tr>
                   <tr>
                    <th>Subtotal After Discount</th>
                    <td>EGP {{ number_format((float) str_replace(',', '',Session::get('discounts')['subtotal']),0)}}</td>
                  </tr>
                  <tr>
                    <th>Shipping</th>
                    <td>
                    Free
                    </td>
                  </tr>
                  <tr>
                    <th>VAT</th>
                    <td>EGP {{ number_format((float) str_replace(',', '',Session::get('discounts')['tax']),0)}}</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>EGP {{ number_format((float) str_replace(',', '',Session::get('discounts')['total']),0)}}</td>
                  </tr>
                </tbody>
              </table>
              @else
              <table class="cart-totals">
                <tbody>
                  <tr>
                    <th>Subtotal</th>
                    <td id="cart-subtotal">EGP {{ number_format((float) str_replace(',', '',Cart::instance('cart')->subtotal()),0)}}</td>
                  </tr>
                  <tr>
                    <th>Shipping</th>
                    <td>
                    Free
                    </td>
                  </tr>
                  <tr>
                    <th>VAT</th>
                    <td id="cart-tax">EGP {{ number_format((float) str_replace(',', '',Cart::instance('cart')->tax()),0)}}</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td id="cart-total">EGP {{ number_format((float) str_replace(',', '',Cart::instance('cart')->total()),0)}}</td>
                  </tr>
                </tbody>
              </table>
              @endif
            </div>
            <div class="mobile_fixed-btn_wrapper">
              <div class="button-wrapper container">
                <a href="{{route('cart.checkout')}}" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
              </div>
            </div>
          </div>
        </div>
        @else
        <div class="row">
            <div class="col-md-12 text-center pt-5 bp-5">
                <p>No item found in your cart</p>
                <a href="{{route('shop.index')}}"class="btn btn-info">Shop Now</a>
            </div>
        </div>
        @endif
      </div>
    </section>
  </main>
@endsection
@push('scripts')
<script>
$(function () {

    // =========================
    // Increase Quantity
    // =========================
    $('.increase-cart-form').submit(function (e) {

        e.preventDefault();

        let form = $(this);

        $.ajax({

            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),

            success: function (response) {

                let row = form.closest('tr');

                row.find('.qty-input').val(response.qty);
                row.find('.item-subtotal').text('EGP' + response.subtotal);

                $('#cart-subtotal').text('EGP' + response.cartSubtotal);
                $('#cart-tax').text('EGP' + response.cartTax);
                $('#cart-total').text('EGP' + response.cartTotal);

                $('.js-cart-count').text(response.count);

            },

            error:function(xhr){
                console.log(xhr.responseText);
            }

        });

    });


    // =========================
    // Decrease Quantity
    // =========================
    $('.decrease-cart-form').submit(function (e) {

        e.preventDefault();

        let form = $(this);

        $.ajax({

            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),

            success: function (response) {

                let row = form.closest('tr');

                row.find('.qty-input').val(response.qty);
                row.find('.item-subtotal').text('EGP' + response.subtotal);

                $('#cart-subtotal').text('EGP' + response.cartSubtotal);
                $('#cart-tax').text('EGP' + response.cartTax);
                $('#cart-total').text('EGP' + response.cartTotal);

                $('.js-cart-count').text(response.count);

            },

            error:function(xhr){
                console.log(xhr.responseText);
            }

        });

    });


    // =========================
    // Remove Item
    // =========================
    $('.remove-cart-form').submit(function (e) {

        e.preventDefault();

        let form = $(this);

        $.ajax({

            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),

            success: function (response) {

                form.closest('tr').remove();

                $('#cart-subtotal').text('EGP' + response.cartSubtotal);
                $('#cart-tax').text('EGP' + response.cartTax);
                $('#cart-total').text('EGP' + response.cartTotal);

                $('.js-cart-count').text(response.count);

                if(response.count == 0){
                    location.reload();
                }

            },

            error:function(xhr){
                console.log(xhr.responseText);
            }

        });

    });


    // =========================
    // Click Remove Icon
    // =========================
    $('.remove-cart').click(function (e) {

        e.preventDefault();

        $(this).closest('form').submit();

    });
    // =========================
// Empty Cart
// =========================
$('.empty-cart-form').submit(function(e){

    e.preventDefault();

    let form = $(this);

    $.ajax({

        url: form.attr('action'),
        type: "POST",
        data: form.serialize(),

        success:function(response){

            // تصفير عداد الهيدر
            $('.js-cart-count').text(0);

            // إعادة تحميل الصفحة لإظهار رسالة "No item found"
            location.reload();

        },

        error:function(xhr){
            console.log(xhr.responseText);
        }

    });

});

});
</script>
@endpush