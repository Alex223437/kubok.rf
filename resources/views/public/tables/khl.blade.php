@push('styles')
<link href="/assets/rfs.css" rel="stylesheet">
<style>
#khl-section {
    background: #fff;
    margin: 0 calc(-40 * 100vw / 1920);
    padding: 0 calc(40 * 100vw / 1920) calc(40 * 100vw / 1920);
}
#khl-section .rfs-header {
    margin: 0 calc(-40 * 100vw / 1920);
    padding-left: calc(80 * 100vw / 1920);
    padding-right: calc(80 * 100vw / 1920);
}
.khl-tab-buttons {
    display: flex;
    gap: 12px;
}
.khl-tab-buttons .button {
    margin-right: 0 !important;
    color: #000;
    font-size: 24px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
    border-radius: 50px;
    border: 1px solid #E80024;
    display: flex;
    height: 45px;
    padding: 6px 15px 4px 15px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    background: transparent;
    width: fit-content;
    white-space: nowrap;
}
.khl-tab-buttons .button.is-active {
    color: #fff;
    background: #E80024;
    border-color: #E80024;
}
.khl-grid-2col {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: calc(20 * 100vw / 1920);
    padding-top: calc(20 * 100vw / 1920);
}
.khl-grid-2col .khl-table.table__wrapper {
    margin: 0;
    padding: 0;
}
/* ── Таблица standings ── */
.khl-standings-table {
    width: 100%;
    table-layout: fixed;
}
.khl-col-rank {
    width: calc(40 * 100vw / 1920);
    text-align: center !important;
    white-space: nowrap;
}
.khl-col-team {
    width: 28%;
    text-align: left !important;
    text-transform: none !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.khl-col-stat {
    text-align: center !important;
    white-space: nowrap;
}
.khl-col-goals {
    width: calc(90 * 100vw / 1920);
    text-align: center !important;
    white-space: nowrap;
}
.khl-team-cell {
    display: flex;
    align-items: center;
    gap: calc(8 * 100vw / 1920);
}
.khl-team-cell__logo {
    flex-shrink: 0;
    width: calc(36 * 100vw / 1920);
    height: calc(36 * 100vw / 1920);
    object-fit: contain;
}
.khl-team-cell__name {
    overflow: hidden;
    text-overflow: ellipsis;
}
@media (max-width: 1024px) {
    #khl-section {
        margin: 0 calc(-10 * 100vw / 1920);
        padding: 0 calc(10 * 100vw / 1920) 24px;
    }
    #khl-section .rfs-header {
        margin: 0 calc(-10 * 100vw / 1920);
        padding-left: calc(10 * 100vw / 1920);
        padding-right: calc(10 * 100vw / 1920);
    }
    /* Кнопки: первые две в ряд, третья по центру на новой строке */
    .khl-tab-buttons {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .khl-tab-buttons .button {
        font-size: 16px;
        height: 40px;
        padding: 4px 10px;
        white-space: nowrap;
        width: 100%;
       
    }
    .khl-tab-buttons .button:last-child {
        grid-column: 1 / -1;
        justify-self: center;
        min-width: 50%;
        width: fit-content;
    }
    /* Отступ между блоком кнопок и таблицей, между таблицами */
    #khl-tab-champ,
    #khl-tab-conf,
    #khl-tab-div {
        padding-top: 18px;
    }
    .khl-grid-2col {
        grid-template-columns: 1fr;
        gap: 18px;
        padding-top: 0;
    }
    /* Паддинг контейнера таблицы: top right bottom left */
    .khl-table .table__container {
        padding: 15px 15px 25px 15px;
        border-radius: 27px !important;
    }
    /* Минимальная ширина чёрного хедера */
    .khl-table__header {
        min-height: 50px;
        display: flex;
        align-items: center;
    }
    /* Колонки таблицы: auto-layout, цифры сжимаются до контента, клуб растягивается */
    .khl-standings-table {
        table-layout: auto;
    }
    .khl-col-rank {
        width: 1px;
        min-width: 20px;
        white-space: nowrap;
    }
    .khl-col-stat,
    .khl-col-goals {
        width: 1px;
        min-width: 28px;
        white-space: nowrap;
    }
    .khl-col-team {
        width: auto;
        max-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* Скрываем: ПБ(7), ПО(8), ПП(9), П(10), Ш(11) */
    .khl-standings-table tr td:nth-child(7),
    .khl-standings-table tr th:nth-child(7),
    .khl-standings-table tr td:nth-child(8),
    .khl-standings-table tr th:nth-child(8),
    .khl-standings-table tr td:nth-child(9),
    .khl-standings-table tr th:nth-child(9),
    .khl-standings-table tr td:nth-child(10),
    .khl-standings-table tr th:nth-child(10),
    .khl-standings-table tr td:nth-child(11),
    .khl-standings-table tr th:nth-child(11) {
        display: none;
    }
    /* Размер шрифта в таблице */
    .khl-standings-table .table__cell {
        font-size: 18px;
    }
    .khl-table__header {
        font-size: 16px;
    }
    /* Команда */
    .khl-team-cell__logo {
        width: 24px;
        height: 24px;
    }
    .khl-team-cell {
        gap: 6px;
    }
}
</style>
@endpush

@php
    $standings  = \App\Models\KhlStanding::orderBy('points', 'desc')
        ->orderBy('ot_wins', 'desc')
        ->orderBy('so_wins', 'desc')
        ->orderBy('goals', 'desc')
        ->get();
    $hasDivData = $standings->whereNotNull('conference')->isNotEmpty();

    $western       = $standings->where('conference', 'Западная')->values();
    $eastern       = $standings->where('conference', 'Восточная')->values();
    $divBobrova    = $standings->where('division', 'Боброва')->values();
    $divKharlamov  = $standings->where('division', 'Харламова')->values();
    $divTarasov    = $standings->where('division', 'Тарасова')->values();
    $divChernyshov = $standings->where('division', 'Чернышёва')->values();
@endphp

{{-- ═══ Единая секция: ТУРНИРНАЯ ТАБЛИЦА ══════════════════ --}}
<div id="khl-section">

    {{-- Постоянный заголовок с кнопками --}}
    <div class="rfs-header">
        <div class="rfs__title">ТУРНИРНАЯ ТАБЛИЦА</div>
        <div class="khl-tab-buttons">
            <button class="button" type="button" id="khl-btn-conf">ПО КОНФЕРЕНЦИЯМ</button>
            <button class="button" type="button" id="khl-btn-div">ПО ДИВИЗИОНАМ</button>
            <button class="button is-active" type="button" id="khl-btn-champ">ЧЕМПИОНАТ</button>
        </div>
    </div>

    {{-- Вкладка: ЧЕМПИОНАТ --}}
    <div id="khl-tab-champ" style="padding-top: calc(20 * 100vw / 1920);">
        @include('partials.khl-standings-table', ['rows' => $standings, 'title' => 'КХЛ'])
    </div>

    {{-- Вкладка: ПО КОНФЕРЕНЦИЯМ --}}
    <div id="khl-tab-conf" style="display:none;">
        @if($hasDivData)
            <div class="khl-grid-2col">
                @include('partials.khl-standings-table', ['rows' => $western,  'title' => 'Западная конференция'])
                @include('partials.khl-standings-table', ['rows' => $eastern,  'title' => 'Восточная конференция'])
            </div>
        @else
            <div style="padding: 20px;">Данные будут доступны после следующего запуска парсера.</div>
        @endif
    </div>

    {{-- Вкладка: ПО ДИВИЗИОНАМ --}}
    <div id="khl-tab-div" style="display:none;">
        @if($hasDivData)
            <div class="khl-grid-2col">
                @include('partials.khl-standings-table', ['rows' => $divBobrova,    'title' => 'Дивизион Боброва'])
                @include('partials.khl-standings-table', ['rows' => $divKharlamov,  'title' => 'Дивизион Харламова'])
                @include('partials.khl-standings-table', ['rows' => $divTarasov,    'title' => 'Дивизион Тарасова'])
                @include('partials.khl-standings-table', ['rows' => $divChernyshov, 'title' => 'Дивизион Чернышёва'])
            </div>
        @else
            <div style="padding: 20px;">Данные будут доступны после следующего запуска парсера.</div>
        @endif
    </div>

</div>

@include('components.upcoming-matches', ['sport' => 'khl', 'eventsUrl' => $page->getPayloadValue('events_url')])

@include('partials.tab-switcher')
<script>
initTabSwitcher(
    { champ: 'khl-tab-champ', conf: 'khl-tab-conf', div: 'khl-tab-div' },
    { champ: ['khl-btn-champ'], conf: ['khl-btn-conf'], div: ['khl-btn-div'] }
);
</script>
