<script type="text/javascript">
    @if($errors->any())
    @foreach($errors->all() as $error)
    itoastr('error','{{$error}}');
    @endforeach
    @endif
    @if ($message = Session::get('success'))
    itoastr('success','{!! $message !!}');
    @endif
    @if ($message = Session::get('status'))
    itoastr('success','{!! $message !!}');
    @endif
    @if ($message = Session::get('error'))
    itoastr('error','{!! $message !!}');
    @endif
    @if ($message = Session::get('info'))
    itoastr('info','{!! $message !!}');
    @endif

    @if (Session::has('linkToastr'))
    iziToast.question({
        timeout: false,
        close: true,
        overlay: true,
        displayMode: 'once',
        id: 'question',
        zindex: 999,
        message: '{!! Session::get('linkText') ?? '' !!}',
        position: 'center',
        buttons: [
            ['<button><b>{{Session::get('linkToastr')}}</b></button>', function () {

                window.location.href="{{Session::get('linkLink') ?? ''}}";

            }, true],
            ['<button>NO</button>', function (instance, toast) {

                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

            }],
        ],
        onClosing: function(instance, toast, closedBy){
            console.info('Closing | closedBy: ' + closedBy);
        },
        onClosed: function(instance, toast, closedBy){
            console.info('Closed | closedBy: ' + closedBy);
        }
    });
    @endif

</script>
