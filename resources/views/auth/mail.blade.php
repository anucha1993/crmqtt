<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>

<body>

    <div id="mailsub" class="notification" align="center">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;">
            <tr>
                <td align="center" bgcolor="#eff3f8">
                    <table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 300px;">
                        <tr>
                            <td>
                                <!-- padding -->
                                <div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
                            </td>
                        </tr>
                        <!--content 1 -->
                        <tr>
                            <td align="center" bgcolor="#fbfcfd">
                                <table width="90%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center">
                                            <!-- padding -->
                                            <div style="height: 60px; line-height: 60px; font-size: 10px;"> </div>
                                            <div style="line-height: 44px;">
                                                <font face="Arial, Helvetica, sans-serif" size="5" color="#57697e" style="font-size: 34px;">
                                                    <span style="font-family: Arial, Helvetica, sans-serif; font-size: 34px; color: #57697e;">
                                                        เจริญมั่น คอนกรีต
                                                    </span>
                                                </font>
                                            </div>
                                            <!-- padding -->
                                            <div style="height: 40px; line-height: 40px; font-size: 10px;"> </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div style="line-height: 24px;">
                                                <font face="Arial, Helvetica, sans-serif" size="4" color="#57697e" style="font-size: 15px;">
                                                    <span style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #57697e; padding: 20px;">
                                                        เรียนคุณ  {{$items->name}} เนื่องจากคุณได้ทำการขอรหัสผ่านใหม่
                                                        <br> <label style="padding-left: 40px;">username : {{$items->email}}</label>
                                                        <br> <label style="padding-left: 40px;">password : {{$items->newpass}}</label>
                                                    </span>
                                                </font>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <div style="line-height: 24px;">

                                                  <font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#596167">
                                                        สอบถามข้อมูลเพิ่มเติมได้ที่ <a href="info.mangroveproject2020@gmail.com" target="_blank" style="font-family: Arial, Helvetica, sans-serif; font-size: 13px;">info.mangroveproject2020@gmail.com</a>
                                                    </font>

                                            </div>
                                            <!-- padding -->
                                            <div style="height: 20px; line-height: 20px; font-size: 10px;"> </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!--footer -->
                        <tr>
                            <td class="iage_footer" align="center" bgcolor="#ffffff">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" style="height: 60px;">
                                            <font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;">
                                                <span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #96a5b5;">
                                                   © Copyright 2002 All rights reserved by  เจริญมั่น คอนกรีต
                                                </span>
                                            </font>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!--footer END-->
                        <tr>
                            <td>
                                <div style="height: 80px; line-height: 80px; font-size: 10px;"> </div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </div>
</body>

</html>
