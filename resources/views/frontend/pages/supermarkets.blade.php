@extends('frontend.layouts.master')
@section('title','Supermarchés')
@section('page','Supermarchés')
@section('main-content')
	<!-- Breadcrumbs -->
	@include('frontend.layouts.breadcrumbs')
	<!-- End Breadcrumbs -->

	<!-- Shopping Cart -->
	<div class="shopping-cart section">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<!-- Shopping Summery -->
					<table class="table shopping-summery">
						<thead>
							<tr class="main-hading">
                                <th></th>
								<th>SUPERMARCHE</th>
                                <th>DESCRIPTION</th>
								<th>ADRESSE</th>
								<th class="text-center">DISTANCE</>
							</tr>
						</thead>
						<tbody id="cart_item_list">
							<form action="{{route('cart.update')}}" method="POST">
								@csrf
                                @if(count($supermarkets)>0)
                                @foreach($supermarkets as $supermarket)
										<tr>
                                            <td>
                                            @if($supermarket->thumbnail)
                                            @php
                                              $photo=explode(',',$supermarket->photo);
                                              // dd($photo);
                                            @endphp
                                            <img src="{{$supermarket[0]}}" class="img-fluid zoom" style="max-width:80px" alt="{{$supermarket->thumbnail}}">
                                        @else
                                            <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid" style="max-width:80px" alt="avatar.png">
                                        @endif </td>
                                        <td>{{ $supermarket->title }}</td>
                                        <td>{!! html_entity_decode($supermarket->description) !!}
                                        </td>
                                        <td>{{ $supermarket->address }}</td>
											<td><!-- Input Order -->
												{{ round($supermarket->distance, 2) }} km
											</td>
										</tr>
									@endforeach
								@else
										<tr>
											<td class="text-center">
												Il n'y a aucun supermarché  disponible pour le moment . <a href="{{route('product-grids')}}" style="color:blue;">Continuer le shopping</a>
											</td>
										</tr>
								@endif

							</form>
						</tbody>
					</table>
					<!--/ End Shopping Summery -->
				</div>
			</div>
		</div>
	</div>
	<!--/ End Shopping Cart -->

	<!-- Start Shop Services Area  -->
	@include('frontend.layouts.services')
	<!-- End Shop Newsletter -->

	<!-- Start Shop Newsletter  -->
	@include('frontend.layouts.newsletter')
	<!-- End Shop Newsletter -->

@endsection
@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>


@endpush
