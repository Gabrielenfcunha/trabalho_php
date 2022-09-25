<?php require_once 'functions.php'; session_start();?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Página do Admin - Inicial</title>
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
		<?php if (isset( $_GET['id'] )) { ?>			
			<h2>
			Tem certeza que quer deletar a página <?php echo $_GET['titulo']; ?>	
			</h2>

			<?php 
			if(isset( $_POST['deletar']) AND !empty($_POST['id'])){
				deletar($conectar, "paginas", $_POST['id'], "paginas.php");
			}
			?>
			<form method="post" action="">
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
				<input type="submit" name="deletar" value="Deletar">
			</form>

		<?php }else{
			echo "Página não encontrado!";
		} ?>
	</div>

<?php } else { 
	//header('location:index.php');
	echo "<script>window.location.href = 'index.php';</script>";
} ?>
</body>
</html>