@extends('backend.layouts.master')
@section('title',$brand->exists ? 'Edition du marque' : 'Ajout marque')

@section('main-content')

<div class="card">
    <h5 class="card-header">@yield('title')</h5>
    <div class="card-body">
        <form action="{{ route($brand->exists ? 'brand.update' : 'brand.store', $brand) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method($brand->exists ? 'put' : 'post')

            @include('backend.layouts.partials.input', [
            'type' => 'text',
            'name' => 'title',
            'label' => 'Nom de la marque',
            'value' => $brand->title,
            'placeholder' => 'Saisir le nom de la marque',
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
                    @if ($brand->exists)
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


@endpush
