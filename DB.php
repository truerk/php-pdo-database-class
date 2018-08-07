<?php
/**
 * @author Truerk
 * Class DB
 */


class DB
{
    private $pdo;#Переменная для подключения к базе данных
    private $host = "localhost";
    private $dbname = "bd_test";
    private $charset = "utf8";
    private $uname = "root";
    private $upass = "";
    private $connectCheck = false;#Переменная для проверки подключения базы данных
    private $query;#Переменная для работы с запросом
    private $rowOperator;#Запрос в виде массива
    private $operator;#Оператор sql
    private $dsn;
    private $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    #=================================================================================================================#
    /**
     * DB constructor.
     */
    function __construct()
    {
        $this->dbconnect();
    }

    #=================================================================================================================#
    /**
     * DB destruct.
     */
    function __destruct()
    {
        $this->dbClose();
    }

    #=================================================================================================================#
    /**
     * DB connect - подключение к базе данных
     */
    function dbConnect()
    {
        $this->dsn = "mysql:host=$this->host; dbname=$this->dbname; charset=$this->charset";
        try {
            $this->pdo = new PDO($this->dsn, $this->uname, $this->upass, $this->opt);
            $this->connectCheck = true;
        } catch (Exception $e) {
            throw new Exception("Ошибка подключения базы даныых!");;
            die();
        }
    }

    #=================================================================================================================#
    /**
     * Метод отключения базы данных
     */
    function dbClose(){
        $this->pdo = null;
    }

    #=================================================================================================================#
    /**
     * Метод запроса к базе данных
     * @param $sql - sql запрос
     * @param null $param - pdo параметры
     * @param string $mode
     * @return mixed
     * @throws Exception
     */
    function dbQuery($sql, $param = null, $mode = "fetchAll")
    {
        #Если бд не подключена, подключаемся
        if (!$this->connectCheck) {
            $this->dbconnect();
        }

        #Проверка mode
        switch ($mode) {
            case "fetchAll":
                $mode = "fetchAll";
                break;
            case "fetchall":
                $mode = "fetchAll";
                break;
            case "fetch":
                $mode = "fetch";
                break;
            case "rowCount":
                $mode = "rowCount";
                break;
            case "rowcount":
                $mode = "rowCount";
                break;
            default:
                throw new Exception("Неправильно задан мод!");
                die();
        }

        #Проверяем какой пришел запрос
        $this->rowOperator = explode(" ", $sql);
        $this->operator = strtolower($this->rowOperator[0]);

        #Подготавливаем запрос
        try {
            $this->query = $this->pdo->prepare($sql);
        } catch (Exception $e) {
            throw new Exception("Ошибка в запросе!");
            die();
        }

        #Если есть параметры, биндим их
        if ($param != null and is_array($param)) {
            #Чистим от пробелов и тегов html
            $param = $this->clearParameters($param);
            foreach ($param as $key => $value) {
                try {
                    $this->query->bindParam($key, $value);
                } catch (Exception $e) {
                    throw new Exception("Ошибка в параметрах запроса!");
                    die();
                }
            }
        }

        #Выполняем запрос, если все хорошо отправляем данные
        if (!$this->query->execute()) {
            throw new Exception("Запрос не выполнен!");
            die();
        } else {
            #Проверяем оператора и возвращаем данные
            if ($this->operator === "select") {
                return $this->query->$mode();
            } elseif ($this->operator === "insert" || $this->operator === "update" || $this->operator === "delete") {
                return $this->query->rowCount();
            } else {
                return null;
            }
        }
    }

    #=================================================================================================================#
    /**
     * Метод возврата последнего добавленого id
     * @return mixed
     */
    function dbLastId()
    {
        return $this->pdo->lastInsertId();
    }

    #=================================================================================================================#
    /**
     * Метод очистки от пробелов и html тегов
     * @param $param
     * @return mixed
     */
    private function clearParameters($param)
    {
        foreach ($param as $key => $value) {
            $param[$key] = htmlspecialchars(trim($value));
        }
        return $param;
    }


}