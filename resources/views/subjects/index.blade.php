@extends('layouts.app')

@section('content')

<div class="container">
    <x-forms.insert-new-value store-route="{{ route('subject.store') }}" />

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

<form class="container form" method="POST" action="{{ route('subject.destroy_many') }}" onsubmit="validate()">
    @csrf
    <div class="form-check form-switch" style="border-bottom: 1px solid silver; padding: 10px 0 10px 50px">
        <input type="checkbox" class="form-check-input" id="select_all" onclick="toggleCheckox(this)">
        <label for="select_all">Zaznacz/odznacz wszystko</label>
    </div>

    <div style="max-height: 500px; overflow-y: auto; padding: 10px">
        @foreach ($subjects as $subject)
        <div class="form-check form-switch" style="border-bottom: 1px solid silver; padding: 10px 0 10px 50px">
            <input type="checkbox" name="subject_id[]" value="{{ $subject->id }}" class="form-check-input" id="id_{{ $subject->id }}">
            <span name="subject_name" data-url="{{ route('subject.update', $subject->id) }}" contenteditable="true">{{ $subject->name }}</span>
        </div>
        @endforeach
    </div>

    <button class="btn btn-danger" value="Usuń zaznaczone">
        <img src="{{ asset('images/bin.png') }}" style="height: 25px">
    </button>
    {{ $subjects->links('paginator.paginator') }}
</form>

<script type="text/javascript">
    $('[name="subject_name"').blur(
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
        const checkboxes = document.querySelectorAll('input[name="subject_id[]"]')
        checkboxes.forEach(checkbox => checkbox.checked = element.checked)
    }

    function validate(event) {
        if(false === confirm("Napewno usunąć wskazane elementy?")){
            event.preventDefault()
        }
        return false;
    }
</script>
@endsection