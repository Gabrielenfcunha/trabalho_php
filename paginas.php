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
	<h2>Gerenciador de Páginas / Notícias</h2>
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
			<a href="form_page.php">Inserir Nova Página</a>
		</div>

		<table border="1">
			<thead>
				<tr>
					<th>Imagem</th>
					<th>Titulo</th>
					<th>descriçao</th>
					<th>Data</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php 				
				$where = "1";
				$order = "data DESC";
				$paginas = selecionar($conectar, "paginas", $where, $order);
				foreach ($paginas as $pagina) : ?>
					<tr>
						<td>
							<?php if($pagina['arquivo']){ ?>
								<img  width="100px" height="100px" src="imagens/<?php echo $pagina['arquivo']?>" alt="<?php echo $pagina['titulo']; ?>">
							<?php } ?>
						</td>
						<td><?php echo $pagina['titulo'] ?></td>
						<td><?php echo $pagina['descricao'] ?></td>
						<td><?php echo $pagina['data'] ?></td>
						<td>
							<a href="form_page.php?id=<?php echo $pagina['id']; ?>&titulo=<?php echo $pagina['titulo']; ?> " >
								Editar
							</a> - 
							<a href="delete_page.php?id=<?php echo $pagina['id']; ?>&titulo=<?php echo $pagina['titulo']; ?> " >
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