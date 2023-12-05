<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @yield('box-title')
        </h2>
    </x-slot>

    <div class="bg-[#664d38] py-12">
        <div class="bg-[#664d38] max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#664d38] dark:bg-[#664d38] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @yield('box-content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>