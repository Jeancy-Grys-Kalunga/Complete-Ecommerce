@extends('backend.layouts.master')
@section('title','Listes des Supermarchés')

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Liste des supermarchés</h6>
      <a href="{{route('supermarket.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Ajouter un supermarché</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($supermarkets)>0)
        <table class="table table-bordered" id="product-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>N°</th>
              <th>Nom du supermarché</th>
              <th>Description</th>
              <th>Adresse</th>
              <th>Photo</th>
              <th>Nom du Fournisseur</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

            @foreach($supermarkets as $supermarket)
              {{-- @php
              $sub_cat_info=DB::table('categories')->select('title')->where('id',$product->child_cat_id)->get();
              // dd($sub_cat_info);
              $brands=DB::table('brands')->select('title')->where('id',$product->brand_id)->get();
              @endphp --}}
                <tr>
                    <td>{{$supermarket->id}}</td>
                    <td>{{$supermarket->title}}</td>
                    <td>{{$supermarket->description}}
                    </td>
                    <td>{{ $supermarket->address }}</td>
                      <td>   @if($supermarket->thumbnail)
                          @php
                            $photo=explode(',',$supermarket->photo);
                            // dd($photo);
                          @endphp
                          <img src="{{$supermarket[0]}}" class="img-fluid zoom" style="max-width:80px" alt="{{$supermarket->thumbnail}}">
                      @else
                          <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid" style="max-width:80px" alt="avatar.png">
                      @endif </td>
                    <td>  {{$supermarket->fournisseur->name}}</td>

                    <td>
                        @if($supermarket->status=='active')
                            <span class="badge badge-success">{{$supermarket->status}}</span>
                        @else
                            <span class="badge badge-warning">{{$supermarket->status}}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('supermarket.edit',$supermarket->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{route('supermarket.destroy',[$supermarket->id])}}">
                      @csrf
                      @method('delete')
                          <button class="btn btn-danger btn-sm dltBtn" data-id={{$supermarket->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        <span style="float:right">{{$supermarkets->links()}}</span>
        @else
          <h6 class="text-center">Aucun supermarché trouvé !!! Veuillez en créer un nouveau</h6>
        @endif
      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
      .zoom {
        transition: transform .2s; /* Animation */
      }

      .zoom:hover {
        transform: scale(5);
      }
  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
  <script>

      $('#product-dataTable').DataTable( {
        "scrollX": false
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[10,11,12]
                }
            ]
        } );

        // Sweet alert

        function deleteData(id){

        }
  </script>
  <script>
      $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
          $('.dltBtn').click(function(e){
            var form=$(this).closest('form');
              var dataID=$(this).data('id');
              // alert(dataID);
              e.preventDefault();
              swal({
                    title: "Etès-vous sûr ?",
                    text: "Voulez-vous vraiment supprimer ce supermarché !",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                       form.submit();
                    } else {
                        swal("Supermarché supprimé avec succès !");
                    }
                });
          })
      })
  </script>
@endpush
