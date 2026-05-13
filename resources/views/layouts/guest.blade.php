<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="p-4 bg-slate-50 min-h-screen"> 
    @yield('content')
</div>
</body>
</html>