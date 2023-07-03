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
            <!-- Links to different sorting routes -->
            <div class="row justify-content-evenly mb-3">
                <a class="btn btn-primary col-2" href="{{ route('admin.users', ['role' => null]) }}">Tous</a>
                <a class="btn btn-primary col-2" href="{{ route('admin.users', ['role' => 'admin']) }}">Admins</a>
                <a class="btn btn-primary col-2" href="{{ route('admin.users', ['role' => 'teacher']) }}">Professeurs</a>
                <a class="btn btn-primary col-2" href="{{ route('admin.users', ['role' => 'student']) }}">Élèves</a>
                <!-- New user button -->
                <a class="btn btn-primary col-2" href="{{ route('admin.user.create') }}">New user</a>
            </div>

            <!-- Sorting form -->
            <form action="{{ route('admin.users', isset($role) ? ['role' => $role] : '') }}" method="GET" class="accordion mb-3 " id="accordionSort">
            @csrf
                <div class="accordion-item col-10 mx-auto gx-0">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button {{ request('name') || request('actif') != ''  ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Filtre
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse {{ request('name') || request('actif') != '' ? 'show' : '' }}" data-bs-parent="#accordionSort">
                        <div class="accordion-body justify-content-evenly row gx-0">
                            <!-- Search by name -->
                            <div class="col-5 row">
                                <label for="name" class="col-form-label col-auto">Recherche par nom : </label>
                                <input type="text" name="name" id="name" class="form-control col-auto" value="{{ request('name') }}">
                            </div>

                            <!-- Search by is actif -->
                            <div class="col-5 row">
                                <label for="actif" class="col-form-label">Tri des actifs :</label>
                                <select name="actif" class="form-select" id="actif">
                                    <option {{ request('actif') == '' ? 'selected' : '' }} value="">Aucun tri</option>
                                    <option {{ request('actif') == '1' ? 'selected' : '' }} value="1">Actif</option>
                                    <option {{ request('actif') == '0' ? 'selected' : '' }} value="0">Non Actif</option>
                                </select>
                            </div>
                        </div>
                        <!-- Submit button -->
                        <div class="col-2 px-4 mb-3">
                            <button class="btn btn-primary mt-1" type="submit">Trier</button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Display users -->
            <div class="row justify-content-center">
                <div class="card">
                        @foreach ($users as $user)
                        <div class="card-body border border-1 {{ $user->passport ? 'pb-1' : 'pb-0'}} row">
                            <div class="col-1 border-end border-1">
                                id: <span class="fw-bold">{{ $user->id }}</span>
                            </div>
                            <div class="col border-end border-1">
                                <p class="col">{{ $user->name }}</p>
                            </div>
                            <div class="col border-end border-1">
                                {{ $user->email }}
                            </div>
                            <div class="col-2 border-end border-1">
                                Actif: {{ $user->is_active == 1 ? 'OUI' : 'NON' }}
                            </div>
                            <div class="col row border-1 justify-content-evenly">
                                <!-- Modification button -->
                                <a href="{{ route('admin.user.update', ['id' => $user->id]) }}" class="col-3 btn btn-primary" title="Modifier">
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </i>
                                </a>
                                <!-- Enable/Disable button -->
                                <button type="button" class="col-3 btn btn-warning" data-bs-toggle="modal" data-bs-target="#activateModal{{$user->id}}" title="Activer/Désactiver">
                                    @if ( $user->is_active == 1 )
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-slash" viewBox="0 0 16 16">
                                            <path d="M13.879 10.414a2.501 2.501 0 0 0-3.465 3.465l3.465-3.465Zm.707.707-3.465 3.465a2.501 2.501 0 0 0 3.465-3.465Zm-4.56-1.096a3.5 3.5 0 1 1 4.949 4.95 3.5 3.5 0 0 1-4.95-4.95ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm.256 7a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
                                        </svg>
                                    </i>
                                    @else
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check" viewBox="0 0 16 16">
                                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514ZM11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                            <path d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z"/>
                                        </svg>
                                    </i>
                                    @endif
                                </button>
                                <!-- Modal for enable/disable -->
                                <div class="modal fade" id="activateModal{{$user->id}}" data-bs-backdrop="static"  data-bs-keyboard="false"  tabindex="-1" aria-labelledby="activateModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="activateModalLabel">Attention !!!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if ( $user->is_active == 1 )
                                                    <p>Etes-vous sûr de vouloir désactiver l'utilisateur {{ $user->name }} ?</p>
                                                @else
                                                    <p>Etes-vous sûr de vouloir activer l'utilisateur {{ $user->name }} ?</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                                <a href="{{ route('admin.user.activate', ['id' => $user->id]) }}" class="btn btn-primary">Oui</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- delete button -->
                                <!-- <button type="button" class="col btn btn-danger" data-bs-toggle="modal" data-bs-target="#supprimerModal{{$user->id}}" title="Supprimer">
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                        </svg>
                                    </i>
                                </button>
                                <Modal 
                                <div class="modal fade" id="supprimerModal{{$user->id}}" data-bs-backdrop="static"  data-bs-keyboard="false"  tabindex="-1" aria-labelledby="supprimerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="supprimerModalLabel">Attention !!!</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Etes-vous sûr de vouloir supprimer l'utilisateur {{ $user->name }} ?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <a href="" class="btn btn-danger">Supprimer</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                            </div>

                            <!-- If the user is a student, display link button to their passport -->
                            <div class="mt-3">
                                @if ($user->passport)
                                    <form action="{{ route('teacher.search') }}" method="get" class="col-4">
                                        <input class="form-control me-2" type="hidden" name="searchPassport" value="{{ $user->passport->id }}" aria-label="Search">
                                        <button class="btn btn-primary input-group-text" type="submit">Passeport</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection