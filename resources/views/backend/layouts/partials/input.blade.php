@php
    $type ??= '';
    $class ??= null;
    $name ??= '';
    $value ??= '';
    $label ??= '';
    $placeholder ??= '';

@endphp

<div @class(["form-group", $class])>
    <label for="{{ $name }}" class="col-form-label">{{ $label }} <span class="text-danger">*</span></label>
    @if ($type === 'textarea')
        <textarea class="form-control @error($name) is-invalid @enderror" name="{{ $name }}" id="{{ $name }}">{{ old($name, $value) }}</textarea>
    @elseif($type === 'file')
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-btn">
                <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                <i class="fa fa-picture-o"></i> Choisir
                </a>
            </span>
        <input  class="form-control" type="text" @error($name) is-invalid @enderror"
        name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}">
      </div>
      <div id="holder" style="margin-top:15px;max-height:100px;"></div>
    </div>
    @else
        <input class="form-control @error($name) is-invalid @enderror" type="{{ $type }}"
            name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}">
    @endif
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>
