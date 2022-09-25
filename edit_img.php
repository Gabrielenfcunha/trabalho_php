<?php require_once 'functions.php'; session_start();?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Página do Admin - Inicial</title>
	<style type="text/css">
		label{display:block;}
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
			if (isset($_POST['cadastrar'])) {
				inserirUsuario($conectar);
			}	

			$nome = "";
			$email = "";
			$senha = "";
			$data = date("Y-m-d");
			$id = "";
			$cadastrar = "cadastrar";
	
			if (isset($_GET['id'])) {
				echo "<h2>Editar Página: ". $_GET['nome']. "</h2>";
				
				$where = "id = ". $_GET['id'];
				$usuario = selecionar($conectar, 'usuarios', $where);
				
				$id = $usuario[0]['id'];
				$nome = $usuario[0]['nome'];
				$imagem = $usuario[0]['imagem'];
				$email = $usuario[0]['email'];
				$senha = $usuario[0]['senha'];
				$cadastrar = "salva";
				$usuario[0]['data_cadastro'];
				
				if (isset($_POST['salva'])) {
					updateimg($conectar);
				}
			}
	
	 ?>
			<form method="post" action="" enctype="multipart/form-data">
				<label style="display: inline;">Apagar imagem?</label>
				<input type="checkbox" name="delete-imagem" value="deletar">
				
				<?php
				if (!empty($imagem)) { ?>
					<div>
						<img width="100px" height="100px" src="imagens/<?php echo $imagem; ?>" alt="Imagem da página">
					</div>
				<?php } ?>

				
				<input type="hidden" name="id" value="<?php echo $id;?>">

				<div>
					<input type="file" name="imagem">
				</div>
				<div>
					<label>Nome: </label>
				<input required  value="<?php echo $nome; ?>"  type="text" name="nome">
				</div>
				<div>
				<label>E-mail: </label>
				<input required value="<?php echo $email; ?>" type="email" name="email" >
				</div>
				<div>
				<label>Senha: </label>
				<input required type="password" name="senha" placeholder="Senha">
				</div>
				<div>
				<label>Repita sua senha: </label>
				<input required type="password" name="repetesenha" placeholder="Repita sua senha">
				</div>
				<div>
					<label>Data Cadastro:</label>
					<input value="<?php echo $data;?>" type="date" name="data_cadastro" required>
				</div>
				<input type="submit" name="<?php echo $cadastrar;?>" value="<?php echo $cadastrar;?>">
			</form>
	</div>
<?php } else { 
	//header('location:index.php');
	echo "<script>window.location.href = 'index.php';</script>";
} ?>
</body>
</html>