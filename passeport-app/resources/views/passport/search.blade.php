@extends('layouts.app')

@section('content')
    <!-- Display success/error messages -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible">
        {{ session('success') }}
    </div>
    @elseif ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row row-col-2 justify-content-between">
                <div class="col-8">
                    <h2>Passeport de l'élève: {{ $passport->user->name }}</h2>
                </div>
                <div class="col-4">
                    <a href="{{ Route('teacher.createModule', ['passportID' => $passport->id]) }}" class="btn btn-primary">Nouveau module</a>
                </div>
            </div>
            <!-- Student's data -->
            <div class="row justify-content-center mb-3">
                <div class="alert alert-primary col-11 mb-2" role="alert">Apprenti-e</div>
                <div class="card gx-0">
                    <!-- Name, class and motivation of the student -->
                    <div class="card-body row border-bottom border-1 gx-0">
                        <div class="col-6 border-end border-1">Nom de l'élève: {{ $passport->user->name }}</div>
                        <div class="col-6 gx-4">Classe: {{ $passport->class }}</div>
                    </div>
                    <div class="card-header fw-bold"><p class="mb-0">Quelle est ma motivation ? Pourquoi je pense avoir le profil pour la voie choisie ?</p></div>
                    <div class="card-body">
                        <p class="card-text mx-3">{{ $passport->motivation }}</p>
                    </div>

                    <!-- Show modules the student needs and their grade for each one, according to their choice -->
                    <div class="card-header fw-bold border-top"><p class="mb-0">Mes notes de module</p></div>
                    <div class="card-body mx-auto">
                        @if ($passport->student_choice == 'DEV')
                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item fw-bold">DEV</li>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($modules as $module)
                                    @if ($module->type == 'DEV')
                                        <li class="list-group-item">{{ $module->name }}</li>
                                        @if ($i == 1)
                                            <li class="list-group-item border-1">{{ $passport->first_note }}</li>
                                        @elseif ($i == 2)
                                            <li class="list-group-item">{{ $passport->second_note }}</li>
                                        @elseif ($i == 3)
                                            <li class="list-group-item">{{ $passport->third_note }}</li>
                                        @endif
                                        @php
                                            $i++;
                                        @endphp
                                    @endif
                                @endforeach
                            </ul>
                        @elseif ($passport->student_choice == 'INFRA')
                            <ul class="list-group list-group-horizontal">
                                <li class="list-group-item fw-bold">INFRA</li>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($modules as $module)
                                    @if ($module->type == 'INFRA')
                                        <li class="list-group-item">{{ $module->name }}</li>
                                        @if ($i == 1)
                                            <li class="list-group-item border-1">{{ $passport->first_note }}</li>
                                        @elseif ($i == 2)
                                            <li class="list-group-item">{{ $passport->second_note }}</li>
                                        @elseif ($i == 3)
                                            <li class="list-group-item">{{ $passport->third_note }}</li>
                                        @endif
                                        @php
                                            $i++;
                                        @endphp
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Choice and sign of the student -->
                    <div class="card-header fw-bold border-top"><p class="mb-0">Mon choix :</p></div>
                    <div class="card-body row gx-0">
                        <div class="col-6 border-end border-1">
                            @if ($passport->student_choice == 'DEV')
                                <div>Développement d'applications (<span class="fw-bold">DEV</span>)</div>
                            @elseif ($passport->student_choice == 'INFRA')
                                <div>Exploitation et infrastructure(<span class="fw-bold">INFRA</span>)</div>
                            @else
                                <div>Aucun choix</div>
                            @endif
                        </div>
                        <div class="col-6 gx-4">Date & Signature: {{ $passport->student_date }}@if ($passport->student_sign != NULL),@endif {{ $passport->student_sign }}</div>
                    </div>
                </div>
            </div>

            <!-- Comment from the legal representative -->
            <div class="row justify-content-center mb-3">
                <div class="alert alert-danger col-11 mb-2" role="alert">Représentant(s) légal(légaux)</div>
                <div class="card gx-0">
                    <div class="card-header fw-bold"><p class="mb-0">Merci de confirmer votre soutien par un commentaire</p></div>
                    <div class="card-body">
                        <p class="card-text mx-3">{{ $passport->legal_comment }}</p>
                    </div>
                    <div class="card-body row border-top border-1 gx-0">
                        <div class="col-6 border-end border-1">Date: {{ $passport->legal_date }}</div>
                        <div class="col-6 gx-4">Signature: {{ $passport->legal_sign }}</div>
                    </div>
                </div>
            </div>

            <!-- Display all modules added by a teacher -->
            @foreach ($passport->modules as $module)
                @if ($module->name != 'Maître principal' && $module->name != 'Maître de classe')
                    <div class="row justify-content-center mb-3">
                        <div class="alert alert-warning col-11 mb-2" role="alert">Formateur module {{ $module->name }}</div>
                        <div class="card gx-0">
                            <div class="card-header fw-bold"><p class="mb-0">Merci de confirmer votre soutien par un commentaire</p></div>
                            <div class="card-body">
                                <p class="card-text mx-3">{{ $module->pivot->description }}</p>
                            </div>
                            <div class="card-body border-top row gx-0">
                                <div class="col-6 border-end border-1 fw-bold">
                                    Décision :
                                </div>
                                <div class="col-6 gx-4">
                                    @if ($module->pivot->choice == 'DEV')
                                        <div>Développement d'applications (<span class="fw-bold">DEV</span>)</div>
                                    @elseif ($module->pivot->choice == 'INFRA')
                                        <div>Exploitation et infrastructure(<span class="fw-bold">INFRA</span>)</div>
                                    @else
                                        <div>Aucun choix</div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body row border-top border-1 gx-0">
                                <div class="col-2 border-end border-1">sigle: {{ $module->pivot->acronym }}</div>
                                <div class="col-3 border-end border-1 gx-4">Date: {{ $module->pivot->date }}</div>
                                <div class="col-7 gx-4">Signature: {{ $module->pivot->sign }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- If the current user is the one who created the module or is an admin, display the modify and delete buttons -->
                    @if ($module->pivot->user_id == Auth::user()->id || Auth::user()->roles[0]->name == 'admin')
                        <div class="col-6 mb-3">
                            <a href="{{ Route('teacher.modifyModule', ['passportID' => $passport->id, 'moduleID' => $module->pivot->id]) }}" class="btn btn-primary">Modifer module</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal{{$module->pivot->id}}">Supprimer module</button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$module->pivot->id}}" data-bs-backdrop="static"  data-bs-keyboard="false"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Attention !!!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Etes-vous sûr de vouloir supprimer le module {{ $module->name}} du passeport de {{ $passport->user->name }} ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <a href="{{ Route('teacher.delete', ['passportID' => $passport->id, 'moduleID' => $module->pivot->id]) }}" class="btn btn-danger">Supprimer module</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach

            <!-- Display the module added by the class teacher -->
            @foreach ($passport->modules as $module)
                @if ($module->name == 'Maître de classe')
                    <div class="row justify-content-center mb-3">
                        <div class="alert alert-info col-11 mb-2" role="alert">Maître de classe</div>
                        <div class="card gx-0">
                            <div class="card-header fw-bold"><p class="mb-0">Merci de confirmer votre soutien par un commentaire</p></div>
                            <div class="card-body">
                                <p class="card-text mx-3">{{ $module->pivot->description }}</p>
                            </div>
                            <div class="card-body border-top row gx-0">
                                <div class="col-6 border-end border-1 fw-bold">
                                    Décision :
                                </div>
                                <div class="col-6 gx-4">
                                    @if ($module->pivot->choice == 'DEV')
                                        <div>Développement d'applications (<span class="fw-bold">DEV</span>)</div>
                                    @elseif ($module->pivot->choice == 'INFRA')
                                        <div>Exploitation et infrastructure(<span class="fw-bold">INFRA</span>)</div>
                                    @else
                                        <div>Aucun choix</div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body row border-top border-1 gx-0">
                                <div class="col-2 border-end border-1">sigle: {{ $module->pivot->acronym }}</div>
                                <div class="col-3 border-end border-1 gx-4">Date: {{ $module->pivot->date }}</div>
                                <div class="col-7 gx-4">Signature: {{ $module->pivot->sign }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- If the current user is the one who created the module or is an admin, display the modify and delete buttons -->
                    @if ($module->pivot->user_id == Auth::user()->id || Auth::user()->roles[0]->name == 'admin')
                        <div class="col-6 mb-3">
                            <a href="{{ Route('teacher.modifyModule', ['passportID' => $passport->id, 'moduleID' => $module->pivot->id]) }}" class="btn btn-primary">Modifer module</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal{{$module->pivot->id}}">Supprimer module</button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$module->pivot->id}}" data-bs-backdrop="static"  data-bs-keyboard="false"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Attention !!!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Etes-vous sûr de vouloir supprimer le module {{ $module->name}} du passeport de {{ $passport->user->name }} ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <a href="{{ Route('teacher.delete', ['passportID' => $passport->id, 'moduleID' => $module->pivot->id]) }}" class="btn btn-danger">Supprimer module</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach

            <!-- Display the module added by the head master -->
            @foreach ($passport->modules as $module)
                @if ($module->name == 'Maître principal')
                    <div class="row justify-content-center mb-3">
                        <div class="alert alert-success col-11 mb-2" role="alert">Maître principal</div>
                        <div class="card gx-0">
                            <div class="card-body row gx-0">
                                <div class="col-6 border-end border-1 fw-bold">
                                    Décision :
                                </div>
                                <div class="col-6 gx-4">
                                    @if ($module->pivot->choice == 'DEV')
                                        <div>Développement d'applications (<span class="fw-bold">DEV</span>)</div>
                                    @elseif ($module->pivot->choice == 'INFRA')
                                        <div>Exploitation et infrastructure(<span class="fw-bold">INFRA</span>)</div>
                                    @else
                                        <div>Aucun choix</div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body row border-top border-1 gx-0">
                                <div class="col-2 border-end border-1">sigle: {{ $module->pivot->acronym }}</div>
                                <div class="col-3 border-end border-1 gx-4">Date: {{ $module->pivot->date }}</div>
                                <div class="col-7 gx-4">Signature: {{ $module->pivot->sign }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- If the current user is an admin, display the modify and delete buttons -->
                    @if (Auth::user()->roles[0]->name == 'admin')
                        <div class="col-6 mb-3">
                            <a href="{{ Route('teacher.modifyModule', ['passportID' => $passport->id, 'moduleID' => $module->pivot->id]) }}" class="btn btn-primary">Modifer module</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal{{$module->pivot->id}}">Supprimer module</button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal{{$module->pivot->id}}" data-bs-backdrop="static"  data-bs-keyboard="false"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Attention !!!</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Etes-vous sûr de vouloir supprimer la section {{ $module->name}} du passeport de {{ $passport->user->name }} ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <a href="{{ Route('teacher.delete', ['passportID' => $passport->id, 'moduleID' => $module->pivot->id]) }}" class="btn btn-danger">Supprimer module</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
@endsection