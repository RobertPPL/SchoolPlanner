@extends('layouts.app')

@section('content')

<div class="container">
    <x-forms.insert-new-value store-route="{{ route('group.store') }}" />

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

<form class="container form" method="POST" action="{{ route('group.destroy_many') }}" onsubmit="validate(event)">
    @csrf
    <div class="form-check form-switch" style="border-bottom: 1px solid silver; padding: 10px 0 10px 50px">
        <input type="checkbox" class="form-check-input" id="select_all" onclick="toggleCheckox(this)">
        <label for="select_all">Zaznacz/odznacz wszystko</label>
    </div>

    <div style="max-height: 500px; overflow-y: auto; padding: 10px">
        @foreach ($groups as $group)
        <div class="form-check form-switch" style="border-bottom: 1px solid silver; padding: 10px 0 10px 50px">
            <input type="checkbox" name="group_id[]" value="{{ $group->id }}" class="form-check-input" id="id_{{ $group->id }}">
            <span name="group_name" data-url="{{ route('group.update', $group->id) }}" contenteditable="true">{{ $group->name }}</span>
        </div>
        @endforeach
    </div>

    <button class="btn btn-danger" value="Usuń zaznaczone">
        <img src="{{ asset('images/bin.png') }}" style="height: 25px">
    </button>
    {{ $groups->links('paginator.paginator') }}
</form>

<script type="text/javascript">
    $('[name="group_name"]').blur(
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
        const checkoboxes = document.querySelectorAll('input[name="group_id[]"]')
        checkoboxes.forEach(checkbox => checkbox.checked = element.checked)
    }

    function validate(event) {
        if(false === confirm("Napewno usunąć wskazane elementy?")){
            event.preventDefault()
        }
        return false;
    }
</script>
@endsection
