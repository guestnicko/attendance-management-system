<x-app-layout>
    <x-slot name="header">
        <h1 class="text-4xl font-semibold">
            Registered Class Sets
        </h1>
    </x-slot>
    

    <div class="inline-flex rounded-md shadow-xs" role="group">
        <button type="button" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-gray-300 transition rounded-s-lg">
            BSIT
        </button>

        <button type="button" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-gray-300 transition rounded-e-lg">
            BSIS
        </button>
    </div>

    <div class="flex flex-col my-2">
       @isset($sets)
            @foreach ($sets as $set)
            <div class="flex w-full max-w-xl bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                <!-- Left Side: Program -->
                <div class="bg-green-500 text-white flex items-center justify-center px-6 py-6 w-32">
                    <span class="text-lg font-bold tracking-wide">
                        {{ $set->s_program }}
                    </span>
                </div>

                <!-- Right Side: Details -->
                <div class="flex flex-col justify-center flex-1 p-4">
                    <div class="flex justify-between items-center mb-1">
                        <h2 class="text-gray-800 font-semibold text-lg">
                            Set: <span class="font-normal">{{ $set->s_set }}</span>
                        </h2>
                        <span class="text-sm bg-gray-100 text-gray-600 px-3 py-1 rounded-full border">
                            {{ $set->total_students }} Students
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">
                        Academic Year: 2025 • Active
                    </p>
                </div>
            </div>

            @endforeach
        @endisset
    </div>
</x-app-layout>