<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Room;
use App\Models\Group;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Services\DateLinkGenerator;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($date = null)
    {
        $date = is_null($date) ? (new DateTime())->format('Y-m-d') : $date;
        $links = new DateLinkGenerator($date, 'schedule.index');

        return view('schedule.index', [
            'groups' => Group::where('agency', '=', Auth::user()->agency)->get(),
            'schedule' => Schedule::with(['groups', 'teacher', 'subject', 'room'])->where('agency', '=', Auth::user()->agency)->where('date', '=', $date)->get(),
            'teachers' => Teacher::where('agency', '=', Auth::user()->agency)->get(),
            'rooms' => Room::where('agency', '=', Auth::user()->agency)->get(),
            'link_next_day' => $links->nextDay(),
            'link_previous_day' => $links->previousDay()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreScheduleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreScheduleRequest $request)
    {
            $request->merge(['agency' => Auth::user()->agency]);
            Schedule::create($request->all())->groups()->attach($request->get('group_id'));
            return redirect()->route('schedule.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateScheduleRequest  $request
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedule.index');
    }
}
