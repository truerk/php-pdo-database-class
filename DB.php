<?php
/**
 * @author Truerk
 * Class DB
 */


class DB
{

    private $connection;
    private $host = "localhost";
    private $dbname = "bd_test";
    private $charset = "utf8";
    private $uname = "root";
    private $upass = "";
    private $connect_check = false;
    private $query;
    private $parametrs = [];
    private $dsn;
    private $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ];


    /**
     * DB constructor.
     */
    function __construct()
    {
        $this->dbconnect();
    }

    /**
     * DB connect
     */
    function dbConnect()
    {
        $this->dsn = "mysql:host=$this->host; dbname=$this->dbname; charset=$this->charset";
        try {
            $this->connection = new PDO($this->dsn, $this->uname, $this->upass, $this->opt);
            $this->connect_check = true;
        } catch (Exception $e) {
            throw new Exception('Ошибка подключения базы даныых!');;
            die();
        }
    }

    /**
     * DB query
     * @param $sql
     * @param null $param
     * @return mixed
     * @throws Exception
     */
    function dbQuery($sql, $param = null)
    {
        //если бд не подключена, подключаемся
        if (!$this->connect_check) {
            $this->dbconnect();
        }

        //Подготавливаем запрос
        try {
            $this->query = $this->connection->prepare($sql);
        } catch (Exception $e) {
            throw new Exception('Ошибка в запросе!');
            die();
        }

        //Если есть параметры, биндим их
        if ($param != null and is_array($param)) {
            foreach ($param as $key => $value) {
                try {
                    $this->query->bindParam($key, $value);
                } catch (Exception $e) {
                    throw new Exception('Ошибка в параметрах запроса!');
                    die();
                }
            }
        }

        //Выполняем запрос, если все хорошо отправляем данные

        if (!$this->query->execute()) {
            throw new Exception('Запрос не выполнен!');
            die();
        } else {
            return $this->query->fetchAll();
        }
    }

}