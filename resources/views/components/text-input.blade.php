@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-[#0a0e14] border border-gray-700 !text-gray-200 placeholder-gray-500 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500']) }}>
