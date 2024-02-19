@extends('backend.layouts.master')
@section('title',$category->exists ? 'Edition du catégorie' : 'Ajout catégorie')

@section('main-content')

<div class="card">
    <h5 class="card-header">@yield('title')</h5>
    <div class="card-body">
        <form action="{{ route($category->exists ? 'category.update' : 'category.store', $category) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method($category->exists ? 'put' : 'post')

            @include('backend.layouts.partials.input', [
            'type' => 'text',
            'name' => 'title',
            'label' => 'Nom catégorie',
            'value' => $category->title,
            'placeholder' => 'Saisir le nom de la categorie',
            ])

            @include('backend.layouts.partials.input', [
            'type' => 'textarea',
            'name' => 'summary',
            'label' => 'Sommaire de la catégorie',
            'value' => $category->summary,
            'placeholder' => 'Saisir le sommaire de la catégories',
            ])

            <div class="form-group">
                <label for="is_parent">Est Parent:</label><br>
                <input type="checkbox" name='is_parent' id='is_parent' value='1' checked> Yes
            </div>
            {{-- {{$parent_cats}} --}}

            <div class="form-group d-none" id='parent_cat_div'>
                <label for="parent_id">Catégorie parent</label>
                <select name="parent_id" class="form-control">
                    <option value="">Veuillez sélectionner plusieurs catégories</option>
                    @foreach($parent_cats as $key=>$parent_cat)
                    <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
                    @endforeach
                </select>
            </div>

            @include('backend.layouts.partials.input', [
                'type' => 'file',
                'name' => 'photo',
                'label' => 'Photo de la catégorie',
                'value' => $category->photo,
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
                    @if ($category->exists)
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
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
      $('#summary').summernote({
        placeholder: "Saisir une courte déscription.....",
          tabsize: 2,
          height: 120
      });
    });
</script>

<script>
  $('#is_parent').change(function(){
    var is_checked=$('#is_parent').prop('checked');
    // alert(is_checked);
    if(is_checked){
      $('#parent_cat_div').addClass('d-none');
      $('#parent_cat_div').val('');
    }
    else{
      $('#parent_cat_div').removeClass('d-none');
    }
  })
</script>
@endpush
