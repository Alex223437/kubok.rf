<section class="table">
    <div class="table__header">
        <div class="article__title color-red ml-title">ТУРНИРНАЯ ТАБЛИЦА (АВТОМАТИЧЕСКАЯ БАСКЕТБОЛ)</div>
    </div>

    <div class="table__wrapper is-active" style="display:block;">
        <div class="table__item is-active">
            <div class="table__container">
                @php
                    // Use a default tag if none is passed just in case, though it should always be passed
                    $leagueTag = $tag ?? 'msl';
                    $standings = \App\Models\BasketballStanding::where('tag', $leagueTag)->get();
                @endphp

                @if($standings->isNotEmpty())
                    <table>
                        <tbody class="table__body">
                            <tr class="table__row">
                                <td class="table__cell">Место</td>
                                <td class="table__cell">Команда</td>
                                <td class="table__cell">И</td>
                                <td class="table__cell">В</td>
                                <td class="table__cell">П</td>
                                <td class="table__cell">+/-</td>
                                <td class="table__cell">Разница</td>
                                <td class="table__cell">О</td>
                                <td class="table__cell">Последние 5 матчей</td>
                            </tr>
                            @foreach($standings as $row)
                                <tr class="table__row">
                                    <td class="table__cell fw-900">{{ $row->rank }}</td>
                                    <td class="table__cell table__cell--team fw-900">{{ $row->team }}</td>
                                    <td class="table__cell">{{ $row->games }}</td>
                                    <td class="table__cell">{{ $row->wins }}</td>
                                    <td class="table__cell">{{ $row->losses }}</td>
                                    <td class="table__cell">{{ $row->plus_minus }}</td>
                                    <td class="table__cell">{{ $row->diff }}</td>
                                    <td class="table__cell color-red fw-900">{{ $row->points }}</td>
                                    <td class="table__cell">{{ $row->last_5 }}</td>
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