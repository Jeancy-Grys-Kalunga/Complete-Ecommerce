@extends('backend.layouts.master')
@section('title','Ajout Tag')
@section('main-content')

<div class="card">
    <h5 class="card-header">Ajouter Post Tag</h5>
    <div class="card-body">
      <form method="post" action="{{route('post-tag.store')}}">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Titre</label>
          <input id="inputTitle" type="text" name="title" placeholder="Saisir titre"  value="{{old('title')}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="status" class="col-form-label">Status</label>
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
           <button class="btn btn-success" type="submit">Enregistrer</button>
        </div>
      </form>
    </div>
</div>

@endsection
