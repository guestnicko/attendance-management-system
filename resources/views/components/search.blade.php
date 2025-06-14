@props(['page' => '', 'route' => ''])
<div class="w-full">
    {{-- Search Form --}}
    <div class="flex items-center justify-start py-3 w-full">
        <form class="max-w-md w-full" id="searchForm" method="GET">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <div class="flex items-center">
                    <input type="search" id="searchInput" onkeyup="search(this, '{{ $route }}')"
                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Student name, Student ID, ..." />

                    {{-- NOTE: Remove button if Live Search is implemented --}}
                    <button type="submit"
                        class="inline-flex items-center py-4 px-3 ms-2 text-sm font-semibold text-gray-950 bg-yellow-400 rounded-lg hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-blue-300">
                        <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('searchForm').addEventListener('submit', (e) => {
        e.preventDefault();
        console.log("form submitted")
    })
    document.getElementById("searchInput").addEventListener('keypress', (e) => {
        console.log(e.value)
    });

    async function search(data, uri) {
        uri = uri + "?search=" + data.value;
        const response = await axios.get(uri, {
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                    .content,
            },
        });
        // UPDATE TABLE
        const students = response.data.students;
        console.log(students.links)
        renderTable(students.data);
        renderPagination(students);
    }
</script>
