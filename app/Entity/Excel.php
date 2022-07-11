<?php

namespace App\Entity;

/**
 * author Mau do back
 */
class Excel{

    //cor padrão
    public $cor = '#99ccff';

    //nome padrão
    public $nameArquivo = 'arquivoExcelGerado';

    private $dados;
    private $fields;

    //responsável por iniciar a conexão com o banco
    //mudar o banco ou outro dado caso precise
    public function __construct($dados, $fields){
       $this->dados = $dados;
       $this->fields = $fields;
    }

    /**
     * Método responsável por forçar o arquivo a baixar e em transformar em formato .xlsx
     * @param string $html
     * @return Xlsx
     */ 
    private function transformFormatExcelAndDownload($html){
        //baixa o arquivo já no formato de excel na parta downloads
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"{$this->nameArquivo}.xlsx\"");
        header("Cache-Control: max-age=0");
        //retorna o Excel
        return $html;
    }

    /**
     * Método responsável por gerar um excel e salvar nas pastas download
     * @param Dados $dados
     * @param string $fields
     * @return .xls
     */
    private function MontaExcel(){
        // Criamos uma tabela HTML com o formato da planilha
        $html = '';

        $html .= '<table border="1">';

        //fazer um foreach com os dados recebidos do banco

        //transformar os campos desejados em formato array
        $fields = explode(',',$this->fields);

        //cria o layout em formato de table
        $html .= "<tr>";
        foreach($fields as $field){
            $html .= "<td style='background-color: {$this->cor}; font-size:20px; color:white;text-align:center;'><b>".$field."</b></td>";
        }
        $html .= '</tr>';

        $contador = 1;
        while ($dado = $this->dados->fetchObject()){
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

        //transforma de table para formato excel
        $excel = $this->transformFormatExcelAndDownload($html);

        //retorna a tabela processada já em formato excel
        return $excel;
    }

    /**
     * Método responsável por pegar os dados passados na query e os fields e passar para a função que gerará um Excel
     * @param string $where
     * @param string $order
     * @param integer $limit
     * @param string $fields
     * @return Excel
     */
    public function generateExcel(){
        //gerando a query para selecionar os dados
        //substituir o '' dentro do database para a tabela que deseja
        $retorno = $this->MontaExcel();
        //Retorna os dados processados em formato de Excel
        return $retorno;
    }
}