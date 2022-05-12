<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->wantsJson()) {
            return Teacher::with('subjects')->where('agency', '=', Auth::user()->agency)->get();
        }
        else {
            $teachers = Teacher::with('subjects')->paginate(10);
            $subjects = Subject::all();
            return view('teachers.index', compact('teachers', 'subjects'))
                ->with('i', (request()->input('page', 1) - 1) * 10);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teachers.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTeacherRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeacherRequest $request)
    {
        $request->merge(['agency' => Auth::user()->agency]);
        Teacher::create($request->all());

        return redirect()->route('teacher.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTeacherRequest  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $teacher->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher, Request $request)
    {
        $teacher->delete();

        return redirect()->route('teacher.index');
    }

    /**
     * Append subject to teacher.
     *
     * @return \Illuminate\Http\Response
     */
    public function append_subject(Request $request)
    {
        $teacher_id = $request->get('teacher_id');
        $subject_id = $request->get('subject_id');

        if (false === Teacher::join('subject_teacher', 'teachers.id', '=', 'subject_teacher.teacher_id')
            ->where([
                ['teachers.id', '=', $teacher_id],
                ['subject_teacher.subject_id', '=', $subject_id],
            ])->exists()
        ) {
            Teacher::find($teacher_id)->subjects()->attach($subject_id);
            return redirect()->route('teacher.index')
                ->withSuccess('Udało się dodać!');
        }

        return redirect()->route('teacher.index')
            ->withErrors('Nauczyciel już ma przporządkowany ten przedmiot!');
    }

    public function remove_subject(Request $request)
    {
        $teacher_id = $request->get('teacher_id');
        $subject_id = $request->get('subjects_id');

        // dd($teacher_id, $subject_id);

        Teacher::find($teacher_id)->subjects()->detach($subject_id);

        return redirect()->route('teacher.index');
    }

    public function getTeacherAllSubjects(Request $request)
    {
        $ids = $request->get('teacher_id');
        $tt = Teacher::with('subjects')->whereIn('id', [$ids])->first();
        return $tt->subjects;
    }
}
