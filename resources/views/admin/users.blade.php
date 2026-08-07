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
                <h3>All Users</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">All Users</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
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
                   
                </div>
                    <div class="table-responsive">
                        @if (Session::has('status'))
                            <p class="alert alert-success">{{ session::get('status') }}</p>
                        @endif
                        <table class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th class="text-center">Id</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Mobile</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="text-center">{{ $user->id }}</td>
                                        <td class="text-center">{{ $user->name }}</td>
                                        <td class="text-center" style="word-break: break-word;">{{ $user->email }}</td>
                                        <td  class="text-center">{{ $user->mobile }}</td>
                                        <td class="text-center">{{ $user->created_at }}</td>
                                        <td class="text-center align-middle">
                                            <div class="list-icon-function justify-content-center ">
                                                <form action="{{route('admin.user.delete',['id'=>$user->id])}}" method="POST">
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
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: " Are You Sure?",
                    text: "You want to delete this record?",
                    type: "warning",
                    buttons: ["No", "Yes"],
                    confirmButtonColor: '#dc3545',
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                })
            })
        })
    </script>
@endpush
