@extends('backend.layouts.master')
@section('title',$supermarket->exists ? 'Edition du supermarché' : 'Ajout du supermarché')

@section('main-content')

<div class="card">
    <h5 class="card-header">@yield('title')</h5>
    <div class="card-body">
      <form action="{{ route($supermarket->exists ? 'supermarket.update' : 'supermarket.store', $supermarket) }}" method="post"
        enctype="multipart/form-data">
        @csrf
         @method($supermarket->exists ? 'put' : 'post')

        @include('backend.layouts.partials.input', [
          'type' => 'text',
          'name' => 'title',
          'label' => 'Nom du supermarché',
          'value' => $supermarket->title,
          'placeholder' => 'Saisir le nom du supermarché',
      ])

      @include('backend.layouts.partials.input', [
         'type' => 'textarea',
         'name' => 'description',
         'label' => 'Description du supermarché',
         'value' => $supermarket->description,
       ])

      @include('backend.layouts.partials.input', [
         'type' => 'textarea',
         'name' => 'address',
         'label' => 'Adresse du supermarché',
         'value' => $supermarket->address,
       ])




        <div class="form-group">
          <label for="user_id">Fournisseurs</label>
          {{-- {{$brands}} --}}

          <select name="user_id" class="form-control">
              <option value="">Sélectionner Fournisseur</option>
             @foreach($fournisseurs as $fournisseur)
              <option value="{{$fournisseur->id}}">{{$fournisseur->name}}</option>
             @endforeach
          </select>
        </div>
      
        {{-- <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                  <i class="fa fa-picture-o"></i> Choisir
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="thumbnail" value="{{old('thumbnail')}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('thumbnail')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> --}}
      

        @include('backend.layouts.partials.input', [
         'type' => 'file',
         'name' => 'thumbail',
         'label' => 'Photo du supermarché',
         'value' => $supermarket->thumbail,
       ])


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
