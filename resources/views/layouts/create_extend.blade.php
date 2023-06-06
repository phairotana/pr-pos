@extends('layouts.blank')

@section('content')
{{-- Content --}}
<!-- <div class="p-t-10"></div> -->
<form 
    method="post"
    id="create-form-{{ Request::get('ajax') ?? ''}}"
    class="bold-labels"
    enctype="multipart/form-data"
>
    
    @if(view()->exists('vendor.backpack.crud.form_content'))
        @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
    @else
        @include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
    @endif
    {{-- <code>
        <pre>
{{var_dump($fields) }}

        </pre>
    </code>
     --}}
</form>   
{{-- Content --}}
@endsection