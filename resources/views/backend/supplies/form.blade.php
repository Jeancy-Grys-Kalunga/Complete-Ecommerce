@extends('backend.layouts.master')
@section('title',$supplie->exists ? 'Edition fournisseur' : 'Ajout fournisseur')

@section('main-content')

<div class="card">
    <h5 class="card-header">@yield('title')</h5>
    <div class="card-body">
      <form action="{{ route($supplie->exists ? 'supplie.update' : 'supplie.store', $supplie) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        @method($supplie->exists ? 'put' : 'post')

        @include('backend.layouts.partials.input', [
          'type' => 'text',
          'name' => 'title',
          'label' => 'Nom du fournisseur',
          'value' => $supplie->name,
          'placeholder' => 'Saisir le nom du fournisseur',
      ])

          @include('backend.layouts.partials.input', [
            'type' => 'text',
            'name' => 'title',
            'label' => 'Adresse mail du fournisseur',
            'value' => $supplie->email,
            'placeholder' => 'Saisir le nom du fournisseur',
        ])

        @include('backend.layouts.partials.input', [
          'type' => 'file',
          'name' => 'thumbail',
          'label' => 'Photo du supermarché',
          'value' => $supplie->photo,
       ])

        <div class="form-group">
          <label for="user_id">Supermarchés</label>
          {{-- {{$brands}} --}}

          <select name="user_id" class="form-control">
              <option value="">Sélectionner Supermarché</option>
             @foreach($supermarkets as $supermarket)
              <option value="{{$supermarket->id}}">{{$supermarket->title}}</option>
             @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Annuler</button>
           <button class="btn btn-success" type="submit">
            @if ($supermarket->exists)
                  Modifier
            @else
                  Enregistrer
            @endif
           </button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $('#lfm').filemanager('image');


    $(document).ready(function() {
      $('#description').summernote({
        placeholder: "Saisir detail déscription.....",
          tabsize: 2,
          height: 150
      });
    });
    // $('select').selectpicker();

</script>


@endpush
