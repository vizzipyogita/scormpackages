<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    <p>Hello {{$ToUserName}},</p>
    <p>Your new login credintials for LMS is given below.<br>
      Email: {{$email}}<br>
      Password: {{$password}}
    </p>

    <br>
    <p>Best Regards</p>
    <p>{{ env('APPLICATION_NAME') }}</p>
  </body>
</html>