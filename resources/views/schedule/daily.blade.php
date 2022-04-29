<div class="mb-3 p-3 btn-group">
        <a class="btn btn-primary" href="{{ $link_previous_day }}">Poprzedni dzień</a>
        <a class="btn btn-primary" href="{{ $link_today }}">Dziś</a>
        <a class="btn btn-primary" href="{{ $link_previous_day }}">Następny dzień</a>
    </div>
    
    <div class="p-3">
        @foreach($schedule as $sch)
            <div class="mb-3 border rounded">

                <div class="dropdown" style="float: right">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu">
                        <button class="dropdown-item" data-href="{{ route('schedule.destroy', $sch->id) }}" data-bs-toggle="modal"
                            data-bs-target="#confirm-delete">
                                Usuń
                        </button>

                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add-group"
                            data-value="{{ $sch->id }}">
                            Dodaj grupę
                        </button>
                    </div>
                </div>

                <div>
                    <b>{{ $sch->date }} {{ $sch->start_time }} - {{ $sch->end_time }}</b><br>
                    Przedmiot: <b>{{ $sch->subject->name }}</b><br>
                    Prowadzący: <b>{{ $sch->teacher->name }}</b><br>
                    Sala: <b>{{ $sch->room->name ?? 'Brak'}}</b><br>
                    ID: <b>{{ $sch->id }}</b><br>
                    Grupy:
                    <ul>
                        @foreach($sch->groups as $group)
                            <li><b>{{ $group->name }}</b></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>