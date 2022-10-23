<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EduAddUpdateRequest;
use App\Http\Requests\SectionAddUpdateRequest;
use App\Models\Education;
use App\Models\Section;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;


class EducationController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $eduSections = Education::with('section')->Paginate(10);
        //dd($eduSections);
        return view('admin.education.index',compact('eduSections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.education.view');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SectionAddUpdateRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(SectionAddUpdateRequest $request)
    {
        try {
            // Safely perform set of DB related queries if fail rollback all.
            DB::transaction(function () use ($request){
                $section = new Section();
                $this->sectionInfo($request, $section);
                $section->user_id = Auth::user()->id;
                $section->save();

//                Section::insert([
//                    'title' => $request->get('title'),
//                    'details' => $request->get('details'),
//                    'country' => $request->get('country'),
//                    'city' => $request->get('city'),
//                    'startDate' => $request->get('startDate'),
//                    'endDate' => $request->get('endDate'),
//                    'isActive' => $request->get('isActive'),
//                    'isShown' => $request->get('isShown'),
//                    'user_id' => auth::user()->id,
//                ]);

                $education = new Education();
                $education->collageName = $request->collageName;
                $education->degree = $request->degree;
                $education->department = $request->department;
                $education->gpa = $request->gpa;
                $education->section_id = $section->id;
                $education->save();
                //$section->educations()->save($education);
            });

            return redirect('/admin/education')->with('success', 'A new education section had been added Successfully.');
        } catch (\Exception $exception){
            // Back to form with errors
            dd($exception->getMessage());
            return redirect('/admin/education/create')
                ->withErrors($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function show(Education $education)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $education = Education::with('section')->findOrFail($id);
        return view('admin.education.view',compact('education'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SectionAddUpdateRequest $request
     * @param int $id
     * @return Application|RedirectResponse|Redirector
     */
    public function update(SectionAddUpdateRequest $request, int $id)
    {
        try {
            DB::transaction(function () use ($request, $id){
                $education = Education::findOrFail($id);
                $section = Section::findOrFail($education->section_id);
                $this->sectionInfo($request, $section);
                $section->save();

                $education->collageName = $request->collageName;
                $education->degree = $request->degree;
                $education->department = $request->department;
                $education->gpa = $request->gpa;
                $education->save();

            });

            return redirect('/admin/education')->with('success', 'education section had been edited Successfully.');
        } catch (\Exception $exception){
            // Back to form with errors
            dd($exception->getMessage());
            return redirect('/admin/education/edit')
                ->withErrors($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(int $id)
    {
        try {
            $education =Education::with('section')->findOrFail($id);
            $education->section()->delete();
            $education->delete();
//            Education::destroy($id);
        } catch (\Exception $exception){
            echo $exception->getMessage();
        }

        return redirect('/admin/education')->with('success', 'A section has been deleted Successfully.');
    }

    /**
     * @param Request $request
     * @param $section
     * @return void
     */
    public function sectionInfo(Request $request, $section): void
    {
        $section->title = $request->title;
        $section->details = $request->details;
        $section->country = $request->country;
        $section->city = $request->city;
        $section->startDate = $request->startDate;
        $section->endDate = $request->endDate;
        $section->isActive = isset($request->isActive) ? 1 : 0;
        $section->isShown = isset($request->isShown) ? 1 : 0;
    }
}
