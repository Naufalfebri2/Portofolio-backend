<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-accent border border-transparent rounded-md font-semibold text-xs text-gray-950 uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-accent-400 focus:ring-offset-2 focus:ring-offset-[#0d1117] transition']) }}>
    {{ $slot }}
</button>
