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
    font-size: 20px;
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
    .khl-grid-2col {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .khl-col-rank {
        width: 28px;
    }
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
    $standings  = \App\Models\KhlStanding::orderBy('rank')->get();
    $hasDivData = $standings->whereNotNull('conference')->isNotEmpty();

    $western       = $standings->where('conference', 'Западная')->values();
    $eastern       = $standings->where('conference', 'Восточная')->values();
    $divBobrova    = $standings->where('division', 'Боброва')->values();
    $divKharlamov  = $standings->where('division', 'Харламова')->values();
    $divTarasov    = $standings->where('division', 'Тарасова')->values();
    $divChernyshov = $standings->where('division', 'Чернышёва')->values();

    function khl_table($rows, string $title): void {
    ?>
    <div class="khl-table table__wrapper is-active" style="display:block;">
        <div class="table__item is-active">
            <div class="khl-table__header"><?= e($title) ?></div>
            <div class="table__container">
                <?php if ($rows->isNotEmpty()): ?>
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
                        <?php foreach ($rows as $i => $row): ?>
                        <tr class="table__row">
                            <td class="table__cell khl-col-rank fw-900"><?= $i + 1 ?></td>
                            <td class="table__cell khl-col-team">
                                <div class="khl-team-cell">
                                    <?php if ($row->logo): ?>
                                        <img class="khl-team-cell__logo" src="<?= e($row->logo) ?>" alt="<?= e($row->team) ?>" loading="lazy">
                                    <?php endif; ?>
                                    <span class="khl-team-cell__name fw-900"><?= e($row->team) ?></span>
                                </div>
                            </td>
                            <td class="table__cell khl-col-stat"><?= e($row->games) ?></td>
                            <td class="table__cell khl-col-stat"><?= e($row->wins) ?></td>
                            <td class="table__cell khl-col-stat"><?= e($row->ot_wins) ?></td>
                            <td class="table__cell khl-col-stat"><?= e($row->so_wins) ?></td>
                            <td class="table__cell khl-col-stat"><?= e($row->so_losses) ?></td>
                            <td class="table__cell khl-col-stat"><?= e($row->ot_losses) ?></td>
                            <td class="table__cell khl-col-stat"><?= e($row->pp) ?></td>
                            <td class="table__cell khl-col-stat"><?= e($row->losses) ?></td>
                            <td class="table__cell khl-col-goals"><?= e($row->goals) ?></td>
                            <td class="table__cell khl-col-stat color-red fw-900"><?= e($row->points) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p style="padding: 20px;">Нет данных.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    }
@endphp

{{-- ═══ Единая секция: ТУРНИРНАЯ ТАБЛИЦА ══════════════════ --}}
<div id="khl-section">

    {{-- Постоянный заголовок с кнопками --}}
    <div class="rfs-header">
        <div class="rfs__title">ТУРНИРНАЯ ТАБЛИЦА</div>
        <div class="table__buttons rfs-header__buttons khl-tab-buttons">
            <button class="button" type="button" id="khl-btn-conf">ПО КОНФЕРЕНЦИЯМ</button>
            <button class="button" type="button" id="khl-btn-div">ПО ДИВИЗИОНАМ</button>
            <button class="button is-active" type="button" id="khl-btn-champ">ЧЕМПИОНАТ</button>
        </div>
    </div>

    {{-- Вкладка: ЧЕМПИОНАТ --}}
    <div id="khl-tab-champ" style="padding-top: calc(20 * 100vw / 1920);">
        @php khl_table($standings, 'КХЛ') @endphp
    </div>

    {{-- Вкладка: ПО КОНФЕРЕНЦИЯМ --}}
    <div id="khl-tab-conf" style="display:none;">
        @if($hasDivData)
            <div class="khl-grid-2col">
                @php khl_table($western, 'Западная конференция') @endphp
                @php khl_table($eastern, 'Восточная конференция') @endphp
            </div>
        @else
            <div style="padding: 20px;">Данные будут доступны после следующего запуска парсера.</div>
        @endif
    </div>

    {{-- Вкладка: ПО ДИВИЗИОНАМ --}}
    <div id="khl-tab-div" style="display:none;">
        @if($hasDivData)
            <div class="khl-grid-2col">
                @php khl_table($divBobrova,    'Дивизион Боброва') @endphp
                @php khl_table($divKharlamov,  'Дивизион Харламова') @endphp
                @php khl_table($divTarasov,    'Дивизион Тарасова') @endphp
                @php khl_table($divChernyshov, 'Дивизион Чернышёва') @endphp
            </div>
        @else
            <div style="padding: 20px;">Данные будут доступны после следующего запуска парсера.</div>
        @endif
    </div>

</div>

@include('components.upcoming-matches', ['sport' => 'khl'])

<script>
(function () {
    var tabs = {
        champ: document.getElementById('khl-tab-champ'),
        conf:  document.getElementById('khl-tab-conf'),
        div:   document.getElementById('khl-tab-div'),
    };
    var btns = {
        champ: ['khl-btn-champ'],
        conf:  ['khl-btn-conf'],
        div:   ['khl-btn-div'],
    };

    function showTab(active) {
        Object.keys(tabs).forEach(function (key) {
            tabs[key].style.display = key === active ? 'block' : 'none';
        });
        Object.keys(btns).forEach(function (key) {
            btns[key].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.classList.toggle('is-active', key === active);
            });
        });
    }

    Object.keys(btns).forEach(function (key) {
        btns[key].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('click', function () { showTab(key); });
        });
    });
})();
</script>
