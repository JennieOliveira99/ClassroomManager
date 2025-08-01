<?php
defined("BASEPATH") or exit('No direct script access allowed');

class M_horario extends CI_Model {
    public function inserir($descricao, $horaInicial, $horaFinal){
        try{
            //Verifico se o horário já está cadastrado
            $retornoConsulta = $this->consultaHorario($descricao, $horaInicial, $horaFinal);

            if($retornoConsulta['codigo'] != 1){
                //Query de inserção dos dados
                $this->db->query("insert into tbl_horario (descricao, hora_ini, hora_fim)
                                    values ('$descricao', '$horaInicial', '$horaFinal')");

                //Verificar se a inserção ocorreu com sucesso
                if($this->db->affected_rows() > 0){
                    $dados = array('codigo' => 1,
                                'msg' => 'Horário cadastrado corretamente.');
                } else{
                    $dados = array('codigo' => 6,
                    'msg' => 'Howve algum problema na inserção na tabela de horário.');
                }
                
            } else{
                $dados = array('codigo' => 5,
                                'msg' => 'Horário já cadastrado no sistema.');
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                      $e->getMessage(), "\n");
        }
        //Envia o array $dados com as informações tratadas
        //Acima pela estrutura de decisão ir
        return $dados;
    }

    private function consultaHorario($descricao, $horaInicial, $horaFinal){
        try{
            //Query para consultar dados de acordo com parâmetros passados
            $sql = "select * from tbl_horario
                where describeo = '$descricao'
                and hora_ini = '$horaInicial'
                and hora_fim = '$horaFinal'
                and estatus = ''";    
            $retornoiHorario = $this->db->query($sql);
            
            //Verificar se a consulta ocorreu com sucesso
            if($retornoiHorario->num_rows() > 0){
                $dados = array('codigo' => 1,
                                'msg' => 'Consulta efetuada com sucesso.');
            
            }else{
                $dados = array('codigo' => 4,
                'msg' => 'Horário não encontrado.');
            }
        }catch (Exception $e) {
            $dados = array('codigo' => 00,
            'msg' => 'ATENÇÃO: 0 seguinte erro aconteceu -> ',
            $e->getMessage(), "\n");
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }
                
    public function consultaHorarioCod($codigo){
        try{
            //Query para consultar dados de acordo com parâmetros passados
            $sql = "select * from tbl_horario where codigo = $codigo and estatus = ''";

            $retornoiHorario = $this->db->query($sql);
            
            //Verificar se a consulta ocorreu com sucesso
            if($retornoiHorario->num_rows() > 0){
                $dados = array('codigo' => 1,
                                'msg' => 'Consulta efetuada com sucesso.');
            }else{
            $dados = array('codigo' => 6,
                            'msg' => 'Horário não encontrado.');
            }
            }catch (Exception $e) {
                $dados = array('codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                $e->getMessage(), "\n");
            }
            //Envía o array $dados com as informações tratadas
            //acima pela estrutura de decisão if
            return $dados;
        }
    
    
    public function consultar($codigo, $descricao, $horaInicial, $horaFinal){
        try{
            //Query para consultar dados de acordo com parâmetros passados
            $sql = "select * from tbl_horario where estatus != 'D' ";

            if(trim($codigo) != '') {
            $sql = $sql . "and codigo = $codigo ";
            }
            
            if (trim($horaInicial) != '') {
            $sql = $sql . " and andar = '$horaInicial' ";
            }

            if (trim($descricao) != '') {
            $sql = $sql . " and describeo like '%$descricao%' ";
            }
            
            if (trim($horaFinal) != '') {
            $sql = $sql . " and andar = '$horaFinal' ";
            }
    
    
            $retorno = $this->db->query($sql);
    
            //Verificar se a consulta ocorreu com sucesso
            if($retorno->num_rows() > 0){
                $linha = $retorno->row();

                if(trim($linha->estatus) == "D"){
                    $dados = array('codigo' => 7,
                                    'msg' => 'Horário desativado no sistema.');
                }else{
                $dados = array('codigo' => 1,
                                'msg' => 'Consulta efetuada com sucesso.',
                                'dados' => $retorno->result());
                }
            }else{
            $dados = array('codigo' => 6,
            'msg' => 'Horário não encontrado.');
            }
        }catch (Exception $e) {
                $dados = array('codigo' => 00,
                                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                        $e->getMessage(), "\n");
        }     
    //Envia o array $dados com as informações tratadas
    //acima pela estrutura de decisão ir
    return $dados;
    }
    
    public function alterar($codigo, $descricao, $horaInicial, $horaFinal){
        try{
        //Verifico se a sala ja esta cadastrada
            $retornoConsulta = $this->consultaHorarioCod($codigo);

            if($retornoConsulta['codigo'] == 1){
                //Inicio a query para atualizacao
                $query = "update tbl_horario set ";
                //Vamos comparar os items
                if($descricao !== ''){
                    $query .= "descricao = '$descricao', ";
                }

                if($horaInicial !== ''){
                    $query .= "hora_ini = '$horaInicial', ";
                }

                if($horaFinal !== ''){
                    $query .= "hora_fim = '$horaFinal', ";
                }

                //Termino a concatencao da query
                $queryFinal = rtrim($query, ", ") . " where codigo = $codigo";

                //Executo a Query de atualizacao dos dados
                $this->db->query($queryFinal);

                //Verificar se a atualizacao ocorreu com sucesso
                if($this->db->affected_rows() > 0){
                    $dados = array('codigo' => 1,
                                    'msg' => 'Horario atualizado corretamente.');

                }else{
                    $dados = array('codigo' => 5,
                                    'msg' => 'Houve algum problema na atualizacao
                                            na tabela de horario.');
                }
            }else{
                $dados = array('codigo' => 4,
                'msg' => 'Horario nao cadastrado no sistema.');
            }

        }catch (Exception $e) {
            $dados = array('codigo' => 00,
            'msg' => 'AIENCAO: O seguinte erro aconteceu -> ',
            $e->getMessage(), "\n");
        }

        //Envia o array $dados com as informacoes tratadas
        //acima pela estrutura de decisao ir
        return $dados;
    }

    public function desativar($codigo){
        try{
        //Verifico se o horário já está cadastrado
            $retormConsulta = $this->consultahorarioCod($codigo);

            if($retormConsulta['codigo'] == 1){

                //Query de atualização dos dados
                $this->db->query("update tbl_horario set estatus = 'D'
                                    where codigo = $codigo");
                
                //Verificar se a atualização ocorreu com sucesso
                if($this->db->affected_rows() > 0){
                    $dados = array('codigo' => 1,
                                   'msg' => 'Horário DESATIVADO corretamente.');
                
                }else{
                    $dados = array('codigo' => 4,
                                    'msg' => 'Houve algum problema na DESATIVAÇÃO do Horário.');
                }
            }else{
            $dados = array('codigo' => 3,
                            'msg' => 'Horário não cadastrado no Sistema, não pode excluir.');
            }
        }catch (Exception $e) {
            $dados = array('codigo' => 00,
                            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                    $e->getMessage(), "\n");
        
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão ir
        return $dados;    
    }
    public function listarTodos(){
        try{
             //Query para consultar dados de acordo com parâmetros
            $sql = "select * from tbl_horario where estatus = ''
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
                  'msg' => 'Horário não encontrado'
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
                            
                        