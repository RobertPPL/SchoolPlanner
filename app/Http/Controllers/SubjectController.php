<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->header('Accept') === 'application/json'){
            $subjects = Subject::all();
            return $subjects;
        }
        else
        {
            $subjects = Subject::where('agency', '=', Auth::user()->agency)->paginate(10);
            return view('subjects.index', compact('subjects'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->all());

        return redirect()->route('subject.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubjectRequest  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update(['name' => $request->get('name')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        try{
            $subject->delete();
        }
        catch(QueryException $e)
        {
            return redirect()->back()->withErrors('error', 'Rekord zawiera powiązane dane!');
        }
    }

    public function destroy_many(Request $request)
    {
        $subject = $request->get('subject_id');
        Subject::destroy($subject);  
        
        return redirect()->route('subject.index');
    }
}
