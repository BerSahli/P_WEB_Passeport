<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Passport;
use App\Models\PassportHasModule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $modules = Module::all();
        $passport = $user->passport;
        return view('home',[
            'user' => $user,
            'passport' => $passport,
            'modules' => $modules,
        ]);
    }

    /**
     * Show the application dashboard with sorted users.
     * 
     * @param \Illuminate\Http\Request $request The data to sort
     * @return @return \Illuminate\Contracts\View\View
     */
    public function sort(Request $request)
    {
        if ($request->input('name') == "" && $request->input('module') == "") {
            return redirect('home');
        }

        $user = Auth::user();
        $modules = Module::all();
        $query = Passport::query();

        // Search by name
        if ($request->has('name')) {
            $name = $request->input('name');
            $query
            ->select('passports.*')
            ->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'LIKE', '%' . $name . '%');
            })
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Search by module
        if ($request->has('module')) {
            $moduleId = $request->input('module');
            if ($moduleId != "") {
                $query->whereHas('modules', function ($query) use ($moduleId, $user) {
                    $query->where('module_id', $moduleId)
                    ->whereHas('users', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
                });
            }
        }

        // Display results
        $results = $query->get()->unique();
        return view('home',[
            'user' => $user,
            'modules' => $modules,
        ], compact('results'));
    }
}
