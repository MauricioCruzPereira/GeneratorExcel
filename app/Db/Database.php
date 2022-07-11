<?php

namespace App\Db;

use \PDO;
use \PDOException;

/**
 * Class responsável por iniciar a conexão com o banco de dados
 * e realizar as querys
 * @author Mauricio Pereira <mauricio.pereira.webart@gmail.com>
 */
class Database{

    /**
     * Host de conexão com o banco de dados
     * @var string
     */
    private static $host;

    /**
     * Nome do banco de dados
     * @var string
     */
    private static $name;

    /**
     * Porta do banco de dados
     * @var integer
     */
    private static $port;

    /**
     * Senha do banco de dados
     * @var string
     */
    private static $pass;

    /**
     * Usuário do banco de dados
     * @var string
     */
    private static $user;

    /**
     * Nome da tabela
     * @var [type]
     */
    private $table;

    /**
     * Instancia de conexão com o banco de dados
     * @var PDO
     */
    private $connection;

    /**
     * Método responsável por configurar a classe
     * @param string $host
     * @param string $name
     * @param string $pass
     * @param string $user
     * @param integer $port
     */
    public static function config($host,$name,$pass,$user,$port = 3306){
        self::$host = $host;
        self::$name = $name;
        self::$port = $port;
        self::$pass = $pass;
        self::$user = $user;
    }

    /**
     * Define a tabela, instancia e conexão
     * @param string $table
     */
    public function __construct($table){
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * método responsável por criar uma conexão com o banco de dados
     */
    private function setConnection(){
        try{
            $this->connection = new PDO(
                "mysql:host=".self::$host.
                ';dbname='.self::$name.
                ';port='.self::$port,
                self::$user,self::$pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Error: ". $e->getMessage());
        }
    }

    /**
     * Método responsável por executar querys no banco de daods
     * @param string $query
     * @param array $params
     * @return void
     */
    private function execute($query,$params = []){
        try{
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;
        }catch(PDOException $e){
            die("Error: ". $e->getMessage());
        }
    }

    /**
     * Método responsável por inserir um registro no banco de dados
     * @param array $values
     * @return integer ID inserido
     */
    public function insert($values){
        //DADOS DA QUERY
        $fields = array_keys($values);
        $binds = array_pad([],count($fields), '?');

        //MONTA A QUERY
        $query = 'INSERT INTO '. $this->table. ' (' . implode(',',$fields) . ') VALUES(' . implode(',',$binds) . ')';

        //MONTA A QUERY
        $this->execute($query, array_values($values));

        //RETORNA O ID INSERIDO
        return $this->connection->lastInsertId();
    }

    /**
     * Método responsável por buscar um dado no banco
     * @param string $where
     * @param string $oder
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public function select($where = null, $oder=null,$limit = null, $fields = '*'){
        //DADOS DA QUERY
        //VERIFICA SE AS VARIÁVEIS POSSUEM VALORES,
        //SE TIVEREM RECEBEM UM PREFIXO MAIS A VARIVAL
        //SE NÃO IRÁ RECEBER UM VALOR VÁZIO
        $where = strlen($where) ? 'WHERE '. $where : '';
        $oder = strlen($oder)   ? 'ORDER BY '. $oder : '';
        $limit = strlen($limit) ? 'LIMIT '. $limit : '';

        //MONTA A QUERY
        $query = 'SELECT '.$fields.' FROM '.$this->table. ' ' .$where. ' ' .$oder. ' ' .$limit;
        //EXECUTA O RESULTADO DA QUERY
        return $this->execute($query);
    }

    /**
     * Método responsável por atualizar um registro no banco de dados
     * @param string $where
     * @param array $values
     * @return boolean
     */
    public function update($where,$values){
        //DADOS DA QUERY
        $fields = array_keys($values);

        //MONTA A QUERY
        $query = 'UPDATE ' .$this->table. ' SET ' .implode('=?,',$fields). '=? WHERE ' . $where;

        //EXECUTA A QUERY
        $this->execute($query,array_values($values));

        //RETORNA SUCESSO
        return true;
    }

    /**
     * Método responsável por excluir um registro no banco de dados
     * @param string $where
     * @return void
     */
    public function delete($where){
        //MONTA A QUERY
        $query = 'DELETE FROM ' .$this->table. ' WHERE ' .$where;

        //EXECUTA A QUERY
        $this->execute($query);

        //RETORNA SUCESSO
        return true;
    }


}
