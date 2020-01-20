<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Notification | BizinaBox</title>
    <style type="text/css">
        @media only screen and (max-width:480px) {
            table[class="MainContainer"],
            td[class="cell"] {
                width: 100% !important;
                height: auto !important;
            }

            td[class="specbundle"] {
                width: 100% !important;
                float: left !important;
                font-size: 13px !important;
                line-height: 17px !important;
                display: block !important;
                padding-bottom: 15px !important;
            }

            td[class="spechide"] {
                display: none !important;
            }

            img[class="banner"] {
                width: 100% !important;
                height: auto !important;
            }

            td[class="left_pad"] {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
        }
        @media only screen and (max-width:540px) {
            table[class="MainContainer"],
            td[class="cell"] {
                width: 100% !important;
                height: auto !important;
            }

            td[class="specbundle"] {
                width: 100% !important;
                float: left !important;
                font-size: 13px !important;
                line-height: 17px !important;
                display: block !important;
                padding-bottom: 15px !important;
            }

            td[class="spechide"] {
                display: none !important;
            }

            img[class="banner"] {
                width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</head>
<body paddingwidth="0" paddingheight="0" style="background-repeat: repeat; padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; margin: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased; width: 100%;background: #edf2f7;" offset="0" toppadding="0" leftpadding="0">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent" align="center" style="font-family:Helvetica, Arial,serif;">
        <tbody>
        <tr><td height="25"></td></tr>
        <tr>
            <td>
                <table width="800" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff" class="MainContainer"
                       style="box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);">
                    <tbody>
                    <tr><td height="25"></td></tr>
                    <tr>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td valign="top" width="40">&nbsp;</td>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                            <tr>
                                                <td class="movableContentContainer " valign="top">
                                                    <div class="movableContent" style="margin: 0; border: 0px; padding-top: 0px; position: relative;padding-bottom:10px;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                        <tbody>
                                                                        <tr>
                                                                            <td valign="top" align="center">
                                                                                <div class="contentEditableContainer contentImageEditable" style="margin: 0;">
                                                                                    <div class="contentEditable" style="margin: 0;">
                                                                                        <a href="{{route('home')}}" target="_blank">
                                                                                            <img src="{{asset('assets/img/logo.png')}}" width="251" alt="Logo" data-default="placeholder" data-max-width="560" style="border: 0; display: block; outline: none;">
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="movableContent" style="margin: 0; border: 0px; padding-top: 0px; position: relative;padding:25px;border:1px solid #dfe3e8">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                                            <tr>
                                                                <td align="left">
                                                                    <div class="contentEditableContainer contentTextEditable" style="margin: 0;">
                                                                        @foreach($body as $message)
                                                                            <div style="padding:20px;
                                                                                @if($message->from_type!='guest')
                                                                                    background-color: #eee;
                                                                                @else
                                                                                    border:1px solid #eee;
                                                                                @endif
                                                                                    margin-bottom:10px;">
                                                                                    <p style="width:100%;margin:0;content: '';clear: both;display: table;">
                                                                                        @if($message->from_type=='guest')
                                                                                            <span style="float:left;"><b>You</b></span>
                                                                                        @else
                                                                                            <span style="float:left;"><b>{{$message->from_name}}</b></span>
                                                                                        @endif
                                                                                        <span style="float:right;">{{$message->datetime}}</span>
                                                                                    </p>
                                                                                    <p style="text-align:left;">
                                                                                        @if($message->type=='text')
                                                                                            {{$message->message}}
                                                                                        @elseif($message->type=='image')
                                                                                            <a href="{{$message->image}}" target="_new">
                                                                                                <img style="max-width:300px;" src="{{$message->image}}"/>
                                                                                            </a>
                                                                                        @elseif($message->type=='file')
                                                                                            <a href="{{$message->image}}" target="_new">
                                                                                                <img style="width:100px" src="{{asset('assets/img/file.jpg')}}"/>
                                                                                            </a>
                                                                                        @endif
                                                                                    </p>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <!-- =============================== footer ====================================== -->
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td valign="top" width="20">&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr><td height="25"></td></tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    @include('components.mail.footer')
</body>
</html>
