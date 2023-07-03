<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Passport;
use App\Models\PassportHasModule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the form view to create a new module in a passport
     *
     * @param string $passportID The UUID of the passport
     * @return \Illuminate\Contracts\View\View
     */
    public function create($passportID)
    {
        $moduls = Module::all()->sortByDesc('id');

        return view('module.create',[
            'passportID' => $passportID,
            'modules' => $moduls,
        ]);
    }

    /**
     * Validates data for updating or create a module
     * 
     * @param array Data to be validated for the module
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'description' => 'nullable|string|max:255',
            'choice' => 'required|string|max:10',
            'acronym' => 'required|string|max:10',
            'sign' => 'required|string|max:50',
        ]);
    }

    /**
     * Save the new module to the database
     * 
     * @param \Illuminate\Http\Request $request The data to save
     * @param string $passportID The UUID of the passport
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveCreate(Request $request, $passportID)
    {
        $data = $request->input('data');

        // If the array is null or not there
        if ($data === null || !is_array($data)) {
            return redirect(route('teacher.createModule', ['passportID' => $passportID]))->withErrors(['data' => 'Invalid data']);
        }
        
        // If the array's data is not conform
        if($this->validator($data)->fails()) {
            return redirect(route('teacher.createModule', ['passportID' => $passportID]))->withErrors($this->validator($data))->withInput();
        }

        $newModule = PassportHasModule::create([
            'description' => $data['description'],
            'choice' => $data['choice'],
            'acronym' => $data['acronym'],
            'date' => Carbon::now(),
            'sign' => $data['sign'],
            'module_id' => $data['module_id'],
            'user_id' => Auth::user()->id,
            'passport_id' => $passportID,
        ]);

        return redirect()->route('teacher.search', ['searchPassport' => $passportID])->with('success', 'Module ajouté avec succès !');
    }

    /**
     * Display the form view to modify a module in a passport
     *
     * @param string $passportID The UUID of the passport
     * @param int $moduleID The ID of the module to modify
     * @return \Illuminate\Contracts\View\View
     */
    public function Modify($passportID, $moduleID)
    {
        $modules = Module::all()->sortByDesc('id');
        $module = PassportHasModule::where('id', $moduleID)->first();

        return view('module.modify',[
            'passportID' => $passportID,
            'module' => $module,
            'modules' => $modules,
        ]);
    }

    /**
     * Save the update to the database
     *
     * @param \Illuminate\Http\Request $request The data to modify
     * @param string $passportID The UUID of the passport
     * @param int $moduleID The ID of the module to modify
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveModify(Request $request, $passportID, $moduleID)
    {
        $data = $request->input('data');

        // If the array is null or not there
        if ($data === null || !is_array($data)) {
            return redirect(route('teacher.modifyModule', ['passportID' => $passportID, 'moduleID' => $moduleID]))->withErrors(['data' => 'Invalid data']);
        }
        
        // If the array's data is not conform
        if($this->validator($data)->fails()) {
            return redirect(route('teacher.modifyModule', ['passportID' => $passportID, 'moduleID' => $moduleID]))->withErrors($this->validator($data))->withInput();
        }

        $module = PassportHasModule::where('id', $moduleID)->first();

        $module->update([
            'description' => $data['description'],
            'choice' => $data['choice'],
            'acronym' => $data['acronym'],
            'date' => Carbon::now(),
            'sign' => $data['sign'],
            'module_id' => $data['module_id'],
        ]);
        return redirect()->route('teacher.search', ['searchPassport' => $passportID])->with('success', 'Module modifié avec succès !');
    }

    /**
     * Delete a module to the database
     *
     * @param string $passportID The UUID of the passport
     * @param int $moduleID The ID of the module to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($passportID, $moduleID)
    {
        $module = PassportHasModule::where('id', $moduleID)->first();

        if ($module) {
            $module->delete();
            return redirect()->route('teacher.search', ['searchPassport' => $passportID])->with('success', 'Module supprimé avec succès !');
        } else {
            return redirect()->route('teacher.search', ['searchPassport' => $passportID])->withErrors(['suppresion' => 'Une erreur est survenu lors de la suppresion']);
        }
    }
}
