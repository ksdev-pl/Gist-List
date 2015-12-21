@if (count($errors->all()))
    <script>
        @foreach ($errors->all() as $error)
            Messenger().post({
                message: "{{ $error }}",
                type: "error"
            });
        @endforeach
    </script>
@endif
@if (Session::has('status'))
    <script>
        Messenger().post({
            message: "{{ Session::get('status') }}",
            type: "success"
        });
    </script>
@endif
@if (Session::has('flash_notification.message'))
    @if (Session::has('flash_notification.overlay'))
        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
    @else
        <script>
            Messenger().post({
                message: "{{ Session::get('flash_notification.message') }}",
                @if (Session::get('flash_notification.level') == 'danger')
                    type: "error"
                @else
                    type: "{{ Session::get('flash_notification.level') }}"
                @endif
            });
        </script>
    @endif
@endif
