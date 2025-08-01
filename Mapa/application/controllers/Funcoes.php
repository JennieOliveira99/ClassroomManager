<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Funcoes extends CI_Controller{

    public function index(){
        $this->load->view('login');
    }

    public function indexPagina(){
        $this->load->view('index');
    }
    
    public function abreSala(){
        $this->load->view('sala');
    }

        public function abreProfessor(){
        $this->load->view('professor');
    }

        public function abreTurma(){
        $this->load->view('turma');
    }

        public function abrePeriodo(){
        $this->load->view('periodo');
    }
         public function abreMapa(){
        $this->load->view('mapa');
    }

        public function abreRelatorio(){
        $this->load->view('relatorio');
    }
    public function encerraSistema(){
        // redireciona o usuario para a pagina de login
        header('Location: ' . base_url());
    }
  
}

