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
        <h6 class="m-0 font-weight-bold text-primary float-left">Liste des fournisseurs</h6>
        <a href="{{route('supplie.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Ajouter un fournisseur</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(count($supplies)>0)
            <table class="table table-bordered" id="banner-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom du fournisseur</th>
                        <th>Adresse mail</th>
                        <th>Photo</th>
                        <th>Supermarché</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($supplies as $supplie)
                    
                    <tr>
                        <td>{{$supplie->fournisseur->id}}</td>
                        <td>{{$supplie->fournisseur->name}}</td>
                        <td>{{$supplie->fournisseur->email}}
                        </td>
                        <td> @if($supplie->fournisseur->photo)
                            @php
                            $photo=explode(',',$supplie->fournisseur->photo);
                            // dd($photo);
                            @endphp
                            <img src="{{$supplie[0]}}" class="img-fluid zoom" style="max-width:80px" alt="{{$supplie->fournisseur->photo}}">
                            @else
                            <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid" style="max-width:80px" alt="avatar.png">
                            @endif </td>

                        <td>
                            @if($supplie->title)
                            {{$supplie->title}}
                            @else
                            Non attribué
                            @endif
                        <td>
                            @if($supplie->fournisseur->status=='active')
                            <span class="badge badge-success">{{$supplie->fournisseur->status}}</span>
                            @else
                            <span class="badge badge-warning">{{$supplie->fournisseur->status}}</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{route('supplie.edit',$supplie->fournisseur->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{route('supplie.destroy',[$supplie->fournisseur->id])}}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm dltBtn" data-id={{$supplie->fournisseur->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <span style="float:right">{{$supplies->links()}}</span>
            @else
            <h6 class="text-center">Aucun fournisseur trouvé !!! Veuillez en créer un nouveau</h6>
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
        transform: scale(3.2);
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
      
      $('#banner-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[3,4]
                }
            ]
        } );

        // Sweet alert

        function deleteData(id){
            
        }
  </script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.dltBtn').click(function(e) {
            var form = $(this).closest('form');
            var dataID = $(this).data('id');
            // alert(dataID);
            e.preventDefault();
            swal({
                    title: "Etès-vous sûr ?"
                    , text: "Voulez-vous vraiment supprimer ce fournisseur !"
                    , icon: "warning"
                    , buttons: true
                    , dangerMode: true
                , })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    } else {
                        swal("Fournisseur supprimé avec succès !");
                    }
                });
        })
    })

</script>
@endpush
