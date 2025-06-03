<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home - Sistema Mapa de Sala</title>
	<link rel="icon" href="<?= base_url('assets/img/icone_fatecSR.ico') ?>" type="image/x-icon">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
		integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?= base_url('assets/css/reset.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css.map') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/styleCadastro.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/stylePassword.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/styleLogin.css') ?>">
	<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">


</head>

<body>
	<a id="linkHome" href="#">
		<header>
			<h1 id="headerTitle">Mapeamento de Salas</h1>
		</header>
	</a>
	<main>
		<!-- Seção com cards -->
		<section class="secao4" id="sobre">
			<div class="secao4-div">
				<!-- Card1 -->
				<a href="../funcoes/abreSala" class="secao4-div-card card">
					<img decoding="async" src="<?= base_url('assets/img/sala-de-aula.png') ?>" alt="imagem1">
					<h3> Sala de Aula</h3>
					<p>Clique para Cadastrar, Editar, consultar ou excluir uma sala de aula</p>
				</a>

				<!-- Card2 -->
				<a href="../funcoes/abreProfessor" class="secao4-div-card card">
					<img decoding="async" src="<?= base_url('assets/img/professores.png') ?>" alt="imagem1">
					<h3> Professores</h3>
					<p>Clique para Cadastrar, Editar, consultar ou excluir uma sala de aula</p>
				</a>

				<!-- Card3 -->
				<a href="../funcoes/abreTurma" class="secao4-div-card card">
					<img decoding="async" src="<?= base_url('assets/img/turma.png') ?>" alt="imagem1">
					<h3> Turma</h3>
					<p>Clique para Cadastrar, Editar, consultar ou excluir uma sala de aula</p>
				</a>

				<!-- Card4 -->
				<a href="../funcoes/abrePeriodo" class="secao4-div-card card">
					<img decoding="async" src="<?= base_url('assets/img/periodo.png') ?>" alt="imagem1">
					<h3> Período</h3>
					<p>Clique para Cadastrar, Editar, consultar ou excluir uma sala de aula</p>
				</a>

				<!-- Card5 -->
				<a href="../funcoes/abreMapa" class="secao4-div-card card">
					<img decoding="async" src="<?= base_url('assets/img/mapeamento.png') ?>" alt="imagem1">
					<h3> Reservas</h3>
					<p>Clique para Cadastrar, Editar, consultar ou excluir uma sala de aula</p>
				</a>

				<!-- Card6 -->
				<a href="../funcoes/abreRelatório" class="secao4-div-card card">
					<img decoding="async" src="<?= base_url('assets/img/relatorio.png') ?>" alt="imagem1">
					<h3> Relatório</h3>
					<p>Clique para Cadastrar, Editar, consultar ou excluir uma sala de aula</p>
				</a>

				<!-- Card7 -->
				<a href="../funcoes/encerraSistema" class="secao4-div-card card">
					<img decoding="async" src="<?= base_url('assets/img/sair.png') ?>" alt="imagem1">
					<h3> Encerrar</h3>
					<p>Clique para Cadastrar, Editar, consultar ou excluir uma sala de aula</p>
				</a>
			</div>
		</section>
	</main>
</body>

</html>