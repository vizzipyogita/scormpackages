<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8" />
  </head>
  <body>
    <p>Dear {{$ToUserName}},</p>
    <p>
      On behalf of the American Softskill Academy, we extend a warm welcome to you as a valued member of our institution. We are thrilled to have you join our community, and we look forward to supporting your personal and professional development in the field of soft skills.
    </p>
    <p>
      To facilitate your access to our online platform, we have created a unique account for you. Please find below your login credentials:
    </p>
        @if($isGuestUser == 1)
          Url: <a href="{{env('APP_URL')}}/user/login">{{env('APP_URL')}}/user/login</a><br>
        @else
          Url: <a href="{{env('APP_URL')}}/login">{{env('APP_URL')}}/login</a><br>
        @endif
      Email: {{$email}}<br>
      Password: {{$password}}
      <p>
        These credentials will enable you to log in to our platform and explore the wide range of resources, courses, and interactive materials that we offer. We encourage you to take full advantage of our platform to enhance your soft skills and achieve your personal and career goals.
      </p>
    <p>
      If you have any questions or require assistance, please do not hesitate to reach out to our support team at support@americansoftskillacademy.com. We are here to help you every step of the way.
    </p>
    <p>Once again, welcome to the American Softskill Academy. We are excited to have you on board, and we are confident that your experience with us will be enriching and rewarding.</p>
    <br>
    <p>Best regards,</p>
    <p>{{ env('MAIL_TEAM_NAME') }}</p>
  </body>
</html>