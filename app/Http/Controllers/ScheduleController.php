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
use Illuminate\Http\Request;

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
            'groups' => Group::get(),
            'schedule' => Schedule::with(['groups', 'teacher', 'subject', 'room'])->where('date', '=', $date)->get(),
            'teachers' => Teacher::get(),
            'rooms' => Room::get(),
            'link_next_day' => $links->nextDay(),
            'link_previous_day' => $links->previousDay(),
            'link_today' =>  $links->todayDay()
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

    public function attachGroupToSchedule(Request $request)
    {
        $schedule = Schedule::find($request->get('schedule_id'));
        $schedule->groups()->attach($request->get('group_id'));
        
        return redirect()->route('schedule.index');
    }
}
