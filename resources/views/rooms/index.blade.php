@extends('layouts.app')

@section('content')

<div class="container">
    <form action="{{ route('group.store') }}" method="POST" class="form">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="name" id="group_name" placeholder="Wprowadź identyfikator grupy" maxlength="100" class="form-control @error('name') is-invalid @enderror">
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

<form class="container form" method="POST" action="{{ route('room.destroy_many') }}">
    @csrf
    <div class="form-check form-switch" style="border-bottom: 1px solid silver; padding: 10px 0 10px 50px">
        <input type="checkbox" class="form-check-input" id="select_all" onclick="toggleCheckox(this)">
        <label for="select_all">Zaznacz/odznacz wszystko</label>
    </div>

    <div style="max-height: 500px; overflow-y: auto; padding: 10px">
        @foreach ($rooms as $room)
        <div class="form-check form-switch" style="border-bottom: 1px solid silver; padding: 10px 0 10px 50px">
            <input type="checkbox" name="room_id[]" value="{{ $room->id }}" class="form-check-input" id="id_{{ $room->id }}">
            <span name="room_name" data-url="{{ route('room.update', $room->id) }}" contenteditable="true">{{ $room->name }}</span>
        </div>
        @endforeach
    </div>

    <button class="btn btn-danger" value="Usuń zaznaczone">
        <img src="{{ asset('images/bin.png') }}" style="height: 25px">
    </button>
    {{ $rooms->links('paginator.paginator') }}
</form>

<script type="text/javascript">
    $('[name="room_name"]').blur(
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

    function toggleCheckox(element) {
        const checkoboxes = document.querySelectorAll('input[name="room_id[]"]')
        checkoboxes.forEach(checkbox => checkbox.checked = element.checked)
    }
</script>
@endsection
