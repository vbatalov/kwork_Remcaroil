<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

При необходимости выполните 

`git clone https://github.com/vbatalov/kwork_Remcaroil`

Разработка чат бота для сбора заявок и отправка в Битрикс

Инструкция по настройке

Настройте MySQL подключения и токен бота в .env файлe

`BOT_API = "token" `

`BOT_URL = "url"` 

1. Сгенерируйте ключ<br>  
`php artisan key:generate`
2. Создайте миграцию <br>   
`php artisan migrate`

3. Выполните команду для сохранения изображений<br>  
`php artisan storage:link` 
4. Перейдите на свой сайт и зарегистрируйте токен <br>  
`https://{yourWebSite.com}/register` <br>  
5. Готово
