<button {{ $attributes->merge(['class' => 'px-4 py-2 font-semibold text-gray-800 border border-gray-300 rounded hover:bg-gray-100']) }}>
  {{ $slot }}
</button>
