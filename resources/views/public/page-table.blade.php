<section class="table">
  <div class="table__header">
    <div class="article__title color-red ml-title">ТУРНИРНАЯ ТАБЛИЦА</div>

    @php
      /** @var \Illuminate\Database\Eloquent\Collection $tablesByType */
        $tablesByType = $page->tablesActive->groupBy('type');
        $first = $tablesByType->keys()->first();

        $types = \App\Models\PageTable::TYPES;
    @endphp

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
  @foreach($tablesByType as $type => $tables)

    <div class="table__wrapper {{$type == $first ? 'is-active' : '' }}" data-tab="{{$type}}">
      @foreach($tables as $key => $table)
        @php
          $headers = $table->payload['headers'] ?? [];
          $n = 0;
        @endphp
        <div class="table__item {{$key == 0 ? 'is-active' : '' }}" data-item="{{$key+1}}">
          <div class="table__container">


            <table>
              <tbody class="table__body">

              <tr class="table__row">
                <td class="table__cell">№</td>
                <td class="table__cell">{{$headers[$n++]['title']??''}}</td>
                <td class="table__cell">{{$headers[$n++]['title']??''}}</td>
                <td class="table__cell">{{$headers[$n++]['title']??''}}</td>
                <td class="table__cell">{{$headers[$n++]['title']??''}}</td>
                <td class="table__cell">{{$headers[$n++]['title']??''}}</td>
              </tr>
              @foreach($table->payload['values'] as $rowIndex => $row)
                @php
                  $n = 0;
                @endphp
                <tr class="table__row">
                  <td class="table__cell fw-900">{{$rowIndex + 1}}</td>
                  <td class="table__cell table__cell--team fw-900" title="{{$row[$n]??''}}">{{$row[$n++]??''}}</td>
                  <td class="table__cell">{{$row[$n++]??''}}</td>
                  <td class="table__cell">{{$row[$n++]??''}}</td>
                  <td class="table__cell">{{$row[$n++]??''}}</td>
                  <td class="table__cell color-red fw-900">{{$row[$n++]??''}}</td>
                </tr>
              @endforeach

              </tbody>
            </table>

          </div>
          <div class="table__footer">{{$table->title}}</div>
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
