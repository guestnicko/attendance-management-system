@props(['count' => 0, 'lastpage' => 0, 'page' => ''])
<div class="w-full flex justify-center my-5" id="pagination">
    <nav aria-label="Page navigation example">
        <ul class="inline-flex -space-x-px text-base h-10">
            <li>
                <button onclick="prevPage('{{ url()->current() }}')"
                    class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                    Previous
                </button>
            </li>
            @for ($i = 1; $i <= $count && $i <= 10; $i++)
                <li>
                    <a href="?page={{ $i }}"
                        class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $i }}</a>
                </li>

                {{-- Fetch the last page of the available page --}}
                @if ($i == 10)
                    <li>
                        <a
                            class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</a>
                    </li>
                    <li>
                        <a href="?page={{ $lastpage }}"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $lastpage }}</a>
                    </li>
                @endif
            @endfor


            <button onclick="nextPage('{{ url()->current() }}')"
                class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                Next</button>
            {{ $page }}
            </li>
        </ul>
    </nav>
</div>

<script>
    const queryParams = new URLSearchParams(window.location.search);
    const Baseurl = window.location;

    const nextPage = (Baseurl) => {
        var url = "";
        let pageQuery = queryParams.get("page");

        queryParams.forEach((value, key) => {
            if (key != "page") {
                url += `${key}=${value}&&`;
            }
            console.log(key);
        });
        pageQuery++;
        url += "page=" + pageQuery;
        window.location.href = Baseurl + "?" + url
    };

    const prevPage = (Baseurl) => {
        var url = "";
        const pageQuery = queryParams.get("page");
        queryParams.forEach((value, key) => {
            if (key != "page") {
                url += `${key}=${value}&&`;
            }
        });
        if (pageQuery > 1) {
            url += "page=" + (pageQuery - 1);
        } else {
            url += "page=" + 1;
        }
        window.location.href = Baseurl + "?" + url
    };
    window.renderPagination = renderPagination

    function renderPagination(data) {
        if (data == null) {
            return;
        }
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = "";

        pagination.innerHTML += `
                    <nav aria-label="Page navigation example">
                        <ul class="inline-flex -space-x-px text-base h-10">
                            <li class="">
                                <button onclick="navigate('${data.prev_page_url}')"
                                    class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    Previous
                                </button>
                            </li>
                    `;
        for (let index = 1; index <= data.last_page && index <= 10; index++) {
            pagination.innerHTML += `
                        <li class="list-none">
                            <button onclick="navigate('${data.links[index].url}')"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                ${index}
                                </button>
                        </li>
                `;

            if (index == 10) {
                pagination.innerHTML += `
                            <li class="list-none">
                            <button class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                ...
                                </button>
                                </li>
                                    <li class="list-none">
                                        <button onclick="navigate('${data.last_page_url}')"
                                    class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                        ${data.last_page}
                                        </button>
                                        </li>
                            `;
            }
        }
        pagination.innerHTML += `
                        <button onclick="navigate('${data.next_page_url}')"
                            class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            Next</button>
                        </li>
                    </ul>
                </nav>`;
    }
    async function navigate(uri) {
        console.log(uri)
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
