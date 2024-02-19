@extends('backend.layouts.master')
@section('title',$banner->exists ? 'Edition Bannière' : 'Ajout Bannière')

@section('main-content')

<div class="card">
    <h5 class="card-header">@yield('title')</h5>
    <div class="card-body">
        <form action="{{ route($banner->exists ? 'banner.update' : 'banner.store', $banner) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method($banner->exists ? 'put' : 'post')

            @include('backend.layouts.partials.input', [
            'type' => 'text',
            'name' => 'title',
            'label' => 'Nom de la bannière',
            'value' => $banner->title,
            'placeholder' => 'Saisir le nom de la bannière',
            ])

            @include('backend.layouts.partials.input', [
            'type' => 'textarea',
            'name' => 'description',
            'label' => 'La description de la bannière',
            'value' => $banner->description,
            'placeholder' => 'Saisir la description de la bannière',
            ])

            @include('backend.layouts.partials.input', [
            'type' => 'file',
            'name' => 'photo',
            'label' => 'Photo de la bannière',
            'value' => $banner->photo,
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
                    @if ($banner->exists)
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
    $('#description').summernote({
      placeholder: "Saisir une courte déscription.....",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush
