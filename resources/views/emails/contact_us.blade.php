<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Message from Roadside Contact Form</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= asset('public/dist/css/AdminLTE.min.css') ?>">
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="20" cellspacing="0" width="700" id="emailContainer" style="background: #fff">
                <tr>
                </tr>
                <tr>
                    <td align="center" valign="top" style="color: #000;font-size:33px;font-weight: 500; font-family: 'Lato';padding: 20px 105px 40px;">
                        Welcome to RoadSide !
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="color: #000;font-size:15px;font-family: 'Lato';line-height: 2.3;padding: 0 62px;">
                       First Name : "{{$first_name}}"
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="color: #000;font-size:15px;font-family: 'Lato';line-height: 2.3;padding: 0 62px;">
                        Last Name : "{{$last_name}}"
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="color: #000;font-size:15px;font-family: 'Lato';line-height: 2.3;padding: 0 62px;">
                        Email : "{{$email}}"
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="color: #000;font-size:15px;font-family: 'Lato';line-height: 2.3;padding: 0 62px;">
                        Phone Number : "{{$phone_number}}"
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top" style="color: #000;font-size:15px;font-family: 'Lato';line-height: 2.3;padding: 0 62px;">
                        Message : <textarea>"{{$email_message}}"</textarea>
                    </td>
                </tr>
                <tr>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
