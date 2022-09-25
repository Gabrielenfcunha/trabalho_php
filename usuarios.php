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
	<h1> Bem Vindo(a) <?php echo $_SESSION['nome'] ?> ao painel administrativo!</h1>
	<h2>Gerenciador de Usuários</h2>
	<nav>
		<ul>
			<li><a href="admin.php">Início</a></li>
			<li><a href="usuarios.php">Gerenciar Usuários</a></li>
			<li><a href="paginas.php">Gerenciar Páginas</a></li>
			<li><a href="deslogar.php">Sair</a></li>
		</ul>
	</nav>
	<div class="conteudo">
	<div>
	<a href="edit_img.php" >Inserir Novo Usuário</a>	
	</div>
		<table border="1">
			<thead>
				<tr>
					<th>Imagem</th>
					<th>Nome</th>
					<th>E-mail</th>
					<th>Data</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				//$usuarios = selecionar($conectar, "usuarios");
				$where = "1";
				$order = "nome DESC";
				$usuarios = selecionar($conectar, "usuarios", $where, $order);
				foreach ($usuarios as $usuario) : ?>
					<tr>
						<td>
							<?php if($usuario['imagem']){ ?>
								<img width="100px" height="100px" src="imagens/<?php echo $usuario['imagem'] ?>" alt="<?php echo $usuario['nome'] ?>" >
							<?php } ?>
						</td>
						<td><?php echo $usuario['nome'] ?></td>
						<td><?php echo $usuario['email'] ?></td>
						<td><?php echo $usuario['data_cadastro'] ?></td>
						<td>
							<a href="edit_img.php?id=<?php echo $usuario['id']; ?>&nome=<?php echo $usuario['nome']; ?> " >
								Editar
							</a> - 
							<a href="delete_img.php?id=<?php echo $usuario['id']; ?>&nome=<?php echo $usuario['nome']; ?> " >
								Deletar
							</a>
						</td>
					</tr>					
				<?php endforeach ?>
				
			</tbody>
		</table>



	</div>
<?php } else { 
	//header('location:index.php');
	echo "<script>window.location.href = 'index.php';</script>";
} ?>
</body>
</html>