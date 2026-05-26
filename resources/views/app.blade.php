<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Ведьмина метла') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/client/app.js', 'resources/client/assets/scss/app.scss'])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
