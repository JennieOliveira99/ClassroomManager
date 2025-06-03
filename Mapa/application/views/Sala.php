<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/icone_fatecSR.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="application/assets/css/reset.css">

    <link rel="stylesheet" href="/Mapa/assets/css/reset.css">
    <link rel="stylesheet" href="/Mapa/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Mapa/assets/css/styleCadastro.css">

    <title> Sala</title>

    <style>
        .navbar-nav {
            width: 100%;
            display: flex;
            justify-content: space-around;
        }

        .nav-item {
            flex-grow: 1;
            text-align: center;
        }

        .nav-link {
            display: block;
            color: rgb(162, 205, 55) !important;
            font-weight: bold;
            padding: 0px;
        }

        .nav-link:hover {
            background-color: rgb(156, 83, 138);
        }
    </style>
</head>

<body>
    <header>
        <div id="headerMenu">
            <a href="../Funcoes/indexPagina">
                <h1 id="headerTitle">Mapeamento de Salas</h1>
            </a>
            <nav class="navbar navbar-expand-lg navbar-dark">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle naviation">
                    <span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="/Mapa/Funcoes/abreSala.php">Sala de Aula</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Mapa/Funcoes/abreProfessor.php">Professores</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Mapa/Funcoes/abreTurma.php">Turma</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Mapa/Funcoes/abrePeriodo.php">Período</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Mapa/Funcoes/abreMapa.php">Reservas</a></li>
                        <li class="nav-item"><a class="nav-link" href="/Mapa/Funcoes/abreRelatorio.php">Relatório</a></li>

                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <section class="secao4" id="cadastroSala">
            <div id="btnCadastroModal" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <input type="text" id="inputPesquisa" class="form-control" placeholder="Pesquisar" style="width: 300px;">
                <button class="btn btn-outline-primary btnAcao modalBtn" data-toggle="modal"
                    data-target="#cadastroSalaModal">Cadastrar Nova Sala</button>
            </div>
        </section>
        <section id="mostrarCadastro">
            <div class="table-responsive tabela-scroll">
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Andar</th>
                            <th scope="col">Capacidade</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaSala">
                        <!-- Conteúdo será preenchido via JS -->
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- MODAIS MOVIDOS PARA FORA DO MAIN -->
    <!-- Modal de Cadastro -->
    <div class="modal fade" id="cadastroSalaModal" tabindex="-1" role="dialog"
        aria-labelledby="cadastroSalaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroSalaModalLabel">Cadastar Nova sala</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCadastroSala" method="post" class="modal-content">
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="codigo" class="col-form-label">Número</label>
                                <input type="number" id="codigo" name="codigo" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="andar" class="col-form-label">Andar</label>
                                <select name="andar" id="andar" class="form-control" required>
                                    <option value='selecione'>Selecione</option>
                                    <option value="9">Térreo</option>
                                    <option value="1">Primeiro</option>
                                    <option value="2">Segundo</option>
                                    <option value="3">Terceiro</option>
                                    <option value="4">Quarto</option>
                                    <option value="5">Quinto</option>
                                    <option value="6">Sexto</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="capacidade" class="col-form-label">Capacidade</label>
                                <input type="number" id="capacidade" name="capacidade" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" id="descricao" name="descricao" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnAcao" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btnAcao" onclick="cadastro();">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content" role="document">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Sala</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditSala" onsubmit="editarSala(event)">
                    <input type="hidden" id="editId" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editDescricao">Descrição</label>
                            <input type="text" id="editDescricao" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="editAndar">Andar</label>
                            <select id="editAndar" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="9">Térreo</option>
                                <option value="1">Primeiro</option>
                                <option value="2">Segundo</option>
                                <option value="3">Terceiro</option>
                                <option value="4">Quarto</option>
                                <option value="5">Quinto</option>
                                <option value="6">Sexto</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editCapacidade">Capacidade</label>
                            <input type="number" id="editCapacidade" class="form-control" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer></footer>
    <script src="../assets/js/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../assets/js/sweetalert2.all.min.js" type="text/javascript"></script>

    <script>
        async function cadastro() {
            event.preventDefault();
            const codigo = document.getElementById('codigo').value;
            const descricao = document.getElementById('descricao').value;
            const andar = document.getElementById('andar').value;
            const capacidade = document.getElementById('capacidade').value;

            try {
                const response = await fetch('/Mapa/Sala/inserir', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        codigo: codigo,
                        descricao: descricao,
                        andar: andar,
                        capacidade: capacidade
                    })
                });

                const result = await response.json();

                if (result.codigo == 1) {
                    //fechar o modal
                    $('#cadastroSalaModal').modal('hide');
                    Swal.fire('Sucesso!', result.msg, 'success');
                    carregarDados();
                    //  window.location.reload();
                } else {
                    Swal.fire('Erro', result.msg, 'error');
                }

            } catch (error) {
                console.error('Erro ao cadastrar sala:', error);
                Swal.fire('Erro', 'Erro ao cadastrar sala.', 'error');
            }
        }
        
        async function carregarDados() {
            try {
                const response = await fetch('/Mapa/Sala/consultar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        codigo: '',
                        descricao: '',
                        andar: '',
                        capacidade: ''
                    })
                });

                const text = await response.text();
                console.log('Resposta bruta:', text);

                const data = JSON.parse(text);
                console.log(data);

                const tabela = document.getElementById('tabelaSala');
                tabela.innerHTML = '';

                data.dados.forEach(item => {
                    // Determinar o nome do andar baseado no valor numérico
                    let andarNome = item.andar;
                    if (item.andar == '9') andarNome = 'Térreo';
                    else if (item.andar == '1') andarNome = 'Primeiro';
                    else if (item.andar == '2') andarNome = 'Segundo';
                    else if (item.andar == '3') andarNome = 'Terceiro';
                    else if (item.andar == '4') andarNome = 'Quarto';
                    else if (item.andar == '5') andarNome = 'Quinto';
                    else if (item.andar == '6') andarNome = 'Sexto';
                    
                    tabela.innerHTML += `
                        <tr class="alert alert-warning">
                            <td>${item.codigo}</td>
                            <td>${andarNome}</td>  
                            <td>${item.capacidade}</td>
                            <td>${item.descricao}</td>
                            <td>
                                <div class="row">
                                    <button class="btn btn-warning btnAcao" onclick="abrirModalEditar(this)">
                                        <i class="fas fa-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btnAcaoExcluir" onclick="deletarSala(${item.codigo})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });

            } catch (error) {
                console.error('Erro ao carregar dados:', error);
            }
        }

        $(document).ready(function() {
            carregarDados();

            $('#cadastroSalaModal').on('show.bs.modal', function() {
                $('#formCadastroSala')[0].reset();
            });
            
            // Adicionar evento de pesquisa ao input
            $("#inputPesquisa").on("keyup", function() {
                filtrarTabela();
            });
        });

        function abrirModalEditar(button) {
            //linha do botao clicado
            const row = button.closest('tr');

            //pegar dados da linha
            const codigo = row.cells[0].innerText; // código da sala
            const andar = row.cells[1].innerText; // andar (nome)
            const capacidade = row.cells[2].innerText; // capacidade
            const descricao = row.cells[3].innerText; // descrição
            
            // Converter nome do andar para valor numérico
            let andarValor = "";
            if (andar === "Térreo") andarValor = "9";
            else if (andar === "Primeiro") andarValor = "1";
            else if (andar === "Segundo") andarValor = "2";
            else if (andar === "Terceiro") andarValor = "3";
            else if (andar === "Quarto") andarValor = "4";
            else if (andar === "Quinto") andarValor = "5";
            else if (andar === "Sexto") andarValor = "6";
            else andarValor = andar; // caso já seja numérico

            // Preenche o modal com dados da sala
            document.getElementById('editId').value = codigo;
            document.getElementById('editDescricao').value = descricao;
            document.getElementById('editAndar').value = andarValor;
            document.getElementById('editCapacidade').value = capacidade;

            // Abre modal
            $('#editModal').modal('show');
        }

        async function editarSala(event) {
            event.preventDefault(); // Isso garante que o formulário não seja enviado pelo navegador

            try { 
                const id = document.getElementById('editId').value;
                const descricao = document.getElementById('editDescricao').value;
                const andar = document.getElementById('editAndar').value;
                const capacidade = document.getElementById('editCapacidade').value;
                
                const response = await fetch('/Mapa/Sala/alterar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        codigo: id,
                        descricao: descricao,
                        andar: andar,
                        capacidade: capacidade
                    })
                });
                
                const result = await response.json();

                if (result.codigo == 1) {
                    //fechar o modal
                    $('#editModal').modal('hide');
                    Swal.fire('Sucesso!', result.msg, 'success');
                    carregarDados();
                } else {
                    Swal.fire('Erro', result.msg, 'error');
                }
            } catch (erro) {
                console.error(erro);
                Swal.fire('Erro!', 'Erro na requisição.', 'error');
            }
        }

        function deletarSala(codigo) {
            Swal.fire({
                title: 'Atenção!',
                text: 'Tem certeza que deseja excluir sala?',
                icon: 'question',
                showConfirmButton: true,
                showCancelButton: true,
                customClass: {
                    popup: 'my-swal-popup',
                    title: 'my-swal-title',
                    html: 'my-swal-text',
                    confirmButton: 'btn btn-danger btnAcao my-swal-button',
                    cancelButton: 'btn btn-secondary btnAcao my-swal-button',
                },
                buttonStyling: false
            }).then(async function(res) {
                if (res.isConfirmed) {
                    try {
                        const request = await fetch('/Mapa/Sala/desativar', {
                            method: 'post',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                codigo: codigo
                            })
                        });
                        
                        const response = await request.json();

                        Swal.fire({
                            title: 'Atenção!',
                            text: response.msg,
                            icon: response.codigo == 1 ? 'success' : 'error',
                            customClass: {
                                popup: 'my-swal-popup',
                                title: 'my-swal-title',
                                html: 'my-swal-text',
                                confirmButton: 'btn btn-primary btnAcao',
                            },
                            buttonStyling: false
                        });
                        
                        carregarDados();
                    } catch (error) {
                        console.error('Erro:', error);
                        Swal.fire('Erro', 'Ocorreu um erro ao tentar excluir a sala.', 'error');
                    }
                }
            });
        }

        function filtrarTabela() {
            const input = document.getElementById("inputPesquisa");
            const filter = input.value.toLowerCase();
            const tabela = document.getElementById("tabelaSala");
            const linhas = tabela.getElementsByTagName("tr");
            
            for (let i = 0; i < linhas.length; i++) {
                const colunas = linhas[i].getElementsByTagName("td");
                let exibir = false;
                
                // Busca em todas as colunas
                for (let j = 0; j < colunas.length; j++) {
                    const texto = colunas[j].textContent || colunas[j].innerText;
                    if (texto.toLowerCase().indexOf(filter) > -1) {
                        exibir = true;
                        break;
                    }
                }
                
                linhas[i].style.display = exibir ? "" : "none";
            }
        }
    </script>
</body>

</html>