<!DOCTYPE html>
<html>
    <head>
        <title>Your invitation to Cryptocurrency Portfolio</title>
    </head>
    <body>
        <p>You have been invited to Cryptocurrency Portfolio. Please click the link below to register:</p>
        <a
            class="text-blue-600 underline italic"
            href="{{ url('/register?invite_token=' . $token) }}">
            {{ url('/register?invite_token=' . $token) }}
        </a>
    </body>
</html>
