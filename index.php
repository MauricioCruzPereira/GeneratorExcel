

<?php
require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

use \App\Entity\Excel;
use \App\Db\Database;

//Inicia conexão com o banco de dados
Database::config('host','banco','senha','user');

//Escolha a tabela que será analisada
$conn = new Database('tabela');
//os campos a serem pegos
$fields = 'campos desejado';
//Recebe o resultado da query
$result = $conn->select("DIGITE A WHERE",'','',$fields);

//Passa o resultado da query e os campos pegos
$excel = new Excel($result,$fields);
//muda o nome do arquivo que será baixado
$excel->nameArquivo = 'teste';
//Muda a cor da primeira linha do excel
$excel->cor = 'red';
//Gera o excel
$excelGerado = $excel->generateExcel();

//Baixa o excel
echo $excelGerado;