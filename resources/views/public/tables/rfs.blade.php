<section class="table">
    <div class="table__header">
        <div class="article__title color-red ml-title">РАСПИСАНИЕ МАТЧЕЙ (АВТОМАТИЧЕСКАЯ RFS)</div>
    </div>

    <div class="table__wrapper is-active">
        <div class="table__item is-active">
            <div class="table__container">
                @php
                    $matches = \App\Models\RfsMatch::all();
                @endphp

                @if($matches->isNotEmpty())
                    <table>
                        <tbody class="table__body">
                            <tr class="table__row">
                                <td class="table__cell">Команда 1</td>
                                <td class="table__cell">Команда 2</td>
                                <td class="table__cell">Счет / Дата</td>
                            </tr>
                            @foreach($matches as $row)
                                <tr class="table__row">
                                    <td class="table__cell table__cell--team fw-900">{{ $row->team1 }}</td>
                                    <td class="table__cell table__cell--team fw-900">{{ $row->team2 }}</td>
                                    <td class="table__cell color-red fw-900">{{ $row->score_or_date }}</td>
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