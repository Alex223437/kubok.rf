<section class="table">
    <div class="table__header">
        <div class="article__title color-red ml-title">ТУРНИРНАЯ ТАБЛИЦА (АВТОМАТИЧЕСКАЯ KHL)</div>
    </div>

    <div class="table__wrapper is-active" style="display:block;">
        <div class="table__item is-active">
            <div class="table__container">
                @php
                    $standings = \App\Models\KhlStanding::all();
                @endphp

                @if($standings->isNotEmpty())
                    <table>
                        <tbody class="table__body">
                            <tr class="table__row">
                                <td class="table__cell">Место</td>
                                <td class="table__cell">Клуб</td>
                                <td class="table__cell">И</td>
                                <td class="table__cell">В</td>
                                <td class="table__cell">ВО</td>
                                <td class="table__cell">ВБ</td>
                                <td class="table__cell">ПБ</td>
                                <td class="table__cell">ПО</td>
                                <td class="table__cell">П</td>
                                <td class="table__cell">Ш</td>
                                <td class="table__cell">О</td>
                            </tr>
                            @foreach($standings as $row)
                                <tr class="table__row">
                                    <td class="table__cell fw-900">{{ $row->rank }}</td>
                                    <td class="table__cell table__cell--team fw-900">{{ $row->team }}</td>
                                    <td class="table__cell">{{ $row->games }}</td>
                                    <td class="table__cell">{{ $row->wins }}</td>
                                    <td class="table__cell">{{ $row->ot_wins }}</td>
                                    <td class="table__cell">{{ $row->so_wins }}</td>
                                    <td class="table__cell">{{ $row->so_losses }}</td>
                                    <td class="table__cell">{{ $row->ot_losses }}</td>
                                    <td class="table__cell">{{ $row->losses }}</td>
                                    <td class="table__cell">{{ $row->goals }}</td>
                                    <td class="table__cell color-red fw-900">{{ $row->points }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="padding: 20px;">Нет данных.</p>
                @endif
            </div>
        </div>
    </div>
</section>