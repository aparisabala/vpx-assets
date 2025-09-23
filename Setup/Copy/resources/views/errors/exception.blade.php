<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Something went wrong</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f9f9f9; text-align:center; padding:50px; }
        .card { max-width:500px; margin:auto; background:#fff; border-radius:10px; padding:30px; box-shadow:0 4px 10px rgba(0,0,0,.1); }
        h1 { color:#d9534f; }
        p { color:#555; }
    </style>
</head>
<body>
    <div class="card">
        <h1>⚠ Oops! Something went wrong</h1>
        <p>We’re working to fix it. Please try again later.</p>

        {{-- Show error details only in debug mode --}}
        @if(config('app.debug'))
            <p><strong>Error:</strong> {{ $exception->getMessage() }}</p>
        @endif
    </div>
</body>
</html>
