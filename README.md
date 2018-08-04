# DB Class
## Работа с классом
##### 1. Подключить файл класса:
```php
<?php 
    require_once('DB.php');
``` 

##### 2. Обьект класса:
```php
<?php 
    $db = new DB();
``` 
 
##### 3. Использовать общий запрос:
```php
<?php 
    $users = $db->dbQuery("SELECT * FROM users");
``` 

##### 4. Использовать общий запрос с параметром:
```php
<?php 
    $users = $db->dbQuery("SELECT * FROM users WHERE user_name=:uname", ['uname' => 'Владислав']);
``` 
   
