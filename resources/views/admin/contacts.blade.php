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
                <h3>All Messages</h3>
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
                        <div class="text-tiny">All Messages</div>
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
                            <p class="alert alert-success">{{ Session::get('status') }}</p>
                        @endif
                        <table class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Message</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td class="text-center">{{ $contact->id }}</td>
                                        <td class="text-center">{{ $contact->name }}</td>
                                        <td class="text-center">{{ $contact->phone }}</td>
                                        <td  class="text-center" style="word-break: break-word;" >{{ $contact->email }}</td>
                                        <td  class="text-center" style="word-break: break-word;">{{ $contact->comment }}</td>
                                        {{-- <td  class="text-center"> <a href="{{  $contact->comment }}" target="_blank">
                                          <i class="icon-link"></i>
                                        </a>
                                        </td> --}}
                                        <td class="text-center">{{ $contact->created_at }}</td>
                                        <td class="text-center align-middle">
                                            <div class="list-icon-function justify-content-center ">
                                                <form action="{{route('admin.contact.delete',['id'=>$contact->id])}}" method="POST">
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
                    {{ $contacts->links('pagination::bootstrap-5') }}
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
