@extends('layouts.app')

@section('content')
<!-- Display success/error messages -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible">
        {{ session('success') }}
    </div>
@elseif ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif
<!-- Show images of information on choice -->
<div class="">
    <img src="{{ URL::asset('/img/image_info.png') }}" alt="information général" class="mx-auto d-block"><br><br>
    <img src="{{ URL::asset('/img/image_module.png') }}" alt="information choix modules" class="mx-auto d-block">
</div>
@endsection