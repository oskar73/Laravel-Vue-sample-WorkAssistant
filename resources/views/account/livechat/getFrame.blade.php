<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>LiveChat ChatBox | Bizinabox</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{mix('assets/resources/css/style.css')}}" />
    <link rel="stylesheet" href="{{mix('assets/resources/css/chatbox.css')}}" />
</head>
<body>
    <div id="chatbox">
        <div class="chat_list">
            <div class="panel-heading">
                <div class="toggle_btn">&#x39E;</div>
                <input type="search" class="list_search_area" placeholder="Search..">
                <div class="panel-heading-title">
                    <span class="panel-heading-item active"
                          data-tab = "guest"
                    >Guests</span>
                    <span class="panel-heading-item"
                          data-tab = "user"
                    >Users</span>
                    <span class="panel-heading-item"
                          data-tab = "team"
                    >Teams</span>
                </div>
            </div>
            <div class="panel-list-area">
                <div class="panel_guest_list_area panel_list_div active ">

                </div>
                <div class="panel_user_list_area panel_list_div">

                </div>
                <div class="panel_team_list_area panel_list_div">

                </div>
            </div>
        </div>
        <div class="chat_content text-center">
            <div class="chat_container">
                <div class="chat_area">
{{--                    <div class="goto_bottom d-none"><i class="fa fa-arrow-down"></i></div>--}}
                    <ul id="messages" class="message_div">

                    </ul>
                    <form id="submit_form" class="chat-form" method="POST">
                        @csrf
                        <div class="form-group position-relative d-flex justify-content-between w-100 chat_area_bottom">
                            <div class="answer-div">
                                <div class="typing-watch"></div>
                                <div class="answer_container">
                                    <textarea class="form-control answer input_element" id="m" name="m" placeholder="Type a message" disabled></textarea>
                                </div>
                            </div>
                            <div class="right-div d-flex">
                                <div class="row mr-0 mr-md-3 mt-auto">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <button type="button" class="submit_btn input_element" id="submit_btn" title="Submit" disabled>
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="file-div">
                                            <i id="file-icon" class="fas fa-paperclip"></i>
                                            <i id="file-loading" title="Uploading..." style="display: none" class="fas fa-circle-notch fa-spin"></i>
                                            <input type="file" id="livechat-file-upload" title="Add Files" class="input_element" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="detail-panel-area">
            <div class="detail_content">
                <span class="closeBtn">×</span>
                <div class="data_area">

                </div>
            </div>
        </div>
    </div>
</body>

<script src="{{mix('assets/resources/js/script.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.2/socket.io.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
<script>
    var token = $('meta[name="csrf-token"]').attr('content');
    var loading_icon="{{asset('assets/img/typing.gif')}}";
    var my_id = "{{user()->id}}", my_name="{{user()->name}}", my_image="{{user()->image}}", socket_server = "{{option("socket_server", '')}}";
    var role = @if(Request::is("employee*"))"employee"@elseif(Request::is("client*"))"client"@endif
</script>
<script src="https://cdn.jsdelivr.net/npm/autosize@6.0.1/dist/autosize.min.js"></script>
<script src="{{asset('assets/js/account/livechat/chatbox.js')}}"></script>
</html>
