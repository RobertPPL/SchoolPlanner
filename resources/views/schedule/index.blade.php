@extends('layouts.app')

@section('content')
<div class="container">
    @if(\Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif

    @if(\Session::has('error'))
    <div class="alert alert-danger">
    {{ Session::get('error') }}
    </div>
    @endif
 
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="form_store" action="{{ route('schedule.store') }}" method="POST" class="form border p-3 bg-light mb-3">
        @csrf
        <div class="form-group mb-3">
            <select name="group_id" class="form-select @error('group') is-invalid @enderror" aria-label="Default select example" required>
                <option value="" selected disabled>Wybierz grupę</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" @if($group->id === old('group_id')) selected @endif>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group mb-3">
            <div class="input-group">
                <span class="input-group-text">Data</span>
                <input id="date" type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') ?? date('Y-m-d') }}"required>

                <span class="input-group-text">Od</span>
                <input id="start_time" type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="form-control @error('start_time') is-invalid @enderror" required>

                <span class="input-group-text">Do</span>
                <input id="end_time" type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="form-control @error('end_time') is-invalid @enderror" required>

                <span class="input-group-text">Sala</span>
                <select id="room_id" name="room_id" class="form-select col" aria-label="Default select example" required>
                <option value="" selected disabled>Sala:</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" @if($room->id === old('room_id')) selected @endif>{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="input-group">
            <select id="teacher_id" name="teacher_id" class="form-select col" aria-label="Default select example" required>
                <option value="" selected disabled>Wybierz nauczyciela:</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @if($teacher->id === old('teacher_id')) selected @endif>{{ $teacher->name }}</option>
                @endforeach
            </select>

            <select id="subject_id" name="subject_id" class="form-select col" aria-label="Default select example" required>
                <option value="" selected disabled>Wybierz przedmiot:</option>
            </select>
        </div>
        <br>
        <div class="form-group">
            <input type="submit" value="Zapisz" class="btn btn-primary">
        </div>
    </form>

    @include('schedule.daily')

</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                ...aaa
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <form method="POST" action="" class="form-delete">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-danger" value="Usuń">
                </form>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" action="{{ route('schedule.attach_group') }}" class="form-attach">
        @csrf
        @method('patch')
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">Proszę wybrać grupę.</div>
                <div class="modal-body">
                    <input type="hidden" name="schedule_id" class="form-control">
                    <select name="group_id" class="form-select">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success" value="Dodaj">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).on('show.bs.modal','#confirm-delete', function (e) {
            $('#confirm-delete').find('.modal-header').text('Proszę potwierdzić.')
            $('#confirm-delete').find('.modal-body').text('Na pewno usunąć ten element?')
            $('#confirm-delete').find('.form-delete').attr('action', $(e.relatedTarget).data('href'))
    })

    $(document).on('show.bs.modal','#add-group', function (e) {
            $('#add-group').find('[name="schedule_id"]').val($(e.relatedTarget).data('value'))
    })

    $(document).ready(function() {
        $('[name="ggg"]').change(function(e) {
            e.target.parentNode.insertAdjacentHTML('beforebegin', `<li>${e.target.value}</li>`)
        })


        $('#teacher_id').change(function (e) {
            $.ajax({
                headers: {
                    'Accept': 'application/json'
                },
                type: 'post',
                data: {
                    teacher_id: e.target.value,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                url: '{{ route("teacher.getTeacherAllSubjects") }}',
                beforeSend: function(){
                    $('#load_screen').modal('show')
                },
                complete: function() {
                    $('#load_screen').modal('hide')
                }
            })
            .done(function(response) {
                response.forEach(x => console.log(x))
                $('#subject_id').find('option').remove();
                response.forEach(x => $('#subject_id').append(new Option(x.name, x.id)))
            })
        })
    })
</script>
@endsection
