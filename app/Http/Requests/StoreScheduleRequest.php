<?php

namespace App\Http\Requests;

use App\Rules\GroupIsFree;
use App\Rules\RoomIsFree;
use App\Rules\TeacherNotBusy;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_id' => [
                'required',
                new GroupIsFree($this->input('date'), $this->input('start_time'), $this->input('end_time'))
            ],
            'date' => 'required|date_format:"Y-m-d"',
            'start_time' => 'required|date_format:"H:i"',
            'end_time' => 'required|date_format:"H:i"|after:start_time',
            'teacher_id' => [
                'required',
                new TeacherNotBusy($this->input('date'), $this->input('start_time'), $this->input('end_time')),
                
            ],
            'room_id' => [
                new RoomIsFree($this->input('date'), $this->input('start_time'), $this->input('end_time')),
            ],
            'subject_id' => 'required'
        ];
    }
}
