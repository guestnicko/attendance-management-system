  @props(['events' => '', 'route' => ''])
  <div class="">
      <select name="event_id" id="eventField" onchange="fetchLogsByEvent('{{ $route }}')"
          class="block w-full text-lg text-gray-500 bg-transparent border-0 border-b-2 border-violet-500 appearance-none ">
          <option value="" selected>Select Event</option>
          @foreach ($events as $event)
              <option value="{{ $event->id }}">{{ $event->event_name }}</option>
          @endforeach
      </select>
  </div>

  <script>
      async function Eventsearch(uri, data) {
          uri = uri + "?event_id=" + data;
          const response = await axios.get(uri, {
              headers: {
                  "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                      .content,
              },
          });
          return response.data.students;
      }

      async function fetchLogsByEvent(uri) {
          const event = document.querySelector("#eventField");
          const students = await Eventsearch(uri, event.value);
          // UPDATE TABLE
          if (students != null) {
              renderTable(students.data);
              renderPagination(students);
          } else {
              renderTable(null);
              renderPagination(students);
          }
      }
  </script>
