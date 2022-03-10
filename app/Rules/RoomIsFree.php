<?php

namespace App\Rules;

use DateTime;
use App\Models\Schedule;
use Illuminate\Contracts\Validation\Rule;

class RoomIsFree implements Rule
{
    private DateTime $start;
    private DateTime $end;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date, $start, $end)
    {
        // $this->room = Teacher::find($room_id);
        $this->start = new DateTime(sprintf('%s %s', $date, $start));
        $this->end = new DateTime(sprintf('%s %s', $date, $end));
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $can = Schedule::whereTime('start_time', '<', $this->end)
            ->whereTime('end_time', '>', $this->start)
            ->where('room_id', '=', $value)
            // ->where('subject_id', '<>', $this->input('subject_id'))
            ->exists();

        return !$can;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sala jest w tym terminie zajęta.';
    }
}
