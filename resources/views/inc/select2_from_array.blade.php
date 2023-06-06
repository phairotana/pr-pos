<div class="{{ $wrapperClass ?? 'form-group col-12 col-sm-6 col-md-12' }}">

  <label for="">{{$name}}
    @if(isset($required))
      <span class="text-danger">*</span>
    @endif
  </label>

  @if (isset($class))
    <select class="form-control {{$class}}_element hidden-reset-filters" name="{{ $name }}">  
  @else
    <select class="form-control select2_element" name="{{ $name }}">  
  @endif
      @if(isset($placeholder))
        <option value="">{{ $placeholder != '' ? $placeholder: '-' }}</option>
      @endif
      @foreach($options as $k => $v)

        <option value="{{ $k }}" {{ $k == session('search.'.$name) ? 'selected' : '' }}>{{ $v }}</option>

      @endforeach

    </select>

</div>