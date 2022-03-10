@extends('layouts.app')

@section('content')
<script src="{{ asset('js/teacher.js') }}" defer></script>

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
</div>

<div class="container">
    <table class="table table-hover">
        <thead class="table-primary">
            <th>Nauczyciel</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($teachers as $teacher)
                <tr>
                    <td>
                        <div name="teacher_name" contenteditable="true" data-url="{{ route('teacher.update', $teacher->id) }}">{{$teacher->name}}</div>
                        <form method="post" action="{{ route('teacher.dettachsubject') }}">
                            @csrf
                            @method('patch')
                            @foreach ($teacher->subjects as $subject)
                                <div class="form-switch"><label>
                                    <input type="hidden" name="teacher" value="{{ $teacher->id }}">
                                    <input
                                        type="checkbox"
                                        value="{{ $subject->id}}"
                                        name="subjects[]"
                                        class="form-check-input"
                                        onclick="deleteButton(this)"
                                    > {{ $subject->name }}</label>

                                </div>
                            @endforeach
                            <input name="delete" type="submit" value="Usuń zaznaczone" class="btn btn-danger" hidden>
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger">
                            <img
                                style="cursor: pointer; height: 20px"
                                src="{{ asset('images/user_delete.png') }}"
                                onclick="removeTeacher('{{ route('teacher.destroy', $teacher->id) }}', this)"
                                title="Usuń nauczyciela"
                            >
                        </button>
                        <button type="button" class="btn btn-success">
                            <img
                                style="cursor: pointer; height: 20px"
                                src="{{ asset('images/plus.png') }}"
                                onclick="addSubject('{{ $teacher->id }}', this)"
                                title="Dodaj przedmiot"
                            >
                        </button>
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
            Na pewno usunąć ten element?
            </div>
            <div class="modal-body">
                <form method="post" action="" class="form-edit">
                    @csrf
                    @method('delete')
                    <div class="form-group">
                        <input type="text" placeholder="Nazwa grupy" id="name" name="name" class="form-control">
                    </div>   
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Edytuj">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<div id="wnd" style="position: fixed; background-color: #fff; left: 0; top: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); visibility: hidden; z-index: 100">
    <form method="post" action="{{ route('teacher.appendsubject') }}" id="wnd_form" style="background-color: #fff; position: fixed; left: 50%; top: 50%; transform: translate(-50%, -50%); border: 1px solid black; border-radius: 5px; width: 600px; height: 200px; margin: auto; padding: 20px">
        @csrf
        @method('patch')
        <input type="text" placeholder="teacher" id="teacher" name="teacher" class="form-control sm-3" style="opacity: 1">
        <select id="subject" name="subject" class="form-control sm-3" style="opacity: 1"></select>
        <input type="submit" style="opacity: 1" class="btn btn-primary">
        <input type="button" class="btn btn-danger" onclick="wnd.style.visibility = 'hidden'" value="Anuluj">

    </form>
</div>

<script>

    $(document).ready(
        $('[name="teacher_name"').blur(
            function(e) {
                e.target.innerText = e.target.innerText.trim()
                $.ajax({
                    method: 'patch',
                    url: $(e.target).data('url'),
                    data: {
                        'name': e.target.innerText.trim(),
                        '_token': '{{ csrf_token() }}'
                    }
                })
            }
        )
    );

</script>
@endsection