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
    $users = $db->dbQuery("SELECT * FROM users WHERE user_name=:uname", ['uname' => 'Владислав'], "fetch");
``` 
   3 аргумент dbQuery по умолчанию fetchAll
   
   Параметр можно заранее вбить в массив:
   
```php
<?php 
    $data["parameter"] = value;
``` 
   Пример:
```php
<?php 
    $data["uname"] = "Владислав";
    $data["uemail"] = "mail@google.com"
    $users = $db->dbQuery("SELECT * FROM users WHERE user_name=:uname and user_email=:uemail", $data, "fetch");
``` 
   
