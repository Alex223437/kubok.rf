<div class="khl-table table__wrapper is-active" style="display:block;">
    <div class="table__item is-active">
        <div class="khl-table__header">{{ $title }}</div>
        <div class="table__container">
            @if($rows->isNotEmpty())
            <table class="khl-standings-table">
                <tbody class="table__body">
                    <tr class="table__row">
                        <td class="table__cell khl-col-rank">№</td>
                        <td class="table__cell khl-col-team">Клуб</td>
                        <td class="table__cell khl-col-stat">И</td>
                        <td class="table__cell khl-col-stat">В</td>
                        <td class="table__cell khl-col-stat">ВО</td>
                        <td class="table__cell khl-col-stat">ВБ</td>
                        <td class="table__cell khl-col-stat">ПБ</td>
                        <td class="table__cell khl-col-stat">ПО</td>
                        <td class="table__cell khl-col-stat">ПП</td>
                        <td class="table__cell khl-col-stat">П</td>
                        <td class="table__cell khl-col-goals">Ш</td>
                        <td class="table__cell khl-col-stat">О</td>
                    </tr>
                    @foreach($rows as $i => $row)
                    <tr class="table__row">
                        <td class="table__cell khl-col-rank fw-900">{{ $i + 1 }}</td>
                        <td class="table__cell khl-col-team">
                            <x-team-logo
                                :name="$row->team"
                                :logo="$row->logo"
                                wrapper-class="khl-team-cell"
                                img-class="khl-team-cell__logo"
                                name-class="khl-team-cell__name fw-900"
                            />
                        </td>
                        <td class="table__cell khl-col-stat">{{ $row->games }}</td>
                        <td class="table__cell khl-col-stat">{{ $row->wins }}</td>
                        <td class="table__cell khl-col-stat">{{ $row->ot_wins }}</td>
                        <td class="table__cell khl-col-stat">{{ $row->so_wins }}</td>
                        <td class="table__cell khl-col-stat">{{ $row->so_losses }}</td>
                        <td class="table__cell khl-col-stat">{{ $row->ot_losses }}</td>
                        <td class="table__cell khl-col-stat">{{ $row->pp }}</td>
                        <td class="table__cell khl-col-stat">{{ $row->losses }}</td>
                        <td class="table__cell khl-col-goals">{{ $row->goals }}</td>
                        <td class="table__cell khl-col-stat color-red fw-900">{{ $row->points }}</td>
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
