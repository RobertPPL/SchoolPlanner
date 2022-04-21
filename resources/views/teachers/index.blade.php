@extends('layouts.app')

@section('content')

<div class="container">
    <form action="{{ route('teacher.store') }}" method="POST" class="form">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="name" id="teacher_name" placeholder="Wprowadź identyfikator nauczyciela" maxlength="101" class="form-control @error('name') is-invalid @enderror">
            <input type="submit" value="Dodaj" class="btn btn-secondary">
        </div>
    </form>
    @if(\Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif

    @if (\Session::has('errors'))
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</div>

<div class="container">
    <table class="table table-hover">
        <thead class="table-primary">
            <th>Nauczyciel</th>
        </thead>
        <tbody>
            @foreach ($teachers as $teacher)
                <tr>
                    <td>
                    <div class="dropdown" style="float: right">
                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="dropdown-menu">
                                <button name="remove_teacher" type="button" class="dropdown-item" data-url="{{ route('teacher.destroy', $teacher->id) }}"
                                    data-bs-toggle="modal" data-bs-target="#confirm-delete">Usuń nauczyciela</button>

                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add-subject"
                                    data-value="{{ $teacher->id }}" data-url="{{ route('teacher.appendsubject') }}">Dodaj przedmiot</button>

                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#remove-subject"
                                    data-value="{{ $teacher->id }}" data-url="{{ route('teacher.appendsubject') }}">Usuń przedmiot(y)</button>
                            </div>
                        </div> 

                        <div class="mb-3" name="teacher_name" contenteditable="true" data-url="{{ route('teacher.update', $teacher->id) }}" style="max-width: 50%">
                            {{$teacher->name}}
                        </div>

                        @foreach ($teacher->subjects as $subject)
                            <span class="mb-1 p-1" style="border: 1px solid black; border-radius: 5px; display: inline-block">{{ $subject->name }}</span>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $teachers->links('paginator.paginator') }}
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            Potwierdzenie.
            </div>
            <div class="modal-body">Czy na pewno usunąć ten element?</div>
                <form method="post" action="" class="form-delete">
                    @csrf
                    @method('delete')
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-danger" value="Usuń">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add-subject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" action="" class="form-attach">
        @csrf
        @method('patch')
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">Proszę wybrać przedmiot.</div>
                <div class="modal-body">
                    <input type="hidden" name="teacher_id" class="form-control">
                    <select name="subject_id" class="form-select">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
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

<div class="modal fade test" id="remove-subject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="post" action="" class="form-attach">
        @csrf
        @method('patch')
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">Proszę wybrać przedmiot(y).</div>
                <div class="modal-body" style="overflow-y: auto">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-info" role="status"></div>
                    </div>
                    <div id="subject_items" class="form-check form-switch">
                        <input type="text" name="teacher_id" class="form-control" hidden>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-danger" value="Usuń">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(() => {
    $(document).on('shown.bs.modal','.modal', function (e) {
        $('#confirm-delete').find('.form-delete').attr('action', $(e.relatedTarget).data('url'))
        $('#add-subject').find('.form-attach').attr('action', $(e.relatedTarget).data('url'))
        $('#add-subject').find('[name="teacher_id"]').val($(e.relatedTarget).data('value'))
        $('#remove-subject').find('[name="teacher_id"]').val($(e.relatedTarget).data('value'))
    })

    $(document).on('show.bs.modal','#remove-subject', function (e) {
        let value = $(e.relatedTarget).data('value')
        $.ajax({
            method: 'get',
            headers: {
                'Accept': 'application/json',
                '_token': '{{ csrf_token() }}',
                'teacher_id': value
            },
            url: '{{ route("subject.index") }}',
            data: {
                'teacher_id': value
            },
            beforeSend: function(){
                $remove_subject = $('#remove-subject')
                $remove_subject.find('.spinner-border').show()
                $remove_subject.find('#subject_items').hide()
            },
            complete: function() {
                $('#remove-subject').find('.spinner-border').hide()
                $('#remove-subject').find('#subject_items').show()
            },
            success: (e) => {
                $('#subject_items').empty()
                e.forEach(x => $('#subject_items').append(`<label><input class="form-check-input" type="checkbox" name="subjects[]" value="${x.id}">${x.name}</label><br>`))
            }
        })
    })

    $('[name="teacher_name"').blur((e) => {
        let target = e.target
        e.target.innerText = e.target.innerText.trim()
        $.ajax({
            method: 'patch',
            url: $(e.target).data('url'),
            data: {
                'name': e.target.innerText.trim(),
                '_token': '{{ csrf_token() }}'
            }
        })
    })
})

function deleteButton(target)
{
    let result = false;

    if(target.form.elements['subjects[]'].length === undefined) {
        result = target.form.elements['subjects[]'].checked
    } else {
        
        for(var i = 0; i < target.form.elements['subjects[]'].length; i++)
        {
            result = (target.form.elements['subjects[]'][i].checked ? true : false)
            if(result) {
                break;
            }
        }
    }

    target.form.elements['delete'].hidden = !result;
}

</script>
@endsection