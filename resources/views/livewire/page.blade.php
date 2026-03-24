@php
  /** @var \App\Models\Page $page */

$tabs = [
  'main' => 'Основное',
  'info' => 'О кубке',
  'charity' => 'Благотворительность',
  'events' => 'Матчи',
  'moments' => 'Моменты',
  'tables' => 'Турнирные таблицы',
  'seo' => 'SEO',
];

$pageUrl = route('page', $page, false);

@endphp
<x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    <span>Страницы кубков -> {!! $page->title !!}</span>
    <a class="font-normal text-gray-500 text-sm font-mono underline" href="{{$pageUrl}}">{{$pageUrl}}</a>
  </h2>
</x-slot>

<div class="py-12">

  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      <div x-data="{ activeTab: $persist('main').as('currentTab') }">
        <!-- Навигация по вкладкам -->
        <div class="border-b border-gray-200">
          <nav class="px-4 flex flex-wrap space-x-4" aria-label="Tabs">
            @foreach($tabs as $code => $title)
              <button @click="activeTab = '{{$code}}'"
                      :class="{ 'border-indigo-600': activeTab === '{{$code}}', 'hover:text-gray-900 hover:border-gray-500 text-gray-500': activeTab !== '{{$code}}' }"
                      class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm">
                {{$title}}
              </button>
            @endforeach
          </nav>
        </div>
        <!-- Содержимое вкладок -->
        <div class="p-4">
          <!-- Вкладка Основное -->
          <div x-show="activeTab === 'main'"
               x-transition:enter="transition duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100">
            @include('livewire.page-main')
          </div>

          <!-- Вкладка О кубке -->
          <div x-show="activeTab === 'info'"
               x-transition:enter="transition duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100">
            <livewire:page-infos-manager :page="$page" wire:key="info-manager-{{ $page->id }}"/>
          </div>

          <!-- Вкладка Благотворительность -->
          <div x-show="activeTab === 'charity'"
               x-transition:enter="transition duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100">
            <livewire:page-charities-manager :page="$page" wire:key="charity-manager-{{ $page->id }}"/>
          </div>

          <!-- Вкладка Матчи -->
          <div x-show="activeTab === 'events'"
               x-transition:enter="transition duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100">

            <livewire:page-events-manager :page="$page" wire:key="events-manager-{{ $page->id }}"/>

            <form wire:submit="savePayload">
              <div class="p-4">
                <label><p>Ссылка "Показать еще"</p>
                  <input type="text" wire:model="payload.events_url" class="rounded w-full mt-1 border-gray-300">
                </label>
              </div>

              <div class="pt-4 flex justify-end">
                <div class="pr-4">
                  @session('message')
                  <span class="text-green-600"> {{ $value }} </span>
                  @endsession
                </div>
                <x-btn-primary type="submit">Сохранить</x-btn-primary>
              </div>
            </form>

          </div>

          <!-- Вкладка Моменты -->
          <div x-show="activeTab === 'moments'"
               x-transition:enter="transition duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100">
            <livewire:page-moments-manager :page="$page" wire:key="moments-manager-{{ $page->id }}"/>
          </div>

          <!-- Вкладка таблицы -->
          <div x-show="activeTab === 'tables'"
               x-transition:enter="transition duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100">
            <livewire:page-tables-manager :page="$page" wire:key="tables-manager-{{ $page->id }}"/>
          </div>

          <!-- Вкладка SEO -->
          <div x-show="activeTab === 'seo'"
               x-transition:enter="transition duration-300"
               x-transition:enter-start="opacity-0"
               x-transition:enter-end="opacity-100">
            @include('livewire.page-seo')
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
