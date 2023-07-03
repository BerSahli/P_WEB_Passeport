<?php

namespace App\Http\Controllers;

use App\Mail\EmailPassportConfirmation;
use App\Models\Module;
use App\Models\Passport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Get a passport with a id
     * 
     * @param Request id of the passport
     * @return \Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('searchPassport');

        // search the passport in de db
        $passport = Passport::where('id', $searchTerm)->first();
        $modules = Module::all();

        // Verify if the result is find
        if ($passport){
            return view('passport.search', ['passport' => $passport, 'modules' => $modules]);
        } else{
            return redirect()->back()->withErrors('Aucun passeport trouvé pour : '.$searchTerm);
        }
    }

    /**
     * Display the form view to modify a passport for a student
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function modify()
    {
            $user = Auth::user();
            $modules = Module::all();
            $passport = $user->passport;
            return view('passport.modify',[
                'passport' => $passport,
            ],compact('modules'));
    }

    /**
     * Validates data for updating a passport filled by a student
     * 
     * @param array Data to be validated for the passport
     * @return \Illuminate\Validation\Validator
     */
    private function validatorStudent(array $data)
    {
        return Validator::make($data, [
            'class' => 'required|string|max:10',
            'motivation' => 'required|string|max:255',
            'student_choice' => 'required|string|max:10',
            'student_sign' => 'required|string|max:50',
        ]);
    }

    /**
     * Save the updating passport filled by a student to the database
     * 
     * @param \Illuminate\Http\Request $request The data to save
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveStudent(Request $request)
    {
        $data = $request->input('data');

        // If the array is null or not there
        if ($data === null || !is_array($data)) {
            return redirect(route('home.modify'))->withErrors(['data' => 'Invalid data']);
        }
        
        // If the array's data is not conform
        if($this->validatorStudent($data)->fails()) {
            return redirect(route('home.modify'))->withErrors($this->validatorStudent($data))->withInput();
        }

        $passport = Passport::findOrFail(Auth::user()->passport->id);

        $passport->update([
            'class' => $data['class'],
            'motivation' => $data['motivation'],
            'student_choice' => $data['student_choice'],
            'first_note' => $data['first_note'],
            'second_note' => $data['second_note'],
            'third_note' => $data['third_note'],
            'student_date' => Carbon::now(),
            'student_sign' => $data['student_sign'],
        ]);

        return redirect('/home')->with('success', 'Modifications soumis avec succès !');
    }

    /**
     * Display the form view to modify a passport for a legal representative
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function sign()
    {
        if (Auth::guest() || Auth::user()->roles[0]->name != 'student'){
            return view('passport.legalSign');
        } else{
            return redirect(route('home'))->withErrors(['accessDenied' => 'Vous n\'avez pas accès à cette page']);
        }
    }

    /**
     * Validates data for updating a passport filled by a legal representative
     * 
     * @param array Data to be validated for the passport
     * @return \Illuminate\Validation\Validator
     */
    private function validatorSign(array $data)
    {
        return Validator::make($data, [
            'uuid' => 'required|max:36|exists:passports,id',
            'email' => 'required|email|max:255',
            'legal_comment' => 'required|string|max:255',
            'legal_sign' => 'required|string|max:50',
        ]);
    }

    /**
     * Save the updating passport filled by a legal representative to the database
     * 
     * @param \Illuminate\Http\Request $request The data to save
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSign(Request $request)
    {
        $data = $request->input('data');

        // If the array is null or not there
        if ($data === null || !is_array($data)) {
            return redirect(route('sign'))->withErrors(['data' => 'Invalid data']);
        }
        
        // If the array's data is not conform
        if($this->validatorSign($data)->fails()) {
            return redirect(route('sign'))->withErrors($this->validatorSign($data))->withInput();
        }

        $passport = Passport::findOrFail($data['uuid']);

        if ($passport){
            $token = Str::random(40);
            $confirmationUrl = route('confirm.passport', [
                'token' => $token,
                'data' => json_encode($data), // Inclure les données $data en tant que paramètre
            ]);
            $passport['confirmation_token'] = $token;
            $passport->save();

            try {
                Mail::to($data['email'])->send(new EmailPassportConfirmation($passport, $confirmationUrl));
        
                return redirect(url('/'))->with('success', 'Un email vous a été envoyé. Veuillez vérifier votre boîte de réception.');
            } catch (\Exception  $e) {
                return redirect(url('/'))->withErrors((['mailError' => 'Une erreur est survenue lors de l\'envoie de l\'email']));
            }
        }

        return redirect(route('sign'))->withErrors(['uuid', 'Passeport introuvable']);
    }

    /**
     * Confirm the save of updating passport filled by a legal representative to the database
     * 
     * @param \Illuminate\Http\Request $request The data to save
     * @param string $token The token to confirm modification of the passport
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmPassport(Request $request, $token)
    {
        $data = $request->query('data');
        $data = json_decode($data, true);
        $passport = Passport::where('confirmation_token', $token)->first();

        if ($passport) {
            $passport->update([
                'legal_comment' => $data['legal_comment'],
                'legal_date' => Carbon::now(),
                'legal_sign' => $data['legal_sign'],
                'confirmation_token' => NULL,
            ]);

            return redirect(url('/'))->with('success', 'Modification de passeport confirmée avec succès !');
        }

        return redirect(url('/'))->withErrors('Jeton de confirmation invalide.');
    }
}
