<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>LiveChat | Bizinabox</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}
    @vite(['resources/sass/app.scss', 'resources/sass/livechat.scss', 'resources/sass/component/front/chat.scss',  'resources/js/chat.js'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="{{ s3_asset('vendors/fontawesome/fontawesome.min.css') }}" rel="stylesheet"/>
</head>
<body>
    <span class="close_btn">x</span>
    <div id="livechat" class="container position-relative h-100 p-0">
        <ul id="messages">

        </ul>
        <form id="biz-chat-form" style="display: none;">
            <div class="biz-chat-bottom tw-flex tw-items-center tw-relative">
                <div class="biz-livechat-typing-text"></div>
                <label class="biz-chat-file">
                    <i id="file-icon" class="fas fa-plus-circle"></i>
                    <i id="file-loading" title="Uploading..." style="display: none" class="fas fa-circle-notch fa-spin"></i>
                    <input type="file" hidden id="livechat-file-upload" title="Add Files" class="input_element" disabled>
                </label>
                <textarea
                    name=""
                    id="m"
                    class="biz-livechat-text input_element"
                    autocomplete="off"
                    placeholder="Enter text here"
                    disabled
                ></textarea>

                <div class="biz-chat-send input_element" id="submit_btn" disabled>
                    <i class="fa fa-paper-plane"></i>
                </div>
            </div>
        </form>
    </div>
</body>


<script>
  let token = $('meta[name="csrf-token"]').attr('content'),
     socket_server = "{{option("socket_server", '')}}";
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.2/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autosize@6.0.1/dist/autosize.min.js"></script>
<script src="{{asset('assets/js/livechat.js')}}"></script>
</html>
