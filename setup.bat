@echo off
chcp 65001 >nul
echo ================================================
echo   Установка проекта "Ведьмина метла"
echo ================================================
echo.

set PHP=C:\OSPanel\modules\PHP-8.3\PHP\php.exe
set COMPOSER=C:\OSPanel\data\PHP-8.3\default\composer\composer.phar
set MYSQL=C:\OSPanel\modules\MySQL-8.0\bin\mysql.exe

echo [1/5] Создаю базу данных vedma...
%MYSQL% -u root -h 127.127.126.26 -e "CREATE DATABASE IF NOT EXISTS vedma CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if %errorlevel% neq 0 (
    echo.
    echo ОШИБКА: MySQL не запущен! Запусти MySQL через OSPanel и попробуй снова.
    pause
    exit /b 1
)
echo OK!

echo [2/5] Устанавливаю PHP-зависимости...
%PHP% %COMPOSER% install --no-interaction --prefer-dist
echo OK!

echo [3/5] Запускаю миграции...
%PHP% artisan migrate --force
echo OK!

echo [4/5] Заполняю базу данными...
%PHP% artisan db:seed --force
echo OK!

echo [5/5] Собираю фронтенд...
call npm install
call npm run build
echo OK!

echo.
echo ================================================
echo   Готово! Открой http://vedma в браузере
echo ================================================
echo.
pause
