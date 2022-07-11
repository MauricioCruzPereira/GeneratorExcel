<?php

namespace App\Entity;

use App\Db\Database;

/**
 * author Mau do back
 */
class Excel{

    //cor padrão
    public $cor = '#99ccff';

    //nome padrão
    public $nameArquivo = 'arquivoExcelGerado';

    //responsável por iniciar a conexão com o banco
    //mudar o banco ou outro dado caso precise
    public function __construct(){
        Database::config('host','banco','senha','user');
    }

    /**
     * Método responsável por forçar o arquivo a baixar e em transformar em formato .xlsx
     * @param string $html
     * @return Xlsx
     */ 
    private function geraExcelDownload($html){
        //baixa o arquivo já no formato de excel na parta downloads
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"{$this->nameArquivo}.xlsx\"");
        header("Cache-Control: max-age=0");
        return $html;
    }

    /**
     * Método responsável por gerar um excel e salvar nas pastas download
     * @param Dados $dados
     * @param string $fields
     * @return .xls
     */
    private function Excel($dados,$fields){
        // Criamos uma tabela HTML com o formato da planilha
        $html = '';

        $html .= '<table border="1">';

        //fazer um foreach com os dados recebidos do banco

        //transformar os campos desejados em formato array
        $fields = explode(',',$fields);

        $html .= "<tr>";
        foreach($fields as $field){
            $html .= "<td style='background-color: {$this->cor}; font-size:20px; color:white;text-align:center;'><b>".$field."</b></td>";
        }
        $html .= '</tr>';

        $contador = 1;
        while ($dado = $dados->fetchObject()){
            $html .= "<tr style='font-size:15px'>";
            foreach($fields as $field){
                if($contador %2 == 0){
                    $html .= "<td style='background-color: #d9d9d9; text-align:center;'>".$dado->$field."</td>";
                }
                else{
                    $html .= "<td style='background-color: #ffffff; text-align:center;'>".$dado->$field."</td>";
                }
                //$html .= "<td>". $dado->$field ."</td>"; 
            }
            $html .= '</tr>';

            $contador++;
        }

        $html .= '</table>';

        echo '<pre>';var_dump($html);echo '</pre>';exit;

        $html = $this->geraExcelDownload($html);
        return $html;
    }

    /**
     * Método responsável por pegar os dados passados na query e os fields e passar para a função que gerará um Excel
     * @param string $where
     * @param string $order
     * @param integer $limit
     * @param string $fields
     * @return Excel
     */
    public function SelectDicesAndTransformExcel($where = null,$order = null,$limit=null,$fields){
        //gerando a query para selecionar os dados
        //substituir o '' dentro do database para a tabela que deseja
        $dados = (new Database('pedido'))->select($where, $order, $limit,$fields);
        $retorno = $this->Excel($dados,$fields);
        return $retorno;
    }
}