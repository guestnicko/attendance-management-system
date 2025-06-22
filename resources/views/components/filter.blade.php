  @props(['page' => '', 'route' => ''])
  <div class="flex items-center py-3 ">
      <button id="dropdownDefault" data-dropdown-toggle="dropdown"
          class="text-gray-900 font-semibold bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-4 py-4 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
          type="button">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
              class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round"
                  d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
          </svg>
      </button>

      <!-- Dropdown menu -->
      <div id="dropdown" class="z-10 hidden w-auto p-3 bg-white rounded-lg shadow dark:bg-gray-700 border-2">
          <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
              Category
          </h6>
          {{-- List for Program --}}
          <div class="flex justify-between gap-3">
              <form id="search_program" onchange="getCategory()" class="">
                  <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                      <label for="" class="font-semibold text-gray-100">Program</label>
                      @foreach (['BSIT', 'BSIS'] as $program)
                          <li class="flex items-center">
                              <input value="{{ $program }}" type="checkbox" name="program"
                                  class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                              <label for="{{ $program }}"
                                  class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                  {{ $program }}
                              </label>
                          </li>
                      @endforeach
                  </ul>
              </form>
              {{-- List for Year Levels --}}
              <form id="search_lvl" onchange="getCategory()" class="">
                  <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                      <label for="" class="font-semibold text-gray-100">Year Level</label>
                      {{-- Key-value pair for this list, key is for the database field, value is the placeholder --}}
                      @foreach (['1' => 'First Year', '2' => 'Second Year', '3' => 'Third Year', '4' => 'Fourth Year'] as $key => $value)
                          <li class="flex items-center">
                              <input type="checkbox" value="{{ $key }}" name="lvl"
                                  class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                              <label for="{{ $key }}"
                                  class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                  {{ $value }}
                              </label>
                          </li>
                      @endforeach

                  </ul>
              </form>
              {{-- List for Sets --}}
              <form id="search_set" onchange="getCategory()" class="">
                  <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                      <label for="" class="font-semibold text-gray-100">Set</label>
                      @foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $set)
                          <li class="flex items-center">
                              <input value="{{ $set }}" type="checkbox" name="set"
                                  class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                              <label for="{{ $set }}"
                                  class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                  {{ $set }}
                              </label>
                          </li>
                      @endforeach
                  </ul>
              </form>
              {{-- List for Status if Enrolled, Dropped, or Graduated --}}
              <form id="search_status" onchange="getCategory()" class="">
                  <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                      <label for="" class="font-semibold text-gray-100">Status</label>
                      @foreach (['ENROLLED', 'DROPPED', 'GRADUATED', 'TO BE UPDATED'] as $status)
                          <li class="flex items-center">
                              <input value="{{ $status }}" type="checkbox" name="status"
                                  class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                              <label for="{{ $status }}"
                                  class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                  {{ $status }}
                              </label>
                          </li>
                      @endforeach
                  </ul>
              </form>
          </div>

      </div>
  </div>
  <script>
      async function searchViaCategory(uri) {
          try {
              const response = await axios.get(uri, {
                  headers: {
                      "X-CSRF-TOKEN": document.querySelector(
                          'meta[name="csrf-token"]'
                      ).content,
                  },
              });
              console.log(response.data)
              return response.data;
          } catch (error) {
              console.error(error);
          }
      }
      async function getCategory() {
          let query = "";
          const program = document.querySelectorAll(
              '#search_program input[name="program"]:checked'
          );
          const lvl = document.querySelectorAll(
              '#search_lvl input[name="lvl"]:checked'
          );
          const set = document.querySelectorAll(
              '#search_set input[name="set"]:checked'
          );
          const status = document.querySelectorAll(
              '#search_status input[name="status"]:checked'
          );
          const program_data = Array.from(program).map((cb) => cb.value);
          const lvl_data = Array.from(lvl).map((cb) => cb.value);
          const set_data = Array.from(set).map((cb) => cb.value);
          const status_data = Array.from(status).map((cb) => cb.value);
          query += "&&program=";
          program_data.forEach((element) => {
              query += element + ",";
          });
          query += "&&lvl=";

          lvl_data.forEach((element) => {
              query += element + ",";
          });
          query += "&&set=";

          set_data.forEach((element) => {
              query += element + ",";
          });
          query += "&&status=";

          status_data.forEach((element) => {
              query += element + ",";
          });
          const data = await searchViaCategory('{{ $route }}' + "?" + query);
          // UPDATE TABLE
          const students = data.students;
          renderTable(students.data);
          renderPagination(students);
      }
  </script>
