<?php


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('todos', 'TodoController@todos');
$router->post('add-todo', 'TodoController@addTodo');
$router->get('todos/{id}', 'TodoController@getATodo');
$router->post('update-todo/{id}', 'TodoController@updateTodo');
$router->delete('delete-todo/{id}', 'TodoController@deleteTodo');