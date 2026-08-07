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
                                    <h3>Orders</h3>
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
                                            <div class="text-tiny">Orders</div>
                                        </li>
                                    </ul>
                                </div>
                                   <div class="wg-box">
                                       <div class="flex items-center justify-between gap10 flex-wrap">
                                           <div class="wg-filter flex-grow">
                                               <form class="form-search">
                                                   <fieldset class="name">
                                                       <input type="text" placeholder="Search here..." name="name">
                                                   </fieldset>
                                                   <div class="button-submit">
                                                       <button type="submit">
                                                          <i class="icon-search"></i>
                                                       </button>
                                                   </div>
                                               </form>
                                           </div>
                                       </div>

                                       <div class="table-responsive">
                                           <table class="table table-striped table-bordered">
                                               <thead>
                                                   <tr>
                                                       <th style="width:8%" class="text-center">Order No</th>
                                                       <th style="width:15%" class="text-center">Name</th>
                                                       <th style="width:12%" class="text-center">Phone</th>
                                                       <th style="width:10%" class="text-center">Subtotal</th>
                                                       <th style="width:8%" class="text-center">Tax</th>
                                                       <th style="width:10%" class="text-center">Total</th>
                                                       <th style="width:10%" class="text-center">Status</th>
                                                       <th style="width:12%" class="text-center">Order Date</th>
                                                       <th style="width:7%" class="text-center">Items</th>
                                                       <th style="width:12%" class="text-center">Delivered</th>
                                                       <th style="width:6%" class="text-center">Action</th>
                                                   </tr>
                                               </thead>

                                               <tbody>
                                                   @foreach($orders as $order)
                                                   <tr>
                                                       <td class="text-center">{{$order->id}}</td>
                                                       <td class="text-center">{{$order->name}}</td>
                                                       <td class="text-center">{{$order->phone}}</td>
                                                       <td class="text-center">  EGP {{ number_format($order->subtotal, 0) }}</td>
                                                       <td class="text-center">EGP {{ number_format($order->tax, 0) }}</td>
                                                       <td class="text-center">EGP {{ number_format($order->total, 0) }}</td>
                                                       <td class="text-center">
                                                          @if ($order->status == 'delivered')
                                                              <span class="badge bg-success">Delivered</span>
                                                          @elseif ($order->status == 'canceled')
                                                              <span class="badge bg-danger">Canceled</span>
                                                          @else
                                                              <span class="badge bg-warning">Ordered</span>
                                                          @endif
                                                       </td>
                                                       <td class="text-center">{{$order->created_at}}</td>
                                                       <td class="text-center">{{$order->orderItems->count()}}</td>
                                                       <td class="text-center">{{$order->delivered_date }}</td>
                                                       <td class="text-center">
                                                           <a href="{{route('admin.order.details',['order_id'=>$order->id])}}">
                                                               <div class="list-icon-function view-icon">
                                                                   <div class="item eye">
                                                                       <i class="icon-eye"></i>
                                                                   </div>
                                                               </div>
                                                           </a>
                                                       </td>
                                                   </tr>
                                                   @endforeach
                                               </tbody>
                                           </table>
                                       </div>

    <div class="divider"></div>

    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
        {{$orders->links('pagination::bootstrap-5')}}
    </div>
</div>
                                    <div class="divider"></div>
                                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                        {{$orders->links('pagination::bootstrap-5')}}

                                    </div>
                                </div>
                            </div>
                        </div>
@endsection