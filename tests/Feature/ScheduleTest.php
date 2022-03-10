<?php

namespace Tests\Feature;

use App\Enums\Agency;
use Tests\TestCase;
use App\Models\Room;
use App\Models\User;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Session;

class AddScheduleTest extends TestCase
{
    private $teacher;
    private $subject;
    private $group;
    private $room;

    public function setUp(): void
    {
        parent::setUp();
        Session::start();
        $this->actingAs(User::where('email', '=', 'bia_test@test.pl')->first());
        $this->teacher = Teacher::factory()->create();
        $this->subject = Subject::factory()->create();
        $this->teacher->subjects()->attach($this->subject);
        $this->group = Group::factory()->create();
        $this->room = Room::factory()->create();

    }

    public function test_can_add_schedule()
    {
        $response = $this->post(
            route('schedule.store'),
            [
                'teacher_id' => $this->teacher->id,
                'start_time' => '11:30',
                'end_time' => '12:30',
                'room_id' => $this->room->id,
                'date' => '2022-04-11',
                'group_id' => $this->group->id,
                'agency' => $this->teacher->agency,
                'subject_id' => $this->subject->id
            ]
        );
        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
        $response->assertRedirect(route('schedule.index'));
    }

    public function test_cant_add_schedule_for_same_teacher_and_same_time()
    {
        $this->post(
            route('schedule.store'),
            [
                'teacher_id' => $this->teacher->id,
                'start_time' => '11:30',
                'end_time' => '12:30',
                'room_id' => $this->room->id,
                'date' => '2022-04-11',
                'group_id' => $this->group->id,
                'agency' => $this->teacher->agency,
                'subject_id' => $this->subject->id
            ]
        );

        $response = $this->post(
            route('schedule.store'),
            [
                'teacher_id' => $this->teacher->id,
                'start_time' => '10:00',
                'end_time' => '11:50',
                'room_id' => $this->room->id,
                'date' => '2022-04-11',
                'group_id' => $this->group->id,
                'agency' => $this->teacher->agency,
                'subject_id' => $this->subject->id
            ]
        );

        $response->assertSessionHasErrors('teacher_id');
        $response->assertStatus(302);
    }

    public function test_cant_add_schedule_for_same_room_in_same_time()
    {
        $teacher = Teacher::factory()->count(2)->create();
        $subject = Subject::factory()->create();
        $teacher[0]->subjects()->attach($this->subject);
        $teacher[1]->subjects()->attach($this->subject);
        $group = Group::factory()->create();
        $room = Room::factory()->create();

        $response = $this->post(
            route('schedule.store'),
            [
                'teacher_id' => $teacher[0]->id,
                'start_time' => '12:00',
                'end_time' => '12:11',
                'room_id' => $room->id,
                'date' => '2022-04-11',
                'group_id' => $group->id,
                'agency' => Agency::BIALYSTOK,
                'subject_id' => $this->subject->id
            ]
        );

        $response2 = $this->post(
            route('schedule.store'),
            [
                'teacher_id' => $teacher[1]->id,
                'start_time' => '12:01',
                'end_time' => '12:11',
                'room_id' => $room->id,
                'date' => '2022-04-11',
                'group_id' => $group->id,
                'agency' => Agency::BIALYSTOK,
                'subject_id' => $subject->id
            ]
        );

        $response2->assertSessionHasErrors('room_id');
    }

    public function _test_cant_one_group_have_lesson_in_same_time()
    {
        $teacher = Teacher::factory()->create();
        $subject = Subject::factory()->create();
        $teacher[0]->subjects()->attach($this->subject);
        $group = Group::factory()->count(2)->create();
        $room = Room::factory()->create();

        $response = $this->post(
            route('schedule.store'),
            [
                'teacher_id' => $teacher->id,
                'start_time' => '12:00',
                'end_time' => '12:11',
                'room_id' => $room->id,
                'date' => '2022-04-11',
                'group_id' => $group[0]->id,
                'agency' => Agency::BIALYSTOK,
                'subject_id' => $this->subject->id
            ]
        );

        $response2 = $this->post(
            route('schedule.store'),
            [
                'teacher_id' => $teacher->id,
                'start_time' => '12:01',
                'end_time' => '12:11',
                'room_id' => $room->id,
                'date' => '2022-04-11',
                'group_id' => $group->id,
                'agency' => Agency::BIALYSTOK,
                'subject_id' => $subject->id
            ]
        );

        $response2->assertSessionHasErrors('room_id');
    }
}
