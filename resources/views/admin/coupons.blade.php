@extends('layouts.admin')
@section('content')
<style>.table-responsive{
    overflow-x: scroll;
    scrollbar-gutter: stable;
}

</style>
 <div class="main-content-inner">
                            <div class="main-content-wrap">
                                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                                    <h3>Coupons</h3>
                                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                                        <li>
                                            <a href="{{route('admin.index')}}">
                                                <div class="text-tiny">Dashboard</div>
                                            </a>
                                        </li>
                                        <li>
                                            <i class="icon-chevron-right"></i>
                                        </li>
                                        <li>
                                            <div class="text-tiny">Coupons</div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="wg-box ">
                                    <div class="flex items-center justify-between gap10 flex-wrap">
                                        <div class="wg-filter flex-grow">
                                            <form class="form-search">
                                                <fieldset class="name">
                                                    <input type="text" placeholder="Search here..." class="" name="name"
                                                        tabindex="2" value="" aria-required="true" required="">
                                                </fieldset>
                                                <div class="button-submit">
                                                    <button class="" type="submit"><i class="icon-search"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                        <a class="tf-button style-1 w208" href="{{route('admin.coupon.add')}}"><i
                                                class="icon-plus"></i>Add new</a>
                                    </div>
                                        <div class="table-responsive">
                                             @if(Session::has('status'))
                                                <p class="alert alert-success">{{session::get('status')}}</p>
                                           @endif
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th  class="text-center">Code</th>
                                                        <th  class="text-center">Type</th>
                                                        <th  class="text-center">Value</th>
                                                        <th  class="text-center">Cart Value</th>
                                                        <th  class="text-center">Expiry Date</th>
                                                        <th  class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($coupons as $coupon )
                                                    <tr>
                                                        <td  class="text-center">{{$coupon->id}}</td>
                                                        <td  class="text-center">{{$coupon->code}}</td>
                                                        <td  class="text-center">{{$coupon->type}}</td>
                                                        <td  class="text-center">{{ number_format($coupon->value, 0) }}</td>
                                                        <td  class="text-center">EGP {{ number_format($coupon->cart_value, 0) }}</td>
                                                        <td  class="text-center">{{$coupon->expiry_date}}</td>
                                                        <td  class="text-center align-middle">
                                                            <div class="list-icon-function justify-content-center">
                                                                <a href="{{route('admin.coupon.edit',['id'=>$coupon->id])}}">
                                                                    <div class="item edit">
                                                                        <i class="icon-edit-3"></i>
                                                                    </div>
                                                                </a>
                                                                <form action="{{route('admin.coupon.delete',['id'=>$coupon->id])}}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <div class="item text-danger delete">
                                                                        <i class="icon-trash-2"></i>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                     @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                          {{$coupons->links('pagination::bootstrap-5')}}
                                    </div>
                                </div>
                            </div>
                        </div>
@endsection
@push('scripts')
<script>
    $(function(){
        $('.delete').on('click',function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            swal({
                title :" Are You Sure?",
                text: "You want to delete this record?",
                type: "warning",
                buttons: ["No","Yes"],
                confirmButtonColor: '#dc3545',
            }).then(function(result){
                if(result){
                    form.submit();
                }
            })
        })
    })
</script>
    
@endpush