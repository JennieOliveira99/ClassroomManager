<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_turma extends CI_Model{
    public function inserir($descricao, $capacidade, $dataInicio)    {
        try {

            //Query de inserção dos dados
            $this->db->query("insert into tbl_turma (descricao, capacidade, dataInicio)
            values ('$descricao', $capacidade, '$dataInicio')");
            
            //Verificar se a inserção ocorreu com sucesso
            if ($this->db->affected_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Turma cadastrada corretamente.'
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Houve algum problema na inserção na tabela de turma.'
                );
            }
                
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                $e->getMessage(),
                "\n"
            );
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    public function consultar($codigo, $descricao, $capacidade, $dataInicio)
    {
        try {
            //Query para consultar dados de acordo com parâmetros passados
            $sql = "select codigo, descricao, capacidade, dataInicio,
            date_format(dataInicio,'%d-%m-%Y') dataIniciobra
            from tbl_turma where estatus = '' ";

            if (trim($codigo) != '') {
                $sql = $sql . "and codigo = $codigo ";
            }

            if (trim($descricao) != '') {
                $sql = $sql . "and descricao like '$descricao' ";
            }

            if (trim($capacidade) != '') {
                $sql = $sql . "and capacidade = $capacidade ";
            }

            if (trim($dataInicio != '')) {
                $sql = $sql . "and dataInicio = '$dataInicio' ";
            }

            $retorno = $this->db->query($sql);

            //Verificar se a consulta ocorreu com sucesso
            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso',
                    'dados' => $retorno->result()
                );

            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Turma não encontrado.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ', 
                $e->getMessage(),
                "\n"
            );
        }

        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }
    public function consultaTurmaCod($codigo)
      {
        try {
            //Query para consultar dados de acordo com parâmetros passados
            $sql = "select * from tbl_turma where codigo = $codigo and estatus = ''";
            
            $retornoSala = $this->db->query($sql);

            //Verificar se a consulta ocorreu com sucesso
            if ($retornoSala->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.'
                );
            
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Turma não encontrado.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: 0 seguinte erro aconteceu -> ',
                $e->getMessage(),
                "\n"
            );
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }
    
    public function alterar($codigo, $descricao, $capacidade, $dataInicio)
    {
        try {
            // Verifica se a turma já está cadastrada
            $retornoConsulta = $this->consultaTurmaCod($codigo);

            if (is_array($retornoConsulta) && isset($retornoConsulta['codigo'])
            && $retornoConsulta['codigo'] == 1) {
            // Monta a query dinâmica
            $query = "UPDATE tbl_turma SET ";
            $updates = [];

                if ($descricao !== '') {
                    $updates[] = "descricao = '$descricao'";
                }
                if ($capacidade !== '') {
                    $updates[] = "capacidade = $capacidade";
                }
                if ($dataInicio !== '') {
                    $updates[] = "dataInicio = '$dataInicio'";
                }

                $query .= implode(", ", $updates) . " WHERE codigo = $codigo ";

                // Prepara os valores para binding
                $params = [];
                if ($descricao !== '') {
                    $params[] = $descricao;
                }
                if ($capacidade !== '') {
                    $params[] = $capacidade;
                }
                if ($dataInicio !== '') {
                    $params[] = $dataInicio;
                }
                $params[] = $codigo;

                // Executa a query
                $this->db->query($query, $params);

                // Verifica se a atualização foi bem-sucedida
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Turma atualizada correctamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 6,
                        'msg' => 'Houve algum problema na atualização na tabela de turma.'
                    );
                }                    
            } else {
                $dados = array(
                'codigo' => 5,
                'msg' => 'Turma não cadastrada no sistema.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> '. $e->getMessage()
            );
        }
        return $dados;
    }

    public function desativar($codigo)
    {

        try {
        // Verifica se a turma já está cadastrada
        $retornoConsulta = $this->consultaTurmaCod($codigo);

            if (is_array($retornoConsulta) && isset($retornoConsulta['codigo'])
                && $retornoConsulta['codigo'] == 1) {
                //Query de atualização dos dados
                $this->db->query("update tbl_turma set estatus = 'D'
                                    where codigo = $codigo");
                
                //Verificar se a atualização ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Turma DESATIVADA corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 5,
                        'msg' => 'Houve algum problema na DESATIVAÇÃO da turma.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 5,
                    'msg' => 'Turma não cadastrada no sistema.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ', 
                $e->getMessage(),
                "\n"
            );
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão ir
        return $dados;
    }
        public function listarTodas(){
        try{
             //Query para consultar dados de acordo com parâmetros
            $sql = "select * from tbl_turma where estatus = ''
                    order by codigo";
    
            $retorno = $this->db->query($sql);

            // verificar se a consulta aocorreu com sucesso
            if ($retorno->num_rows() > 0){
              $dados = array(
                  'codigo' => 1,
                  'msg' => 'consulta efetuada com sucesso.',
                  'dados'=> $retorno->result()
              );
            }else{
              $dados = array(
                  'codigo' => 6,
                  'msg' => 'Turma não encontrada'
              );
            }
        } catch (Exception $e){
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        return $dados; // retorna um array de objetos
    }
} 