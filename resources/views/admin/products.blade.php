@extends('layouts.admin');
@section('content');
<style>
.pname {
    vertical-align: middle !important;
}

.table-responsive{
    overflow-x: auto;
}

</style>


<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Products</h3>

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
                    <div class="text-tiny">Products</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">

            <div class="flex items-center justify-between gap10 flex-wrap">

                <div class="wg-filter flex-grow">

                    <form class="form-search">

                        <fieldset class="name">
                            <input type="text"
                                   placeholder="Search here..."
                                   class=""
                                   name="name">
                        </fieldset>

                        <div class="button-submit">
                            <button type="submit">
                                <i class="icon-search"></i>
                            </button>
                        </div>

                    </form>

                </div>

                <a href="{{route('admin.product.add')}}"
                   class="tf-button style-1 w208">

                    <i class="icon-plus"></i>

                    Add New

                </a>

            </div>

            @if(Session::has('status'))
                <p class="alert alert-success mt-3">
                    {{Session::get('status')}}
                </p>
            @endif

            <div class="table-responsive">

                <table class="table table-striped table-bordered">

                    <thead>

                    <tr>

                        <th class="text-center" style="width:5%">#</th>

                        <th style="width:30%">Product</th>

                        <th class="text-center" style="width:12%">Category</th>

                        <th class="text-center" style="width:10%">Brand</th>

                        <th class="text-center" style="width:12%">Price</th>

                        <th class="text-center" style="width:10%">Stock</th>

                        <th class="text-center" style="width:8%">Qty</th>

                        <th class="text-center" style="width:8%">Featured</th>

                        <th class="text-center" style="width:10%">Action</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($products as $product)

                    <tr>

                        <td class="text-center align-middle">
                            {{$product->id}}
                        </td>

                        <td class="align-middle">

                            <div class="d-flex align-items-center gap-3">

                                <img src="{{asset('uploads/products/thumbnails/'.$product->image)}}"
                                     style="width:70px;height:70px;border-radius:10px;object-fit:cover">

                                <div>

                                    <div class="body-title-2">
                                        {{$product->name}}
                                    </div>

                                    <div class="text-tiny">
                                        {{$product->slug}}
                                    </div>

                                </div>

                            </div>

                        </td>

                        <td class="text-center align-middle">
                            {{$product->category->name}}
                        </td>

                        <td class="text-center align-middle">
                            {{$product->brand->name}}
                        </td>

                        <td class="text-center align-middle">

                            <strong>
                             EGP {{ number_format($product->regular_price, 0) }}
                            </strong>

                            @if($product->sale_price)

                                <br>

                                <small class="text-danger">
                                    Sale : ${{$product->sale_price}}
                                </small>

                            @endif

                        </td>

                        <td class="text-center align-middle">

                            @if($product->stock_status == 'instock')

                                <span class="badge bg-success px-3 py-2">
                                    In Stock
                                </span>

                            @else

                                <span class="badge bg-danger px-3 py-2">
                                    Out Stock
                                </span>

                            @endif

                        </td>

                        <td class="text-center align-middle">
                            {{$product->quantity}}
                        </td>

                        <td class="text-center align-middle">

                            @if($product->featured)

                                <span class="badge bg-primary px-3 py-2">
                                    Featured
                                </span>

                            @else

                                <span class="badge bg-secondary px-3 py-2">
                                    Normal
                                </span>

                            @endif

                        </td>

                        <td class="text-center align-middle">

                            <div class="list-icon-function justify-content-center">

                             

                                <a href="{{route('admin.product.edit',['id'=>$product->id])}}">
                                    <div class="item edit">
                                        <i class="icon-edit-3"></i>
                                    </div>
                                </a>

                                <form action="{{route('admin.product.delete',['id'=>$product->id])}}" method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button class="item delete border-0 bg-transparent text-danger">

                                        <i class="icon-trash-2"></i>

                                    </button>

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

                {{$products->links('pagination::bootstrap-5')}}

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