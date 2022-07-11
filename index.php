<?php
require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

use \App\Entity\Excel;

//instancia o Excel;
$excel = new Excel();

//use isso para alterar a cor caso queira
//$excel->cor = 'red';

//muda o nome do arquivo que será gerado
$excel->nameArquivo = 'pedidosCompletos';
// passa os parametros que deseja no where e os campos que quer pegar, caso para melhor entendimento consulta o arquivo Database.php
$html = $excel->SelectDicesAndTransformExcel("id_revendedor != 0 AND data_emissao >= '2022-05-01 00:00:00'AND data_emissao < '2022-07-1 00:00:00'",'','','id,id_revendedor');

//imprime o excel
echo $html;
