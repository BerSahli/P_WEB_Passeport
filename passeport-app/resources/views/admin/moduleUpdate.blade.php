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
                <div class="card-header">Modification</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.module.saveUpdate', ['id' => $module->id]) }}">
                    @csrf
                        <!-- Name of the module -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Module</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="data[module]" value="{{ $module->name }}" required autofocus>
                            </div>
                        </div>

                        <!-- Type of the module -->
                        <div class="row mb-3">
                            <label for="type" class="col-md-4 col-form-label text-md-end">Type: </label>
                            <div class="col-md-6">
                                <select id="type" name="data[type]" class="form-select" aria-label="Default select example" required>
                                        <option {{ $module->type == 'DEV' ? 'selected' : '' }} value="DEV">DEV</option>
                                        <option {{ $module->type == 'INFRA' ? 'selected' : '' }} value="INFRA">INFRA</option>
                                        <option {{ $module->type == '' ? 'selected' : '' }} value="">Aucun</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Modifier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
