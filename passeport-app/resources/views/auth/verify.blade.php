@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Vérification de votre adresse email') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nouveau lien de vérification à été envoyé à votre adresse email.') }}
                        </div>
                    @endif

                    {{ __('Avant de continuer, veuillez vérifé votre email pour un lien de vérification.') }}
                    {{ __('Si vous n\'avez pas reçu d'email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Cliquez ici pour renvoyer un nouveau') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
