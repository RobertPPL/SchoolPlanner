<?php

namespace App\Rules;

use DateTime;
use App\Models\Schedule;
use Illuminate\Contracts\Validation\Rule;

class GroupIsFree implements Rule
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
            ->where('group_id', '=', $value)
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
        return 'Grupa nie może wykonać tych zajęć ponieważ już jest zajęty w tym czasie.';
    }
}
