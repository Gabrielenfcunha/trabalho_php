<?php require_once 'functions.php'; session_start();?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Página do Admin - Inicial</title>
	<style type="text/css">
		label{display: block;}
		input:checked + div img{display: none;}
	</style>
</head>
<body>
<?php if ( isset($_SESSION['ativa']) ) { ?>
	<img width="100px" height="100px" src="imagens/<?php echo $_SESSION['imagem']  ?>" >
	<h1>Bem Vindo(a) <?php echo $_SESSION['nome'] ?> ao painel administrativo!</h1>
	<nav>
		<ul>
			<li><a href="admin.php">Início</a></li>
			<li><a href="usuarios.php">Gerenciar Usuários</a></li>
			<li><a href="paginas.php">Gerenciar Páginas</a></li>
			<li><a href="deslogar.php">Sair</a></li>
		</ul>
	</nav>
	<div class="conteudo">	
		<?php 
		

		if (isset($_POST['enviar'])) {
			insertPage($conectar);
		}

		$titulo = "";
		$descricao = "";
		$data = date("Y-m-d");
		$id = "";
		$enviar = "enviar";

		if (isset($_GET['id'])) {
			echo "<h2>Editar Página: ". $_GET['titulo']. "</h2>";
			
			$where = "id = ". $_GET['id'];
			$pagina = selecionar($conectar, 'paginas', $where);
			
			$id = $pagina[0]['id'];
			$titulo = $pagina[0]['titulo'];
			$arquivo = $pagina[0]['arquivo'];
			$descricao = $pagina[0]['descricao'];
			$data = $pagina[0]['data'];
			$enviar = "atualizar";
			
			
			if (isset($_POST['atualizar'])) {
				updatePage($conectar);
			}
		}
	
		?>
		
			<form method="post" action="" enctype="multipart/form-data">
				<label style="display: inline;">Apagar imagem?</label>
				<input type="checkbox" name="delete-arquivo" value="deletar">

				<?php
				if (!empty($arquivo)) { ?>
					<div>
						<img width="100px" height="100px" src="imagens/<?php echo $arquivo; ?>" alt="arquivo da página">
					</div>
				<?php } ?>


				<input type="hidden" name="id" value="<?php echo $id;?>">
				
				<div>
					<input type="file" name="arquivo">
				</div>
				
				<div>
					<label>Título:</label>
					<input value="<?php echo $titulo; ?>" type="text" name="titulo" required>
				</div>
				<div>
					<label>Descrição:</label>
					<textarea name="descricao"><?php echo $descricao; ?></textarea>
				</div>
				
				<div>
					<label>Data Cadastro:</label>
					<input value="<?php echo $data;?>" type="date" name="data" required>
				</div>

				<a><input type="submit" name="<?php echo $enviar;?>" value="<?php echo $enviar;?>"></a>
			</form>		
	</div>

<?php } else { 
	//header('location:index.php');
	echo "<script>window.location.href = 'index.php';</script>";
} ?>
</body>
</html>