@extends('backend.layouts.master')
@section('title','Listes de Catégorie Supermarché')

@section('main-content')
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Liste catégories des Supermarchés</h6>
        <a href="{{route('superMarketCategory.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Ajouter nouvelle catégorie</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if(count($superMarketCategories)>0)
            <table class="table table-bordered" id="product-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom de la catégorie</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($superMarketCategories as $superMarketCategory)
                    
                    <tr>
                        <td>{{$superMarketCategory->id}}</td>
                        <td>{{$superMarketCategory->title}}</td>
                        <td> @if($superMarketCategory->photo)
                            @php
                            $photo=explode(',',$superMarketCategory->photo);
                            // dd($photo);
                            @endphp
                            <img src="{{$superMarketCategory[0]}}" class="img-fluid zoom" style="max-width:80px" alt="{{$superMarketCategory->photo}}">
                            @else
                            <img src="{{asset('backend/img/thumbnail-default.jpg')}}" class="img-fluid" style="max-width:80px" alt="avatar.png">
                            @endif 
                        </td>
                        <td>
                            @if($superMarketCategory->status=='active')
                            <span class="badge badge-success">{{$superMarketCategory->status}}</span>
                            @else
                            <span class="badge badge-warning">{{$superMarketCategory->status}}</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{route('superMarketCategory.edit',$superMarketCategory->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{route('superMarketCategory.destroy',[$superMarketCategory->id])}}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm dltBtn" data-id={{$superMarketCategory->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <span style="float:right">{{$superMarketCategories->links()}}</span>
            @else
            <h6 class="text-center">Aucune catégorie trouvée pour cet type de supermarché !!! Veuillez en créer une nouvelle</h6>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<style>
    div.dataTables_wrapper div.dataTables_paginate {
        display: none;
    }

    .zoom {
        transition: transform .2s;
        /* Animation */
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
    $('#product-dataTable').DataTable({
        "scrollX": false "columnDefs": [{
            "orderable": false
            , "targets": [10, 11, 12]
        }]
    });

    // Sweet alert

    function deleteData(id) {

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
                    , text: "Voulez-vous vraiment supprimer cette catégorie de supermarché !"
                    , icon: "warning"
                    , buttons: true
                    , dangerMode: true
                , })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    } else {
                        swal("Catégorie de supermarché supprimé avec succès !");
                    }
                });
        })
    })

</script>
@endpush
