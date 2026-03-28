<section class="table {{ ($khlStyle ?? false) ? 'khl-table' : '' }}">
  @php
    /** @var \Illuminate\Database\Eloquent\Collection $tablesByType */
    $tablesByType = $page->tablesActive->groupBy('type');
    $first = $tablesByType->keys()->first();
    $types = \App\Models\PageTable::TYPES;
    $useKhlStyle = $khlStyle ?? false;
  @endphp

  @if(!$useKhlStyle)
  <div class="table__header">
    <div class="article__title color-red ml-title">ТУРНИРНАЯ ТАБЛИЦА</div>
    <div class="table__buttons">
      @if($tablesByType->count() > 1)
        @foreach($tablesByType as $type => $tables)
          <button class="button {{$type == $first ? 'is-active' : '' }}" type="button" data-tab="{{$type}}">
            <div class="button__text">{{$types[$type]}}</div>
          </button>
        @endforeach
      @endif
    </div>
  </div>
  @endif

  @foreach($tablesByType as $type => $tables)
    <div class="table__wrapper {{$type == $first ? 'is-active' : '' }}" data-tab="{{$type}}">
      @foreach($tables as $key => $table)
        @php
          $headers = $table->payload['headers'] ?? [];
        @endphp
        <div class="table__item {{$key == 0 ? 'is-active' : '' }}" data-item="{{$key+1}}">
          @if($useKhlStyle)
            <div class="khl-table__header">{{ $table->title }}</div>
          @endif
          <div class="table__container">
            <table>
              <tbody class="table__body">
                <tr class="table__row">
                  <td class="table__cell">№</td>
                  @foreach($headers as $i => $header)
                    <td class="table__cell {{ $i === 0 ? '' : '' }}">{{ $header['title'] ?? '' }}</td>
                  @endforeach
                </tr>
                @foreach($table->payload['values'] as $rowIndex => $row)
                  <tr class="table__row">
                    <td class="table__cell fw-900">{{ $rowIndex + 1 }}</td>
                    @foreach($row as $colIndex => $value)
                      <td class="table__cell
                        {{-- first col = team name --}}
                        {{ $colIndex === 0 ? 'table__cell--team fw-900' : '' }}
                        {{-- last col = points, red --}}
                        {{ $colIndex === count($row) - 1 ? 'color-red fw-900' : '' }}
                      ">{{ $value }}</td>
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          @if(!$useKhlStyle)
          <div class="table__footer">{{$table->title}}</div>
          @endif
        </div>
      @endforeach
      <div class="table__footer for-devices">
        @foreach($tables as $key => $table)
          <button class="table__footer-item {{$key == 0 ? 'is-active' : '' }}" type="button" data-item="{{$key+1}}">
            {{$table->short ?: $table->title}}
          </button>
        @endforeach
      </div>
    </div>
  @endforeach
</section>
