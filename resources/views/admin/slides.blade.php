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
                <h3>Slides</h3>
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
                        <div class="text-tiny">Slides</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box" >
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
                    <a class="tf-button style-1 w208" href="{{route('admin.slide.add')}}"><i class="icon-plus"></i>Add new</a>
                </div>
                     <div class="table-responsive">
                     @if(Session::has('status'))
                        <p class="alert alert-success">{{session::get('status')}}</p>
                     @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Id</th>
                                <th class="text-center">Image</th>
                                <th class="text-center">Tagline</th>
                                <th class="text-center">Title</th>
                                <th class="text-center">Subtitle</th>
                                <th class="text-center">Link</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($slides as $slide)
                            <tr>
                                <td class="text-center">{{$slide->id}}</td>
                                <td class="pname text-center align-middle">
                                    <div class="image justify-content-center">
                                        <img src="{{asset('uploads/slides')}}/{{$slide->image}}" alt="{{$slide->title}}" class="image">
                                    </div>
                                </td>
                                <td class="text-center">{{$slide->tagline}}</td>
                                <td class="text-center">{{$slide->title}}</td>
                                <td class="text-center">{{$slide->subtitle}}</td>
                                <td  class="text-center"> <a href="{{ $slide->link }}" target="_blank">
                                          <i class="icon-link"></i>
                                </a>
                               </td>
                                <td class="text-center align-middle">
                                    <div class="list-icon-function justify-content-center">
                                        <a href="{{route('admin.slide.edit',['id'=>$slide->id])}}">
                                            <div class="item edit">
                                                <i class="icon-edit-3"></i>
                                            </div>
                                        </a>
                                        <form action="{{route('admin.slide.delete',['id'=>$slide->id])}}" method="POST">
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
                    {{$slides->links('pagination::bootstrap-5')}}

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
