@extends('layouts.app')

@section('content')
    <!-- Display error messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Changer les images</div>
                <div class="card-body">
                    <form action="{{ route('admin.index.saveUpdate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Change the first image -->
                        <div class="form-group row mb-3">
                            <label for="image_info" class="col-md-4 col-form-label text-md-end">Image info</label>
                            <div class="col-md-6">
                                <input type="file" name="image_info" id="image_info" class="form-control">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Change the second image -->
                        <div class="form-group row mb-3">
                            <label for="image_module" class="col-md-4 col-form-label text-md-end">Image module</label>
                            <div class="col-md-6">
                                <input type="file" name="image_module" id="image_module" class="form-control">
                                @error('image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Changer l'image</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
