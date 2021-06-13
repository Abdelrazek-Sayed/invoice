@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('success') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(Session::has('error'))

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('error') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


@if (Session::has('notify_success'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ session()->get('notify_success') }}",
                type: "success"
            })
        }

    </script>
@endif

@if (session()->has('notify_error'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ session()->get('notify_error') }}",
                type: "error"
            })
        }

    </script>
@endif

@if (session()->has('notify_delete'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ session()->get('notify_delete') }}",
                type: "warning"
            })
        }

    </script>
@endif
