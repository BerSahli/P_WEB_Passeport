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
                <div class="card-header">{{ __('Nouvel utilisateur') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.user.saveCreate') }}">
                    @csrf
                        <!-- Select the role type for the new user -->
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">Rôle: </label>
                            <div class="col-md-6">
                                <select id="role" name="data[role]" class="form-select" aria-label="Default select example" required>
                                    @foreach ($roles as $role)
                                        @if (!($role->name == 'student'))
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Name of the user -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Prénom et Nom') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="data[name]" value="" required autofocus>
                            </div>
                        </div>

                        <!-- Email of the user -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Adresse email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="data[email]" value="" required>
                            </div>
                        </div>

                        <!-- Password of the user -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="data[password]" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirmer le Mot de passe') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="data[password_confirmation]" required>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Créer') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
