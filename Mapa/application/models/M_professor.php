<?php



defined ('BASEPATH') or exit ('No direct script access allowed');



class M_professor extends CI_Model{

    public function inserir($nome, $tipo, $cpf){

        try{

            $retornoConsulta = $this->consultaProfessorCpf($cpf);



            if($retornoConsulta['codigo'] != 1){

                $this->db->query("insert into tbl_professor (nome, tipo, cpf)

                                values ('$nome', '$tipo', '$cpf')");



                if($this->db->affected_rows() > 0){

                    $dados = array(

                        'codigo' => 1,

                        'msg' => 'Professor cadastrado corretamente.'

                    );

                }else{

                    $dados = array(

                        'codigo' => 6,

                        'msg' => 'Houve algum problema na inserção na tabela de Professor.'

                    );

                }

            }else{

                $dados = array(

                    'codigo' => 5,

                    'msg' => 'Professor já cadastrado no sistema.'

                );

            }

        }catch(Exception $e){

            $dados = array(

                'codigo' => 00,

                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

                $e->getMessage()

            );

        }



        return $dados;

    }



    private function consultaProfessorCpf($cpf){

        try{

            $sql = "select * from tbl_professor where cpf = '$cpf'";



            $retornoSala = $this->db->query($sql);



            if($retornoSala->num_rows() > 0){

                $dados = array(

                    'codigo' => 1,

                    'msg' => 'Consulta efetuada com sucesso'

                );

            }else{

                $dados = array(

                    'codigo' => 6,

                    'msg' => 'Professor não encontrado'

                );

            }



        }catch(Exception $e){

            $dados = array(

                'codigo' => 00,

                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

                $e->getMessage()

            );

        }



        return $dados;

    }



    public function consultaProfessorCod($codigo){

        try{

            $sql = "select * from tbl_professor where codigo = '$codigo' and estatus = ''";



            $retornoSala = $this->db->query($sql);

    

            if($retornoSala->num_rows() > 0){

                $dados = array(

                    'codigo' => 1,

                    'msg' => 'Consulta efetuada com sucesso'

                );

            }else{

                $dados = array(

                    'codigo' => 6,

                    'msg' => 'Professor não encontrado'

                );

            }

      

        }catch(Exception $e){

            $dados = array(

                'codigo' => 00,

                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

                $e->getMessage()

            );

        }

        return $dados;

  }



  public function consultar($codigo, $nome, $tipo, $cpf){

    try{

        $sql = "select * from tbl_professor where estatus = '' ";



        if(trim($codigo) != ''){

            $sql = $sql . "and codigo = $codigo";

        }



        if(trim($cpf) != ''){

            $sql = $sql . "and cpf = '$cpf' ";

        }



        if(trim($nome) != ''){

            $sql = $sql . "and nome like '%$nome%' ";

        }



        if(trim($tipo) != ''){

            $sql = $sql . "and tipo = '$tipo' ";

        }



        $sql = $sql . " order by nome ";



        $retorno = $this->db->query($sql);



        if($retorno->num_rows() > 0){

            $dados = array(

                'codigo' => 1,

                'msg' => 'Consulta efetuada com sucesso.',

                'dados' => $retorno->result()

            );

        }else{

            $dados = array(

                'codigo' => 6,

                'msg' => 'Professor não encontrado.'

            );

        }

    }catch(Exception $e){

        $dados = array(

            'codigo' => 00,

            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

            $e->getMessage()

        );

    }



    return $dados;

  }



  public function alterar($codigo, $nome, $cpf, $tipo){

    try{

        $retornoConsulta = $this->consultaProfessorCod($codigo);



        if($retornoConsulta['codigo'] == 1){

            $query = "update tbl_professor set ";



            if($nome !== ''){

                $query .= "nome = '$nome', ";

            }



            if($cpf !== ''){

                $query .= "cpf = '$cpf', ";

            }



            if($tipo !== ''){

                $query .= "tipo = '$tipo' ";

            }



            $queryFinal = rtrim($query, ", ") . " where codigo = $codigo";



            $this->db->query($queryFinal);



            if($this->db->affected_rows() > 0){

                $dados = array(

                    'codigo' => 1,

                    'msg'  => 'Professor atualizado corretamente'

                );

            }else{

                $dados = array(

                    'codigo' => 6,

                    'msg' => 'Houve algum problema na atualização na tabela de Professor.'

                );

            }

        }else{

            $dados = array(

                'codigo' => 5,

                'msg' => 'Professor não cadastrado no sistema.'

            );

        }

    }catch(Exception $e){

        $dados = array(

            'codigo' => 00,

            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

            $e->getMessage()

        );

    }



    return $dados;

  }



  public function desativar($codigo){

    try{

        $retornoConsulta = $this->consultaProfessorCod($codigo);



        if($retornoConsulta['codigo'] == 1){

            $this->db->query("update tbl_professor set estatus = 'D' 

                             where codigo = $codigo");

            

            if($this->db->affected_rows() > 0){

                $dados = array(

                    'codigo' => 1,

                    'msg' => 'Professor DESATIVADO corretamente.'

                );

            }else{

                $dados = array(

                    'codigo' => 5,

                    'msg' => 'Houve algum problema na desativação na tabela de Professor'

                );

            }

        }else{

            $dados = array(

                'codigo' => 6,

                'msg' => 'Professor não cadastrado no sistema'

            );

        }

    }catch(Exception $e){

        $dados = array(

            'codigo' => 00,

            'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',

            $e->getMessage()

        );

    }



    return $dados;

  }

      public function listarTodos(){
        try{
             //Query para consultar dados de acordo com parâmetros
            $sql = "select * from tbl_professor where estatus = ''
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
                  'msg' => 'Professor não encontrado'
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



?>