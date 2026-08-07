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
            <h3>Brands</h3>

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
                    <div class="text-tiny">Brands</div>
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
                                   name="name"
                                   value="">
                        </fieldset>

                        <div class="button-submit">
                            <button type="submit">
                                <i class="icon-search"></i>
                            </button>
                        </div>

                    </form>
                </div>

                <a class="tf-button style-1 w208"
                   href="{{route('admin.brand.add')}}">
                    <i class="icon-plus"></i>
                    Add new
                </a>

            </div>

            @if(Session::has('status'))
                <p class="alert alert-success mt-3">
                    {{ Session::get('status') }}
                </p>
            @endif

            <div class="table-responsive">

                <table class="table table-striped table-bordered">

                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Slug</th>
                            <th class="text-center">Products</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($brands as $brand)

                        <tr>

                            <td class="text-center">
                                {{$brand->id}}
                            </td>

                            <td class="pname text-center">

                                <div class="image">
                                    <img src="{{ asset('uploads/brands/'.$brand->image) }}"
                                         alt="{{$brand->name}}"
                                         class="image">
                                </div>

                                <div class="name">
                                    <a href="#" class="body-title-2">
                                        {{$brand->name}}
                                    </a>
                                </div>

                            </td>

                            <td class="text-center">
                                {{$brand->slug}}
                            </td>

                            <td class="text-center">
                                <a href="#" target="_blank">
                                    {{ $brand->products_count }}
                                </a>
                            </td>

                            <td class="text-center align-middle">

                                <div class="list-icon-function justify-content-center">

                                    <a href="{{route('admin.brand.edit',['id'=>$brand->id])}}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>

                                    <form action="{{route('admin.brand.delete',['id'=>$brand->id])}}" method="POST">
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
                {{$brands->links('pagination::bootstrap-5')}}
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