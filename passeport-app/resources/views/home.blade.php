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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Teacher/Admin Part -->
            @if ($user->roles[0]->name == 'teacher' || $user->roles[0]->name == 'admin')

                <!-- Sorting form -->
                <form action="{{ route('sort') }}" method="GET" class="accordion mb-3 " id="accordionSort">
                @csrf
                    <div class="accordion-item col-10 mx-auto gx-0">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button {{ request('name') || request('module') ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Filtre
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse {{ request('name') || request('module') ? 'show' : '' }}" data-bs-parent="#accordionSort">
                            <div class="accordion-body justify-content-evenly row gx-0">
                                <!-- Search by name -->
                                <div class="col-5 row">
                                    <label for="name" class="col-form-label col-auto">Recherche par nom : </label>
                                    <input type="text" name="name" id="name" class="form-control col-auto" value="{{ request('name') }}">
                                </div>

                                <!-- Search by modules -->
                                <div class="col-5 row">
                                <label for="module" class="col-form-label">Tri des modules :</label>
                                    <select name="module" class="form-select" id="module">
                                        @php
                                            if (Auth::user()->roles[0]->name != 'student'){
                                                $modules = $modules->sortByDesc('id');
                                            }
                                        @endphp
                                        <option value="">Aucun tri</option>
                                        @foreach ($modules as $module)
                                            @if (!(Auth::user()->roles[0]->name == 'teacher' && $module->id == 1))
                                                <option value="{{ $module->id }}" {{ request('module') == $module->id ? 'selected' : '' }}>{{ $module->name }}</option>
                                            @endif
                                        @endforeach
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

                <!-- Display student's passports, which have been modified by the teacher -->
                <div class="row justify-content-center">
                    <div class="alert alert-primary col-11" role="alert">Passports</div>
                    <div class="card">

                        <!-- If the data has been sorted -->
                        @if (isset($results))
                            @foreach ($results as $result)
                                <div class="card-body border-bottom border-1 row">
                                    <div class="col-4 border-end border-1">
                                        Passeport de {{$result->user->name}}
                                    </div>
                                    <div class="col-8 row justify-content-between">
                                        <div class="col-8">
                                            @foreach ($result->modules as $module)
                                                @if ($module->pivot->user_id == $user->id)
                                                {{$module->name}}<br>
                                                @endif
                                            @endforeach
                                        </div>
                                        <form action="{{ route('teacher.search') }}" method="get" class="col-4">
                                            <input class="form-control me-2" type="hidden" name="searchPassport" value="{{ $result->id }}" aria-label="Search">
                                            <button class="btn btn-primary input-group-text" type="submit">Modifier</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                        <!-- Sorted by the most recently modified passport by teacher  -->
                        @else
                            @php
                                $userPassports = $user->passports
                                    ->unique()
                                    ->sortByDesc(function ($passport) use ($user) {
                                        $lastModifiedModule = $passport->modules
                                            ->where('pivot.user_id', $user->id)
                                            ->sortByDesc('pivot.updated_at')
                                            ->first();

                                        return optional($lastModifiedModule)->pivot->updated_at;
                                    });
                            @endphp

                            <!-- Display all modules added by the teacher -->
                            @foreach ($userPassports as $passport)
                                <div class="card-body border-bottom border-1 row">
                                    <div class="col-4 border-end border-1">
                                        Passeport de {{$passport->user->name}}
                                    </div>
                                    <div class="col-8 row justify-content-between">
                                        <div class="col-8">
                                            @foreach ($passport->modules as $module)
                                                @if ($module->pivot->user_id == $user->id)
                                                {{$module->name}}<br>
                                                @endif
                                            @endforeach
                                        </div>
                                        <form action="{{ route('teacher.search') }}" method="get" class="col-4">
                                            <input class="form-control me-2" type="hidden" name="searchPassport" value="{{ $passport->id }}" aria-label="Search">
                                            <button class="btn btn-primary input-group-text" type="submit">Modifier</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            <!-- Student Part -->
            @elseif ($user->roles[0]->name == 'student')
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
                                    <div class="col-3 border-end border-1">Date: {{ $module->pivot->date }}</div>
                                    <div class="col-7 gx-4">Signature: {{ $module->pivot->sign }}</div>
                                </div>
                            </div>
                        </div>
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
                                    <div class="col-3 border-end border-1">Date: {{ $module->pivot->date }}</div>
                                    <div class="col-7 gx-4">Signature: {{ $module->pivot->sign }}</div>
                                </div>
                            </div>
                        </div>
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
                                    <div class="col-3 border-end border-1">Date: {{ $module->pivot->date }}</div>
                                    <div class="col-7 gx-4">Signature: {{ $module->pivot->sign }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@endsection
