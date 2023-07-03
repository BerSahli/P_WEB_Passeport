<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
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
     * Display the page with all users
     *
     * @param \Illuminate\Http\Request $request Data for sorting
     * @param string $role Role of the user want to select
     * @return \Illuminate\Contracts\View\View
     */
    public function users(Request $request, $role = '')
    {
        $users = User::query();

        // If the research focuses on a specific role
        if ($role != '') {
            $users->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            });
        }

        // Search by name
        if($request->has('name')) {
            $users->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }

        // Sort by activation state
        if($request->filled('actif')) {
            $actifValue = $request->input('actif') ? 1 : 0;
            $users->where('is_active', $actifValue);
        }
        
        $users = $users->get();

        // return the view
        if ($role != '') {
            return view('admin.users', ['role' => $role], compact('users'));
        }
        return view('admin.users', ['users' => $users]);
    }

    /**
     * Activate or deactivate a user
     * 
     * @param int $id The ID of the user to activate or deactivate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateUser($id)
    {
        $user = User::find($id);

        if ($user->is_active == 1){
            $user->update([
                'is_active' => 0
            ]);
        } else {
            $user->update([
                'is_active' => 1
            ]);
        }
        return back();
    }

    /**
     * Diplay the form view to modify a user
     * 
     * @param int $id The ID of the user
     * @return \Illuminate\Contracts\View\View
     */
    public function updateUser($id)
    {
        $user = User::find($id);

        return view('admin.userUpdate', [
            'user' => $user,
        ]);
    }

    /**
     * Validates data for updating a user
     * 
     * @param array Data to be validated for the user update
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);
    }

    /**
     * Save the update to the database
     * 
     * @param \Illuminate\Http\Request $request The data to update
     * @param int $id The ID of the user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUpdateUser(Request $request, $id)
    {
        $data = $request->input('data');
        $user = User::find($id);

        // If the array is null or not there
        if ($data === null || !is_array($data)) {
            return redirect(route('admin.user.update', ['id' => $id]))->withErrors(['data' => 'Invalid data']);
        }

        // If the array's data is not conform
        if($this->validator($data)->fails()) {
            return redirect(route('admin.user.update', ['id' => $id]))->withErrors($this->validator($data))->withInput();
        }

        $users = User::all();
        foreach ($users as $userEmail) {
            if($userEmail->email == $data['email'] && $userEmail->id != $user->id){
                return redirect(route('admin.user.update', ['id' => $id]))->withErrors(['email' => 'The email has already been taken.'])->withInput();
            }
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect(route('admin.users'))->with('success', 'Utilisateur modifié avec succès !');
    }

    /**
     * Display the form view to create a new user
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function createUser()
    {
        $roles = Role::all()->sortDesc();
        return view('admin.userCreate', ['roles' => $roles]);
    }

    /**
     * Validates data for create a new user
     * 
     * @param array Data to be validated for the user update
     * @return \Illuminate\Validation\Validator
     */
    private function validatorNew(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);
    }

    /**
     * Save the new user to the database
     * 
     * @param \Illuminate\Http\Request $request The data to save
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveCreateUser(Request $request)
    {
        $data = $request->input('data');

        // If the array is null or not there
        if ($data === null || !is_array($data)) {
            return redirect(route('admin.user.create'))->withErrors(['data' => 'Invalid data']);
        }
        
        // If the array's data is not conform
        if($this->validatorNew($data)->fails()) {
            return redirect(route('admin.user.create'))->withErrors($this->validatorNew($data))->withInput();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        
        // Assign a role to the user
        $role = Role::findByName($data['role']);

        $user->assignRole($role);

        return redirect(route('admin.users'))->with('success', 'Utilisateur ajouté avec succès !');
    }

    /**
     * Retrieve all modules or modules based on type
     * 
     * @param string|null $type The type of modules to retrieve.
     * @return \Illuminate\Contracts\View\View
     */
    public function modules($type = null)
    {   
        // If type is not null
        if ($type != null) {
            $modules = Module::where('type', $type)->get();
            return view('admin.modules', ['modules' => $modules]);
        }

        $modules = Module::all();
        return view('admin.modules', ['modules' => $modules]);
    }
    
    /**
     * Diplay the form view to modify a module
     * 
     * @param int $id The ID of the module
     * @return \Illuminate\Contracts\View\View
     */
    public function updateModule($id)
    {
        $module = Module::find($id);

        return view('admin.moduleUpdate', [
            'module' => $module,
        ]);
    }

    /**
     * Validates data for updating a module
     * 
     * @param array Data to be validated for the module update
     * @return \Illuminate\Validation\Validator
     */
    private function validatorModule(array $data)
    {
        return Validator::make($data, [
            'module' => 'required|string|max:255',
            'type' => 'nullable|max:10',
        ]);
    }

    /**
     * Save the update to the database
     * 
     * @param \Illuminate\Http\Request $request The data to update
     * @param int $id The ID of the module
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUpdateModule(Request $request, $id)
    {
        $data = $request->input('data');
        $module = Module::find($id);

        // If the array is null or not there
        if ($data === null || !is_array($data)) {
            return redirect(route('admin.module.update', ['id' => $id]))->withErrors(['data' => 'Invalid data']);
        }

        // If the array's data is not conform
        if($this->validatorModule($data)->fails()) {
            return redirect(route('admin.module.update', ['id' => $id]))->withErrors($this->validatorModule($data))->withInput();
        }

        // Update
        if($data['type'] === null){
            $module->update([
                'name' => $data['module'],
                'type' => '',
            ]);
        } else {
            $module->update([
                'name' => $data['module'],
                'type' => $data['type'],
            ]);
        }

        return redirect(route('admin.modules'))->with('success', 'Utilisateur modifié avec succès !');
    }

    /**
     * Diplay the form view to modify the images of the welcome page
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function updateImage() 
    {
        return view('admin.image');
    }

    /**
     * Save the update
     * 
     * @param \Illuminate\Http\Request $request The data to update
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUpdateImage(Request $request)
    {
        if (!($request->hasFile('image_info') && $request->hasFile('image_module'))) {
            return back()->withErrors(['nothing' => 'Aucun fichier à transférer']);
        }

        // Validate data
        $request->validate([
            'image_info' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image_module' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Image 1 processing
        if ($request->hasFile('image_info')) {
            $image_info = $request->file('image_info');
            $imageName_info = 'image_info.png';

            $image_info->move(public_path('img'), $imageName_info);
        }

        // Image 2 processing
        if ($request->hasFile('image_module')) {
            $image_module = $request->file('image_module');
            $imageName_module = 'image_module.png';

            $image_module->move(public_path('img'), $imageName_module);
        }

        // Redirect the user to the welcome page with a success message
        return redirect(url('/'))->with('success', 'Les images ont été changées avec succès.');
    }
}
