<div class="table__wrapper is-active" style="display:block;">
    <div class="table__item is-active">
        @if($title ?? '')
            <div class="khl-table__header">{{ $title }}</div>
        @endif
        <div class="table__container">
            <table>
                <tbody class="table__body">
                    <tr class="table__row">
                        <td class="table__cell bsk-cell-hdr">№</td>
                        <td class="table__cell bsk-cell-hdr bsk-cell-club">Клуб</td>
                        <td class="table__cell bsk-cell-hdr">И</td>
                        <td class="table__cell bsk-cell-hdr">В</td>
                        <td class="table__cell bsk-cell-hdr">П</td>
                        <td class="table__cell bsk-cell-hdr">%</td>
                        <td class="table__cell bsk-cell-hdr">Последние 5</td>
                        <td class="table__cell bsk-cell-hdr">Забито</td>
                        <td class="table__cell bsk-cell-hdr">Пропущено</td>
                        <td class="table__cell bsk-cell-hdr">+/-</td>
                        <td class="table__cell bsk-cell-hdr">Очки</td>
                    </tr>
                    @foreach($rows as $i => $row)
                    @php
                        $last5 = $row->last_5 ? array_map('trim', explode(',', $row->last_5)) : [];
                        [$scored, $missed] = array_pad(explode('/', $row->plus_minus ?? '/'), 2, '0');
                        $diff = (int)$scored - (int)$missed;
                    @endphp
                    <tr class="table__row">
                        <td class="table__cell bsk-cell-num">{{ $i + 1 }}</td>
                        <td class="table__cell table__cell--team bsk-cell-club">
                            <x-team-logo :name="$row->team" :logo="$row->logo" />
                        </td>
                        <td class="table__cell bsk-cell-num">{{ $row->games }}</td>
                        <td class="table__cell bsk-cell-num">{{ $row->wins }}</td>
                        <td class="table__cell bsk-cell-num">{{ $row->losses }}</td>
                        <td class="table__cell bsk-cell-num">{{ $row->win_pct }}%</td>
                        <td class="table__cell bsk-cell-last5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="12" viewBox="0 0 80 12" fill="none">
                                @php $filled = array_slice($last5, 0, 5); @endphp
                                @for($j = 0; $j < 5; $j++)
                                    @php
                                        $result = $filled[$j] ?? null;
                                        $color  = $result === 'W' ? '#5BA500' : ($result === 'L' ? '#E80024' : '#D9D9D9');
                                    @endphp
                                    <circle cx="{{ 6 + $j * 17 }}" cy="6" r="6" fill="{{ $color }}"/>
                                @endfor
                            </svg>
                        </td>
                        <td class="table__cell bsk-cell-num">{{ $scored }}</td>
                        <td class="table__cell bsk-cell-num">{{ $missed }}</td>
                        <td class="table__cell bsk-cell-num">{{ $diff > 0 ? '+' . $diff : $diff }}</td>
                        <td class="table__cell bsk-cell-pts">{{ $row->points }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
