<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
     * Open Dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Display User Profile.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return view('admin.profile', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update Profile.
     *
     * @param ProfileUpdateRequest $request
     * @param int $id
     * @return Application|RedirectResponse|Redirector
     */
    public function updateProfile(ProfileUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        try {
            DB::transaction(function () use ($request, $user) {
                if ($request->hasFile('photo')) {
                    try {
                        // remove the image locally.
                        unlink(public_path('/images/' . $user->photo_path));
                    } catch (\Exception $exception) {
                        // Todo: To handel this.
                    }
                    $path = Str::of('Portrait');
                    // Save the file locally in the public/images folder under a new folder named /Portrait
                    $photo = $request->file('photo');
                    $photo_path = $photo->storeAs($path, $photo->getClientOriginalName(), 'images');
                }
                $user->firstName = $request->firstName;
                $user->lastName = $request->lastName;
                $user->phone_number = $request->phone_number;
                $user->address = $request->address;
                $user->summery = $request->summery;
                $user->linkedin = $request->linkedin;
                $user->instagram = $request->instagram;
                $user->github = $request->github;
                $user->photo_path = $photo_path ?? $user->photo_path;
                $user->save();
            });
            return redirect('/admin/index')->with('success', 'Profile info had been edited Successfully.');
        } catch (\Exception $exception) {
            // Back to form with errors
            dd($exception->getMessage());
            return redirect('/admin/profile')
                ->withErrors($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
