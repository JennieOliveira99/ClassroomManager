<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Incluir a classe que precisaremos instanciar
include_once("M_sala.php");
include_once("M_horario.php");
include_once("M_turma.php");
include_once("M_professor.php");

class M_mapa extends CI_Model
{
    public function inserir($dataReserva, $codSala, $codHorario, $codTurma, $codProfessor)
    {
        try {
            //Verifico se o professor já está cadastrado
            $retornoConsulta = $this->consultaReservaTotal($dataReserva, $codSala, $codHorario,
                                                           $codTurma, $codProfessor);
                                                         
            if ($retornoConsulta['codigo'] == 6 || $retornoConsulta['codigo'] == 7) {
                //Chamo o objeto sala para validação
                $salaObj = new M_sala();

                //Chamamos o metodo de verificação
                $retornoConsultaSala = $salaObj->consultar($codSala, '', '', '');


                if ($retornoConsultaSala['codigo'] == 1) {
                    //Chamo o objeto horario para validação
                    $horarioObj = new M_horario();

                    //Chamamos o metodo de verificação
                    $retornoConsultaHorario = $horarioObj->consultaHorarioCod($codHorario);

                    if ($retornoConsultaHorario['codigo'] == 1) {
                        //Chamo o objeto turma para validação
                        $turmaObj = new M_turma();

                        //Chamamos o metodo de verificação
                        $retornoConsultaTurma = $turmaObj->consultaTurmaCod($codTurma);

                        if ($retornoConsultaTurma['codigo'] == 1) {
                            //Chamo o objeto professor para validação
                            $professorObj = new M_professor();

                            //Chamamos o metodo de verificação
                            $retornoConsultaProfessor = $professorObj->consultaProfessorCod($codProfessor);

                            if ($retornoConsultaProfessor['codigo'] == 1) {
                                //Query de inserção dos dados
                                $this->db->query("insert into tbl_mapa (datareserva, sala, codigo_horario,
                                                    codigo_turma, codigo_professor)
                                                    values ('". $dataReserva."', $codSala, $codHorario,
                                                    $codTurma, $codProfessor)");
                            
                                //Verificar se a inserção ocorreu com sucesso
                                if ($this->db->affected_rows() > 0) {
                                    $dados = array(
                                        'codigo' => 1,
                                        'msg' => 'Agendamento cadastrado corretamente.'
                                    );
                                } else {
                                    $dados = array(
                                        'codigo' => 8,
                                        'msg' => 'Houve algum problema na inserção na tabela de
                                                agendamento.'
                                    );
                                }
                            } else {
                                $dados = array(
                                    'codigo' => $retornoConsultaProfessor['codigo'],
                                    'msg' => $retornoConsultaProfessor['msg']
                                );
                            }
                        } else {
                            $dados = array(
                                'codigo' => $retornoConsultaTurma['codigo'],
                                'msg' => $retornoConsultaTurma['msg']
                            );
                        }
                    } else {

                        $dados = array(
                            'codigo' => $retornoConsultaHorario['codigo'],
                            'msg' => $retornoConsultaHorario['msg']
                        );
                    }
                } else {

                    $dados = array(
                        'codigo' => $retornoConsultaSala['codigo'],
                        'msg' => $retornoConsultaSala['msg']
                    );
                }
            } else {

                $dados = array(
                    'codigo' => 7,
                    'msg' => 'Agendamento já cadastrado no sistema.'
                );
            }                            
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu - > ',
                $e->getMessage(),
                "\n"
            );
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    private function consultaReservaTotal($dataReserva, $codSala, $codHorario)
    {
        try {
            //Query para verificar a hora inicial e final daquele determinado horário
            $sql = "select * from tbl_horario
                    where codigo = $codHorario";

            $retornoHorario = $this->db->query($sql);

            if ($retornoHorario->num_rows() > 0) {
                $linhaHr = $retornoHorario->row();
                $horaInicial = $linhaHr->hora_ini;
                $horaFinal = $linhaHr->hora_fim;

                //Query para consultar dados de acordo com parâmetros passados
                $sql = "select * from tbl_mapa m, tbl_horario h
                        where m.datareserva = '" . $dataReserva . "'
                        and m.sala = $codSala
                        and m.codigo_horario = h.codigo
                        and (h.hora_fim <= '" . $horaInicial . "'
                        and h.hora_ini >= '" . $horaFinal . "')";
                $retornoMapa = $this->db->query($sql);

                //Verificar se a consulta ocorreu com sucesso
                if ($retornoMapa->num_rows() > 0) {
                    $linha = $retornoMapa->row();

                    if (trim($linha->estatus) == "D") {
                        $dados = array(
                            'codigo' => 7,
                            'msg' => 'Agendamento desativado no sistema.'
                        );
                    } else {
                        $dados = array(
                            'codigo' => 1,
                            'msg' => "A data de: " . $dataReserva . " está ocupada para esta sala"
                        );
                    }
                } else {
                    $dados = array(
                        'codigo' => 6,
                        'msg' => 'Reserva não encontrada.'
                    );
                }
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
        //pela estrutura de decisão if
        return $dados;
    }

    public function consultar($codigo, $dataReserva, $codSala, $codHorario, $codTurma, $codProfessor)
    {
        try {
            //Query para consultar dados de acordo com parâmetros passados
            $sql = "select m.codigo, date_format(m.datareserva, '%d-%m-%Y') datareservabra, datareserva,
                    m.sala, s.descricao descsala, m.codigo_horario,
                    h.descricao deschorario, m.codigo_turma, t.descricao descturma, m.codigo_professor,
                    p.nome nome_professor
                    from tbl_mapa m, tbl_professor p, tbl_horario h, tbl_turma t, tbl_sala s
                    where m.estatus = ''
                        and m.codigo_professor = p.codigo
                        and m.codigo_horario = h.codigo
                        and m.codigo_turma = t.codigo
                        and m.sala = s.codigo ";

            if (trim($codigo) != '') {
            $sql = $sql . "and m.codigo = $codigo ";
            }

            if (trim($dataReserva) != '') {
            $sql = $sql . "and m.datareserva = '" . $dataReserva . "' ";
            }

            if (trim($codSala) != '') {
            $sql = $sql . "and m.sala = $codSala ";
            }

            if (trim($codHorario) != '') {
            $sql = $sql . "and m.codigo_horario = $codHorario ";
            }

            if (trim($codTurma) != '') {
            $sql = $sql . "and m.codigo_turma = $codTurma ";
            }

            if (trim($codProfessor) != '') {
            $sql = $sql . "and m.codigo_professor = $codProfessor ";
            }

            $sql = $sql . " order by m.datareserva, h.hora_ini, m.codigo_horario, m.sala ";

            $retorno = $this->db->query($sql);

            //Verificar se a consulta ocorreu com sucesso
            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso.',
                    'dados' => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Agendamento não encontrado.'
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
        //pela estrutura de decisão if
        return $dados;
    }

    public function alterar($codigo, $dataReserva, $codSala, $codHorario, $codTurma, $codProfessor)
    {
        try {
            //Verifico se o professor já está cadastrado
            
            $retornoConsultaCodigo = $this->consultar(
                $codigo,
                "",
                "",
                "",
                "",
                ""
            );
            
            if ($retornoConsultaCodigo['codigo'] == 1) {
            //Inicio a query para atualização
            $query = "update tbl_mapa set ";
            
                if ($dataReserva != "") {
                    $query .= "datareserva = '$dataReserva', ";
                }
                
                if ($codSala != "") {
                    //Chamo o objeto sala para validação
                    $salaObj = new M_sala();
                
                    //chamar o método de verificação
                    $retornoConsultaSala = $salaObj->consultar($codSala, '', '', '');
                
                    if ($retornoConsultaSala['codigo'] == 1) {
                        $query .= "sala = $codSala, ";
                    } else {
                        $dados = array(
                            'codigo' => $retornoConsultaSala['codigo'],
                            'msg' => $retornoConsultaSala['msg']
                        );
                    }
                }
                
                if ($codHorario != "") {
                    //Chamo o objeto sala para validação
                    $horarioObj = new M_horario();
                
                    //chamar o método de verificação
                    $retornoConsultaHorario = $horarioObj->consultaHorarioCod($codHorario);
                
                    if ($retornoConsultaHorario['codigo'] == 1) {
                        $query .= "codigo_horario = $codHorario, ";
                    } else {
                        $dados = array(
                            'codigo' => $retornoConsultaHorario['codigo'],
                            'msg' => $retornoConsultaHorario['msg']
                        );
                    }
                }

                if ($codTurma != "") {
                    //Chamo o objeto sala para validação
                    $turmaObj = new M_turma();
                
                    //chamar o método de verificação
                    $retornoConsultaTurma = $turmaObj->consultaTurmaCod($codTurma);
                
                    if ($retornoConsultaTurma['codigo'] == 1) {
                        $query .= "codigo_turma = $codTurma, ";
                    } else {
                        $dados = array(
                            'codigo' => $retornoConsultaTurma['codigo'],
                            'msg' => $retornoConsultaTurma['msg']
                        );
                    }
                }
                
                if ($codProfessor != "") {
                    //Chamo o objeto sala para validação
                    $professorObj = new M_professor();
                
                    //chamar o método de verificação
                    $retornoConsultaProfessor = $professorObj->consultaProfessorCod($codProfessor);
                
                    if ($retornoConsultaProfessor['codigo'] == 1) {
                        $query .= "codigo_professor = $codProfessor, ";
                
                        //Termino a concatenação da query
                        $queryFinal = rtrim($query, ", ") . " where codigo = $codigo";
                
                        //Executo a Query de atualização dos dados
                        $this->db->query($queryFinal);
                
                        //Verificar se a atualização ocorreu com sucesso
                        if ($this->db->affected_rows() > 0) {
                            $dados = array(
                                'codigo' => 1,
                                'msg' => 'Agendamento alterado corretamente.'
                            );
                        } else {
                            $dados = array(
                                'codigo' => 8,
                                'msg' => 'Houve algum problema na alteração na tabela de agendamento.'
                            );
                        }
                    } else {
                        $dados = array(
                            'codigo' => $retornoConsultaProfessor['codigo'],
                            'msg' => $retornoConsultaProfessor['msg']
                        );
                    }
                }    
            } else {

                $dados = array(
                    'codigo' => 8,
                    'msg' => 'Agendamento não cadastrado no sistema.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' .
                    $e->getMessage() .
                    '\n'
            );
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    public function desativar($codigo)
    {
        try {
            //Verifico se o agendamento já está cadastrado
            $retornoConsulta = $this->consultar(
                $codigo,
                "",
                "",
                "",
                "",
                ""
            );

            if ($retornoConsulta['codigo'] == 1) {

             //query de atualização dos dados
                $this->db->query("delete from tbl_mapa
                                      where codigo = $codigo"); 

                //Verificar se a atualização ocorreu com sucesso
                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Agendamento DESATIVADO corretamente.'
                    );
                } else {
                    $dados = array(
                        'codigo' => 5,
                        'msg' => 'Houve algum problema na DESATIVAÇÃO do Agendamento.'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Agendamento não cadastrado no Sistema, não pode excluir.'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 00,
                'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ' .
                        $e->getMessage(),
                "\n"
            );
        }

        //  Envia o array $dados com as informações tratadas
       //acima pela estrutura de decisão if
       return $dados;
    }

    public function inserirNovo($codSala, $codHorario, $codTurma, $codProfessor, $dataInicio, $dataFim, $diaSemana)
    {
        try {
             $salaObj = new M_sala();
             $retornoConsultaSala = $salaObj->consultar($codSala, '', '','');
                                                                                                                  
            if ($retornoConsultaSala['codigo'] == 1) {
                $horarioObj = new M_horario();
                $retornoConsultaHorario = $horarioObj->consultaHorarioCod($codHorario);

                if ($retornoConsultaHorario['codigo'] == 1) {
                     $turmaObj = new M_turma();   //Chamamos o metodo de verificação
                    $retornoConsultaTurma = $turmaObj->consultaTurmaCod($codTurma);

                    if ($retornoConsultaTurma['codigo'] == 1) {                         //Chamo o objeto turma para validação
                        $professorObj = new M_professor();                         //Chamamos o metodo de verificação
                        $retornoConsultaProfessor = $professorObj->consultaProfessorCod($codProfessor);

                            if ($retornoConsultaProfessor['codigo'] == 1) {
                                $datasCorrespondentes = [];
                                $datasFora = [];

                                for($i = 0; $i < count($diaSemana); $i++){
                                    for ($dataAtual = strtotime($dataInicio); $dataAtual <= strtotime($dataFim); $dataAtual = strtotime("+1 day", $dataAtual)){
                                        $retornoConsulta = $this->consultaReservaTotal(date("Y-m-d", $dataAtual), $codSala, $codHorario);

                                        if ($retornoConsulta['codigo'] == 1 || $retornoConsulta['codigo'] == 7) {
                                            if (isset($retornoConsulta['status']) && $retornoConsulta['status'] == 'D'){
                                                if (date ("w", $dataAtual) == $diaSemana[$i]){
                                                    $datasCorrespondentes[] = date("Y-m-d", $dataAtual);
                                                }
                                            } else{
                                                $datasFora[] = date("d-m-Y", $dataAtual). ". ";
                                            }
                                        } else{
                                            if (date ("w", $dataAtual) == $diaSemana[$i]){
                                                $datasCorrespondentes[] = date("Y-m-d", $dataAtual);
                                            }
                                        }
                                    }
                                }
                                if (!empty($datasCorrespondentes)){
                                    foreach ($datasCorrespondentes as $data){
                                        $this->db->query("INSERT INTO tbl_mapa (dataReserva, sala, codigo_horario, codigo_turma, codigo_professor)
                                        VALUES ('" . $data . "', $codSala, $codHorario, $codTurma, $codProfessor)");
                                    }
                                
                                   if ($this->db->affected_rows() > 0){
                                      $dados = array(
                                          'codigo' => 1,
                                          'msg' => !empty($datasFora) ? 'Agendamento cadastrado, porém a(s) data(s) ' .
                                          substr(join($datasFora), 0, -2). ' já possuem agendamentos.' : 'Agendamento cadastrado corretamente.'
                                      );
                                    }
                                }else{
                                    $dados = array(
                                          'codigo' => empty($datasFora) ? 8 : 15,
                                          'msg' => empty($datasFora) ? 'Estas datas de agendamento já constamno sistema.' : 'A(s) datas(s) ' .
                                          substr(join($datasFora), 0, -2) . ' já possuem agendamentos e não podem ser incluídas.'    
                                    );
                                }
                            } else {
                                $dados = array('codigo' => $retornoConsultaProfessor['codigo'], 'msg' => $retornoConsultaProfessor['msg']);
                            }
                    } else {
                            $dados = array('codigo' => $retornoConsultaTurma['codigo'], 'msg' => $retornoConsultaTurma['msg']);
                    }
                } else {
                    $dados = array('codigo' => $retornoConsultaHorario['codigo'], 'msg' => $retornoConsultaHorario['msg']);
                }
            } else {
                    $dados = array('codigo' => $retornoConsultaSala['codigo'], 'msg' => $retornoConsultaSala['msg']);
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 0, 'msg' => 'ATENÇÃO: O seguinte erro aconteceu - > ' . $e->getMessage());
        }
        return $dados;
    }
}   