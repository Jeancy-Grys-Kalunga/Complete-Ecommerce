@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Commande      <a href="{{route('order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> Generate PDF</a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>N° </th>
            <th>N° Commande</th>
            <th>Nom</th>
            <th>E-mail</th>
            <th>Quantité</th>
            <th>Montant Total</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->order_number}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->quantity}}</td>
            <td>{{number_format($order->total_amount,2)}} FC</td>
            <td>
                @if($order->status=='new')
                  <span class="badge badge-primary">Nouvelle</span>
                @elseif($order->status=='process')
                  <span class="badge badge-warning">En cours</span>
                @elseif($order->status=='delivered')
                  <span class="badge badge-success">Livrée</span>
                @else
                  <span class="badge badge-danger">Annuléé</span>
                @endif
            </td>
            <td>
                <a href="{{route('order.edit',$order->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{route('order.destroy',[$order->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>

        </tr>
      </tbody>
    </table>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">INFORMATION DE LA COMMANDE</h4>
              <table class="table">
                    <tr class="">
                        <td>N° Commande</td>
                        <td> : {{$order->order_number}}</td>
                    </tr>
                    <tr>
                        <td>Date Commande</td>
                        <td> : {{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                        <td>Quantité</td>
                        <td> : {{$order->quantity}}</td>
                    </tr>
                    <tr>
                        <td>Status de la Commande</td>
                        <td> : @if($order->status=='new')
                            <span class="badge badge-primary">Nouvelle</span>
                          @elseif($order->status=='process')
                            <span class="badge badge-warning">En cours</span>
                          @elseif($order->status=='delivered')
                            <span class="badge badge-success">Livrée</span>
                          @else
                            <span class="badge badge-danger">Annuléé</span>
                          @endif</td>
                    </tr>
                   
                    <tr>
                      <td>Coupon</td>
                      <td> : {{number_format($order->coupon,2)}} FC</td>
                    </tr>
                    <tr>
                        <td>Montant Total</td>
                        <td> :{{number_format($order->total_amount,2)}} FC</td>
                    </tr>
                    <tr>
                        <td>Méthode de Paiement</td>
                        <td> : @if($order->payment_method=='cod') Payer à la livraison @else Mobile Money @endif</td>
                    </tr>
                    <tr>
                        <td>Status du paiement</td>
                        <td> : @if($order->payment_status=='unpaid')
                            <span class="badge badge-danger">Non payée</span>
                          @else
                          <span class="badge badge-success">Payée</span>
                          @endif</td>
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">INFORMATION DE LA LIVRAISON</h4>
              <table class="table">
                    <tr class="">
                        <td>Nom</td>
                        <td> : {{$order->first_name}} {{$order->last_name}}</td>
                    </tr>
                    <tr>
                        <td>E-mail</td>
                        <td> : {{$order->email}}</td>
                    </tr>
                    <tr>
                        <td>N° Tél.</td>
                        <td> : {{$order->phone}}</td>
                    </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
