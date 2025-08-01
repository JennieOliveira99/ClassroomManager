<?php
defined('BASEPATH') or exit('No direct script access allowed');

    class Relatorio extends CI_Controller{
          
        public function gerarMapaNovo(){
            try{
                $json = file_get_contents('php://input');
                $resultado = json_decode($json);

                if (empty($resultado->dataMapa)){
                    echo json_encode([
                        'codigo' => 2,
                        'msg' => 'Data não informada'
                    ]);
                    return;
                }
                $this->load->model('M_relatorio'); // carrrega a model

                $dados = $this->M_relatorio->buscarReservasPorData($resultado->dataMapa);

                if (!empty($dados) && $dados!= 0){
                    echo json_encode([
                        'codigo' => 1,
                        'msg' => 'Relatorio gerado com sucesso!',
                        'dados' => $dados // enviar os dados para view
                    ]);
                }else{
                    echo json_encode([
                        'codigo' => 3,
                        'msg' => 'Nenhuma reserva encontrada para a data informada'
                    ]);
                }
            } catch(Exception $e){
                    echo json_encode([
                        'codigo' => 0,
                        'msg' => 'Erro ao gerar realtório' . $e->getMessage()
                    ]);
            }
        }
    }
