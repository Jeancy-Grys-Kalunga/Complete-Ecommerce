@extends('frontend.layouts.master')

@section('title','Suivre Commande')
@section('page','Suivie de la commande')
@section('main-content')
   <!-- Breadcrumbs -->
@include('frontend.layouts.breadcrumbs')
<!-- End Breadcrumbs -->

<section class="tracking_box_area section_gap py-5">
    <div class="container">
        <div class="tracking_box_inner">
            <p>Veuillez Saisir l'ID de votre commande pour avoir des informations de votre commande.
                </p>
            <form class="row tracking_form my-4" action="{{route('product.track.order')}}" method="post" novalidate="novalidate">
              @csrf
                <div class="col-md-8 form-group">
                    <input type="text" class="form-control p-2"  name="order_number" placeholder="Saisir le N° de la commande">
                </div>
                <div class="col-md-8 form-group">
                    <button type="submit" value="submit" class="btn submit_btn">Suivre commande</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
