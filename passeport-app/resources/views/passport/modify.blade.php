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
            <!-- Student -->
            <div class="row justify-content-center mb-3">
                <div class="alert alert-primary col-11 mb-2" role="alert">Apprenti-e</div>
                <!-- Create form -->
                <form action="{{ route('home.save') }}" method="POST">
                    @csrf
                    <div class="card gx-0">
                        <!-- Name + class -->
                        <div class="card-body row border-bottom border-1 gx-0">
                            <div class="col-6 border-end border-1">Nom de l'élève: {{ $passport->user->name }}</div>
                            <div class="col-6 row gx-4">
                                <div class="col-auto">
                                    <label for="class" class="col-form-label">Classe: </label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="class" name="data[class]" class="form-control @error('class') is-invalid @enderror" value="{{ $passport->class }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Motivation -->
                        <div class="card-header fw-bold border-top"><label for="motivation" class="mb-0 form-label">Quelle est ma motivation ? Pourquoi je pense avoir le profil pour la voie choisie ?</label></div>
                        <textarea type="text" id="motivation" name="data[motivation]" class="card-body form-control @error('motivation') is-invalid @enderror" required>{{ $passport->motivation }}</textarea>

                        <!-- Choice -->
                        <div class="card-header fw-bold border-top"><p class="mb-0">Mon choix :</p></div>
                        <div class="card-body row gx-0">
                            <div class="col-6 border-end border-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="data[student_choice]" id="infra" value="INFRA" checked required>
                                    <label class="form-check-label" for="infra">Exploitation et infrastructure(<span class="fw-bold">INFRA</span>)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="data[student_choice]" id="dev" value="DEV" {{ ($passport->student_choice=="DEV")? "checked" : "" }} required>
                                    <label class="form-check-label" for="dev">Développement d'applications (<span class="fw-bold">DEV</span>)</label>
                                </div>
                            </div>
                            <div class="col-6 row gx-4">
                                <div class="col-auto">
                                    <label for="sign" class="col-form-label">Signature: </label>
                                </div>
                                <div class="col-auto">
                                    <input type="text" id="sign" name="data[student_sign]" class="form-control @error('student_sign') is-invalid @enderror" value="{{ $passport->student_sign }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Grades -->
                        <div class="card-header fw-bold"><p class="mb-0">Mes notes de module</p></div>
                        <div class="card-body row mx-auto justify-content-around">
                            <div class="col-auto row">
                                <div class="col-auto">
                                    <label for="note1" class="col-form-label note-label"></label>
                                </div>
                                <div class="col-auto">
                                    <select id="note1" name="data[first_note]" class="form-select" aria-label="Default select example" required>
                                        <option {{ ($passport->first_note==1)? "selected" : "" }} value="1">1</option>
                                        <option {{ ($passport->first_note==1.5)? "selected" : "" }} value="1.5">1.5</option>
                                        <option {{ ($passport->first_note==2)? "selected" : "" }} value="2">2</option>
                                        <option {{ ($passport->first_note==2.5)? "selected" : "" }} value="2.5">2.5</option>
                                        <option {{ ($passport->first_note==3)? "selected" : "" }} value="3">3</option>
                                        <option {{ ($passport->first_note==3.5)? "selected" : "" }} value="3.5">3.5</option>
                                        <option {{ ($passport->first_note==4)? "selected" : "" }} value="4">4</option>
                                        <option {{ ($passport->first_note==4.5)? "selected" : "" }} value="4.5">4.5</option>
                                        <option {{ ($passport->first_note==5)? "selected" : "" }} value="5">5</option>
                                        <option {{ ($passport->first_note==5.5)? "selected" : "" }} value="5.5">5.5</option>
                                        <option {{ ($passport->first_note==6)? "selected" : "" }} value="6">6</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto row">
                                <div class="col-auto">
                                    <label for="note2" class="col-form-label note-label"></label>
                                </div>
                                <div class="col-auto">
                                    <select id="note2" name="data[second_note]" class="form-select" aria-label="Default select example" required>
                                        <option {{ ($passport->second_note==1)? "selected" : "" }} value="1">1</option>
                                        <option {{ ($passport->second_note==1.5)? "selected" : "" }} value="1.5">1.5</option>
                                        <option {{ ($passport->second_note==2)? "selected" : "" }} value="2">2</option>
                                        <option {{ ($passport->second_note==2.5)? "selected" : "" }} value="2.5">2.5</option>
                                        <option {{ ($passport->second_note==3)? "selected" : "" }} value="3">3</option>
                                        <option {{ ($passport->second_note==3.5)? "selected" : "" }} value="3.5">3.5</option>
                                        <option {{ ($passport->second_note==4)? "selected" : "" }} value="4">4</option>
                                        <option {{ ($passport->second_note==4.5)? "selected" : "" }} value="4.5">4.5</option>
                                        <option {{ ($passport->second_note==5)? "selected" : "" }} value="5">5</option>
                                        <option {{ ($passport->second_note==5.5)? "selected" : "" }} value="5.5">5.5</option>
                                        <option {{ ($passport->second_note==6)? "selected" : "" }} value="6">6</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto row">
                                <div class="col-auto">
                                    <label for="note3" class="col-form-label note-label"></label>
                                </div>
                                <div class="col-auto">
                                    <select id="note3" name="data[third_note]" class="form-select" aria-label="Default select example" required>
                                        <option {{ ($passport->third_note==1)? "selected" : "" }} value="1">1</option>
                                        <option {{ ($passport->third_note==1.5)? "selected" : "" }} value="1.5">1.5</option>
                                        <option {{ ($passport->third_note==2)? "selected" : "" }} value="2">2</option>
                                        <option {{ ($passport->third_note==2.5)? "selected" : "" }} value="2.5">2.5</option>
                                        <option {{ ($passport->third_note==3)? "selected" : "" }} value="3">3</option>
                                        <option {{ ($passport->third_note==3.5)? "selected" : "" }} value="3.5">3.5</option>
                                        <option {{ ($passport->third_note==4)? "selected" : "" }} value="4">4</option>
                                        <option {{ ($passport->third_note==4.5)? "selected" : "" }} value="4.5">4.5</option>
                                        <option {{ ($passport->third_note==5)? "selected" : "" }} value="5">5</option>
                                        <option {{ ($passport->third_note==5.5)? "selected" : "" }} value="5.5">5.5</option>
                                        <option {{ ($passport->third_note==6)? "selected" : "" }} value="6">6</option>
                                    </select>
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

@push('scripts')
<script>
    // Écouteur d'événement lorsqu'une option de choix est sélectionnée
    document.querySelectorAll('input[name="data[student_choice]"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            updateNoteLabels();
        });
    });

    // Fonction pour mettre à jour les étiquettes des notes
    function updateNoteLabels() {
        var choice = document.querySelector('input[name="data[student_choice]"]:checked').value;

        var modulesData = JSON.parse('{!! addslashes(json_encode($modules)) !!}');
        var noteLabels = document.querySelectorAll('.note-label');
        var moduleIndex = 0;

        switch (choice) {
            case 'DEV':
                modulesData.forEach(element => {
                    if (element['type'] === 'DEV') {
                        noteLabels[moduleIndex].textContent = element['name'];
                        moduleIndex++;
                    }
                });
                break;
            case 'INFRA':
                modulesData.forEach(element => {
                    if (element['type'] === 'INFRA') {
                        noteLabels[moduleIndex].textContent = element['name'];
                        moduleIndex++;
                    }
                });
                break;
            default:
                noteLabels[moduleIndex].textContent = "";
                moduleIndex++;
                break;
        }
    }

    // Appel initial pour mettre à jour les étiquettes des notes en fonction du choix sélectionné
    updateNoteLabels();
</script>
@endpush