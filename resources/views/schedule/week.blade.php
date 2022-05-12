@extends('layouts.app')

@section('content')
<script>
    let data = {!! $schedules !!}
    for(d in data) {
        console.log(data[d])
    }
</script>

<table>
<tr>
    <th class="col-sm border">Poniedziałek</th>
    <th class="col-sm border">Wtorek</th>
    <th class="col-sm border">Środa</th>
    <th class="col-sm border">Czwartek</th>
    <th class="col-sm border">Piątek</th>
    <th class="col-sm border">Sobota</th>
    <th class="col-sm border">Niedziele</th>
</tr></table>
@endsection