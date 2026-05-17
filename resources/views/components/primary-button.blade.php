<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-[#EB5E28] border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-[#d45220] focus:outline-none focus:ring-2 focus:ring-[#EB5E28] focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-[#EB5E28]/20']) }}>
    {{ $slot }}
</button>
