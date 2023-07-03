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
    <div class="row justify-content-center mb-3">
        <div class="col-md-8">
            <!-- Create the form -->
            <form action="{{ route('teacher.saveModify', ['passportID' => $passportID, 'moduleID' => $module->id]) }}" method="POST">
                @csrf
                <div class="card gx-0">
                    <!-- Module -->
                    <div class="card-body row gx-0">
                        <div class="col-6 row border-1">
                            <div class="col-auto">
                                <label for="module" class="col-form-label">Module: </label>
                            </div>
                            <div class="col-auto">
                                <select id="module" name="data[module_id]" class="form-select" aria-label="Default select example" required>
                                    @foreach ($modules as $moduleNumber)
                                        <!-- If the current user is not an admin, hide the "head master" option  -->
                                        @if (!(Auth::user()->roles[0]->name == 'teacher' && $moduleNumber->id == 1))
                                            <option value="{{ $moduleNumber->id }}" {{ $moduleNumber->id == $module->module_id ? 'selected' : '' }}>{{ $moduleNumber->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Comment from the instructor/class teacher -->
                    <div>
                        <div class="card-header fw-bold border-top"><label for="description" class="mb-0 form-label">Merci de confirmer votre soutien par un commentaire</label></div>
                        <textarea type="text" id="description" name="data[description]" class="card-body form-control">{{ $module->description }}</textarea>
                    </div>

                    <!-- Choice from the instructor/class teacher -->
                    <div class="card-body border-bottom border-1 row gx-0">
                        <div class="col-6 row border-end border-1">
                            <div class="col-auto">
                                <label class="col-form-label fw-bold">Recommendation: </label>
                            </div>
                        </div>
                        <div class="col-6 px-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="data[choice]" id="dev" value="DEV" {{ $module->choice == 'DEV' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="dev">Développement d'applications (<span class="fw-bold">DEV</span>)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="data[choice]" id="infra" value="INFRA" {{ $module->choice == 'INFRA' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="infra">Exploitation et infrastructure(<span class="fw-bold">INFRA</span>)</label>
                            </div>
                        </div>
                    </div>

                    <!-- Signature from the instructor/class teacher -->
                    <div class="card-body row gx-0">
                        <div class="col-6 row border-end border-1">
                            <div class="col-auto">
                                <label for="acronym" class="col-form-label">Sigle: </label>
                            </div>
                            <div class="col-auto">
                                <input type="acronym" id="sign" name="data[acronym]" class="form-control @error('acronym') is-invalid @enderror" value="{{ $module->acronym }}" required>
                            </div>
                        </div>
                        <div class="col-6 row px-4">
                            <div class="col-auto">
                                <label for="sign" class="col-form-label">Signature: </label>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="sign" name="data[sign]" class="form-control @error('sign') is-invalid @enderror" value="{{ $module->sign }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Submit button -->
                <button class="btn btn-primary mt-1" type="submit">Envoyer</button>
            </form>
        </div>
    </div>
@endsection