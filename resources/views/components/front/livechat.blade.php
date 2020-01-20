<div id="chat_biz_area">
    <div class="biz-chat-area" x-show="form">
        <div class="biz-chat-title biz-drag-header">
            <img src="{{asset('assets/img/comment.png')}}" class="livechat-facivon">
            <span class="d-inline-block tw-pb-1.5 tw-pl-1">Chat with Bizinabox</span>
            <div class="tw-ml-auto">
                <a href="javascript:void(0)" class="biz-contact-close biz-chat-menu-icon">
                    <i class="fa fa-times font-size20"></i>
                </a>
                <a href="javascript:void(0)" class="live-chat-open-window biz-chat-menu-icon" style="font-size: 14px">
                    <i class="fa fa-external-link-alt"></i>
                </a>
                <a href="javascript:void(0)" class="contact-minus-btn biz-chat-menu-icon tw-text-sm">
                    <i class="fa fa-window-minimize"></i>
                </a>
            </div>
        </div>
        <div class="biz-chat-iframe-div">
        </div>
    </div>
    <div class="bizchat-fixed-bottom-right">
        <a href="javascript:void(0)" class="contact-div width-100 d-block biz-contact-a">
            <img src="{{asset('assets/img/contact.png')}}" alt="Contact Us">
            <p>Contact Us</p>
        </a>
    </div>
</div>

<script>
    var biz_chat_icon = $('#chat_biz_area .biz-contact-a');
    var biz_chat_triggered = 0;
    var $close_btn = 0;

    if(localStorage.getItem('chatOpened'))
    {
        chatToggleFab();
        chatMinFab();
    }
    biz_chat_icon.click(function() {
        chatToggleFab();
        if(!localStorage.getItem('chatOpened'))
        {
            localStorage.chatOpened = true;
        }
        console.log(biz_chat_triggered);
        if(biz_chat_triggered===0)
        {
            getChatForm();
        }
    });

    $(document).on('click', '#chat_biz_area .fa-window-minimize', function() {
        chatMinFab();
    });
    $(document).on('click', '#chat_biz_area .live-chat-open-window', function() {
        window.open("/livechat", "LiveChat", "width=572, height=759");
    });

    $(document).on('click', '#chat_biz_area .fa-angle-up', function() {
        $(this).removeClass("fa-angle-up").addClass("fa-window-minimize");
        $(".biz-chat-iframe-div").show();
        $(".biz-chat-title").toggleClass("biz-livechat-min-position");
        $('.biz-chat-title').toggleClass('biz-drag-header');

        if(biz_chat_triggered===0)
        {
            getChatForm();
        }

    });

    function chatToggleFab() {
        biz_chat_icon.toggleClass('d-block');
        $('.biz-chat-area').toggleClass('d-block');
    }

    function chatMinFab()
    {
        $('#chat_biz_area .fa-window-minimize').removeClass("fa-window-minimize").addClass("fa-angle-up");
        $("#chat_biz_area .biz-chat-iframe-div").hide();
        $("#chat_biz_area .biz-chat-title").toggleClass("biz-livechat-min-position");
        $('#chat_biz_area .biz-chat-title').toggleClass('biz-drag-header');
    }

    function getChatForm()
    {
        $(".biz-chat-iframe-div").html('<iframe src="/livechat" class="biz-live-chat biz-chat-iframe-area" id="livechat-iframe"></iframe>');

        biz_chat_triggered = 1;
    }
    $(".biz-contact-close").click(function () {
        if($close_btn===0)
        {
            $(".biz-chat-iframe-div").append("<div class='biz-livechat-close-screen'>Are you sure you want to end the conversation? <br><a href='javascript:void(0);' class='chat-close-no-btn btn btn-outline-primary border-radius-0 btn-square'>No</a><a href='javascript:void(0);' class='chat-close-yes-btn btn btn-success border-radius-0 btn-square'>Yes</a><br><br><label><input type='checkbox' id='chat_transfer_checkbox'/> Chat Transcript to email.</label><br class='chat_transfer_br'></div>")
            $close_btn = 1;
        }
    });
    $(document).on('click', '.chat-close-no-btn', function() {
        $(".biz-livechat-close-screen").remove();
        $close_btn = 0;
    });
    $(document).on('click', '.chat-close-yes-btn', function() {
        let $transfer = $("#chat_transfer_checkbox").prop("checked")==true?1:0;

        $.ajax({
            url: "/livechat/end",
            method: 'get',
            data:{transfer:$transfer},
            success: function(result)
            {
                $(".biz-chat-error-div").remove();
                if(result.status===1)
                {
                    localStorage.removeItem('chatOpened');
                    chatToggleFab();
                    $close_btn = 0;
                    $(".biz-livechat-close-screen").remove();
                    biz_chat_triggered = 0;
                    if(result.data===0)
                    {
                        document.getElementById('livechat-iframe').contentWindow.$(".close_btn").trigger('click');
                    }

                }else {
                    $("#chat_transfer_email").after("<div class='biz-chat-error-div text-danger'></div>");
                    $.each(result.data, function(index, item) {
                        $(".biz-chat-error-div").append(item);
                    });
                }
            },
        });

    });
</script>
