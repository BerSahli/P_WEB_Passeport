@extends('layouts.app')

@section('content')
<!-- Display success messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row justify-content-evenly mb-3">
                <a class="btn btn-primary col-2" href="{{ route('admin.modules', ['type' => null]) }}">Tous</a>
                <a class="btn btn-primary col-2" href="{{ route('admin.modules', ['type' => 'DEV']) }}">DEV</a>
                <a class="btn btn-primary col-2" href="{{ route('admin.modules', ['type' => 'INFRA']) }}">INFRA</a>
            </div>
            <div class="row justify-content-center">
                <div class="card">
                    <!-- Display all modules -->
                    @foreach ($modules as $module)
                        <div class="card-body border border-1 row">
                            <div class="col-1 border-end border-1">
                                id: <span class="fw-bold">{{ $module->id }}</span>
                            </div>
                            <div class="col border-end border-1">
                                <p class="col">{{ $module->name }}</p>
                            </div>
                            <div class="col border-end border-1">
                                @if ($module->type != '')
                                    Type: {{ $module->type }}
                                @endif
                            </div>
                            <div class="col row border-1 justify-content-evenly">
                                <!-- Modifiy button -->
                                <a href="{{ route('admin.module.update', ['id' => $module->id]) }}" class="col-3 btn btn-primary" title="Modifier">
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection