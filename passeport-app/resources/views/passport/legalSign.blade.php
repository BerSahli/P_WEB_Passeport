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
            <!-- Legal representative(s) -->
            <div class="row justify-content-center mb-3">
                <div class="alert alert-danger col-11 mb-2" role="alert">Représentant(s) légal(légaux)</div>
                <!-- Create the form -->
                <form action="{{ route('save') }}" method="POST">
                    @csrf
                    <div class="card gx-0">

                        <!-- Representative's comment -->
                        <div>
                            <div class="card-header fw-bold"><label for="legal_comment" class="mb-0 form-label">Merci de confirmer votre soutien par un commentaire</label></div>
                            <textarea type="text" id="legal_comment" name="data[legal_comment]" class="card-body form-control @error('legal_comment') is-invalid @enderror" required></textarea>
                        </div>

                        <!-- Representative's signature-->
                        <div class="card-body row gx-0">
                            <div class="col-6 row border-end border-1">
                            </div>
                            <div class="col-6 row px-4">
                                <div class="col-auto">
                                    <label for="sign" class="col-form-label">Signature: </label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="sign" name="data[legal_sign]" class="form-control @error('legal_sign') is-invalid @enderror" value="" required>
                                </div>
                            </div>
                        </div>

                        <!-- Representative's signature and uuid of the passport -->
                        <div class="card-body row gx-0">
                            <div class="col-6 row border-end border-1">
                                <div class="col-auto">
                                    <label for="uuid" class="col-form-label">ID du passeport: </label>
                                </div>
                                <div class="col-auto">
                                <input class="form-control me-2 @error('uuid') is-invalid @enderror" type="text" id="uuid" name="data[uuid]" required>
                                </div>
                            </div>
                            <div class="col-6 row px-4">
                                <div class="col-auto">
                                    <label for="email" class="col-form-label">E-mail: </label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="email" name="data[email]" class="form-control @error('email') is-invalid @enderror" value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Submit button -->
                <button class="btn btn-primary mt-1" type="submit">Envoyer</button>
                </form>
            </div>
        </div>
    </div>
@endsection