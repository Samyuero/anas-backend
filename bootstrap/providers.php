<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\RouteServiceProvider::class,  // ← Add this line
    CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider::class,
];