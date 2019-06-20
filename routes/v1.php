<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
], function ($api) {
    $api->post('/register', 'UserController@register');
    $api->post('/comment', 'CommentController@register');
    $api->delete('/delete-comment', 'CommentController@delete');
    $api->get('/notification', 'NotificationController@get');
});
