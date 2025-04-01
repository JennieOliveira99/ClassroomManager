<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Turma extends CI_Model
{
    public function inserir($descricao, $capacidade,$dataInicio)
    {
        try {
            // Query de inserção de dados com bindings
          //  $sql = "INSERT INTO tbl_turma (codigo, descricao, dataInicio) ";
          
          $this->db->query("insert into tbl_turma ( descricao, capacidade, dataInicio)
            values ('$descricao', '$capacidade','$dataInicio')");

    
            // Verificar se a inserção ocorreu com sucesso
            if ($this->db->affected_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Turma cadastrada corretamente.'
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Houve algum problema na inserção na tabela de turmas.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
    
        // Envia o array $dados com as informações tratadas acima pela estrutura de decisão if
        return $dados;
    }

    public function consultar($codigo, $descricao, $dataInicio)
    {
        try {
            //query para consultar dados de acordo com parâmetros passados
            $sql = "select codigo, descricao, capacidade, dataInicio, 
            date_format(dataInicio, '%d/%m/%Y') as dataIniciobra from tbl_turma 
            where estatus = ''";

            //verificar se a consulta ocorreu com sucesso
            if (trim($codigo) != '') {
                $sql = $sql . " and codigo = $codigo";
            }
            if (trim($descricao) != '') {
                $sql = $sql . " and descricao like '%$descricao%'";
            }
            if (trim($dataInicio) != '') {
                $sql = $sql . " and dataInicio = '$dataInicio'";
            }
            if (trim($dataInicio) != '') {
                $sql = $sql . " and dataInicio = '$dataInicio'";
            }
            $retorno = $this->db->query($sql);
            //verificar se a consulta ocorreu com sucesso
            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.',
                    'turmas' => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Turma não encontrada.'
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

        return $dados;
    }

    public function consultaTurmaCod($codigo)
    {
        try {
            //query para consultar dados de acordo com parâmetros passados  
            $sql = "select codigo from tbl_turma where codigo = $codigo and estatus = ''";

            $retornoSala = $this->db->query($sql);

            //verificar se a consulta ocorreu com sucesso
            if ($retornoSala->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso'
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Turma não encontrada'
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
        // Envia o array $dados com as informações tratadas acima pela estrutura de decisão if
        return $dados;
    }
    public function alterar($codigo, $descricao, $capacidade, $dataInicio)
    {
        try {

            //verifica se turma ja esta cadastrada
            $retornoConsulta = $this->consultaTurmaCod($codigo);
            if (is_array($retornoConsulta) && isset($retornoConsulta['codigo']) 
            && $retornoConsulta['codigo'] == 1) {

                //monta a query dinamica

                $query = "UPDATE tbl_turma SET ";
                $updates = [];

                if ($descricao != '') {
                    $updates[] = "descricao = '$descricao'";
                }
                if ($capacidade != '') {
                    $updates[] = "capacidade = '$capacidade'";
                }
                if ($dataInicio != '') {
                    $updates[] = "dataInicio = '$dataInicio'";
                }
                $query .= implode(", ", $updates) . " WHERE codigo = $codigo";

                //prepara os valore para biding

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

                //executa a query
                $this->db->query($query, $params);

                //verifica se a atualização ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Turma atualizada corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 6,
                        'msg' => 'Houve algum problema na atualização na tabela de turmas.'
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
        // Envia o array $dados com as informações tratadas acima pela estrutura de decisão if
        return $dados;
    }
    public function desativar($codigo){
        try{
//verifica se a turma ja esta cadastrada
            $retornoConsulta = $this->consultaTurmaCod($codigo);

            if (is_array($retornoConsulta) && isset($retornoConsulta['codigo'])
             && $retornoConsulta['codigo'] == 1) {
                //query de atualização de dados
                $this->db->query("UPDATE tbl_turma SET estatus = 'D'
                 WHERE codigo = $codigo");

                //verifica se a atualização ocorreu com sucesso
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
                    'msg' => 'Turma não cadastrada no sistema, não pode desativar.'
                );
            }

        }
        catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                $e->getMessage(),
                "\n"
            );
        }
        // Envia o array $dados com as informações tratadas acima pela estrutura de decisão if  
        return $dados;
    }
}
