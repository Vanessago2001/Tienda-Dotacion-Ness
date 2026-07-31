<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#40E0D0] border border-transparent rounded-md font-semibold text-xs text-[#0d3f3c] uppercase tracking-widest hover:bg-[#2bbdb3] focus:bg-[#2bbdb3] active:bg-[#1f8f89] focus:outline-none focus:ring-2 focus:ring-[#40E0D0] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
