<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirection</title>
</head>
<body>
    <form id="redirect-form" action="{{ route('interventions.plan', ['id' => $intervention->id]) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="user_id" value="{{ $intervention->user->id }}">
    </form>
    <script>
        document.getElementById('redirect-form').submit();
    </script>
</body>
</html>
