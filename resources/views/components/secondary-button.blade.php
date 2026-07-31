<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-[#40E0D0] rounded-md font-semibold text-xs text-[#0d3f3c] uppercase tracking-widest shadow-sm hover:bg-[#f2fffd] focus:outline-none focus:ring-2 focus:ring-[#40E0D0] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
