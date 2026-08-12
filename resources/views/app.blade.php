<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ведьмина метла — цветочный салон в Таганроге. Букеты из свежих цветов, ручная сборка, доставка за 24 часа. Заказать цветы в Таганроге с доставкой.">
    <meta name="keywords" content="цветы Таганрог, букеты Таганрог, доставка цветов Таганрог, купить букет Таганрог, цветочный салон Таганрог, Ведьмина метла">
    <meta name="geo.region" content="RU-ROS">
    <meta name="geo.placename" content="Таганрог">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="Ведьмина метла">
    <meta property="og:title" content="Ведьмина метла — цветы и букеты в Таганроге с доставкой">
    <meta property="og:description" content="Цветочный салон в Таганроге. Свежие букеты ручной сборки, доставка за 24 часа.">

    <title>{{ config('app.name', 'Ведьмина метла') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Caveat:wght@600&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Florist",
        "name": "Ведьмина метла",
        "description": "Цветочный салон в Таганроге. Букеты из свежих цветов, ручная сборка, доставка за 24 часа.",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Таганрог",
            "addressRegion": "Ростовская область",
            "addressCountry": "RU"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": 47.2362,
            "longitude": 38.8969
        },
        "areaServed": {
            "@type": "City",
            "name": "Таганрог"
        },
        "priceRange": "₽₽",
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
            "opens": "09:00",
            "closes": "21:00"
        }
    }
    </script>

    @vite(['resources/client/app.js', 'resources/client/assets/scss/app.scss'])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
