<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                Registered Class Sets
            </h1>

            @isset($totalSets)
                <div class="flex items-center text-gray-700">
                    <span class="mr-2 text-lg">Total Number of Sets:</span>
                    <span class="px-3 py-1 bg-gray-800 text-green-400 text-lg font-semibold rounded-2xl">
                        {{ $totalSets }}
                    </span>
                </div>
            @endisset
        </div>
    </x-slot>
    

    <div class="inline-flex rounded-md shadow-xs" role="group">
        <a href="{{ route('sets.view', ['program' => 'BSIT']) }}"
        class="px-4 py-2 bg-gray-800 hover:bg-gray-900 transition-all
                {{request('program', 'BSIT') === 'BSIT' ? 'text-green-600 font-semibold' : 'text-gray-300'}} transition rounded-s-lg">
            BSIT
        </a>
        <a href="{{ route('sets.view', ['program' => 'BSIS']) }}"
        class="px-4 py-2 bg-gray-800 hover:bg-gray-900 transition-all
                {{request('program', 'BSIT') === 'BSIS' ? 'text-green-600 font-semibold' : 'text-gray-300'}} transition rounded-e-lg">
            BSIS
        </a>
    </div>

    <div class="grid grid-cols-4 gap-4 my-2">
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
                    </div>
                    <p class="text-sm text-gray-500">
                        {{ $set->total_students }} / 40 Students
                    </p>
                </div>
            </div>

            @endforeach
        @endisset
    </div>
</x-app-layout>