<?php
$servidor = "localhost";
$usuarioBd = "root";
$senhaBd = "";
$nomeBd = "aquivo";
$conectar = mysqli_connect($servidor, $usuarioBd, $senhaBd, $nomeBd);

##############################################################################
//Logar
##############################################################################
function logar($conectar){
	if ( isset($_POST['enviar']) AND !empty($_POST['senha']) AND !empty($_POST['email'])) {		
		
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$senha = sha1($_POST['senha']);
		$query = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha' ";
		//echo $query;
		$executar = mysqli_query( $conectar, $query );
		$resultado = mysqli_fetch_assoc($executar);
		//print_r($resultado);
		if (!empty($resultado)) {
			session_start();
			$_SESSION['nome'] = $resultado['nome'];
			$_SESSION['ativa'] = true;
			header('location:admin.php');
		}else{
			echo "E-mail ou senha inválidos";
		}
	}else{
		echo "E-mail ou senha inválidos";
	}
	if (!empty($resultado)) {
		session_start();
		$_SESSION['imagem'] = $resultado['imagem'];
		$_SESSION['ativa'] = true;
		header('location:admin.php');
	}
}
//end logar
##############################################################################
// deslogar
##############################################################################

function deslogar(){
	session_start();
	session_unset();
	session_destroy();
	header("location: index.php");
}//end deslogar
##############################################################################
##############################################################################
function selecionar($conectar, $tabela, $where=1, $order="id"){
	$query = "SELECT * FROM $tabela WHERE $where ORDER BY $order" ;
	$executar = mysqli_query( $conectar, $query);
	$resultados = mysqli_fetch_all($executar, MYSQLI_ASSOC);
	return $resultados;
}

##############################################################################
##############################################################################
function inserirUsuario($conectar){
	if ( isset($_POST['cadastrar']) AND !empty($_POST['email']) AND !empty($_POST['senha']) ) {
		$nome = mysqli_real_escape_string($conectar, $_POST['nome']);
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$senha = sha1($_POST['senha']);
		$erros = array();

		if ( !empty($_FILES['imagem']['name']) ) {
			$imagem = $_FILES['imagem']['name'];
		}else{
			$imagem = "";
		}

		if (!empty($imagem)) {
			$nomeImagem = $_FILES['imagem']['name'];
			$type = $_FILES['imagem']['type'];
			$nomeTemporario = $_FILES['imagem']['tmp_name'];
			$tamanho = $_FILES['imagem']['size'];			

			$tamanhoMaximo = 1024 * 1024 * 5; //5MB

			if ($tamanho > $tamanhoMaximo) {
				$erros[] = "Seu arquivo excede o tamanho máximo<br>";
			}

			$arquivosPermitidos = ["png","jpg","jpeg","pdf"];
			$extensao = pathinfo($nomeImagem, PATHINFO_EXTENSION);

			if ( !in_array($extensao, $arquivosPermitidos) ) {
				$erros[] = "Extensão de arquivo não permitida!<br>";
			}

			$typesPermitidos = ["application/pdf", "image/png", "image/jpg","image/jpeg"];

			if ( !in_array( $type, $typesPermitidos )) {
				$erros[] = "Tipo de arquivo não permitido!<br>";
			}

			if ( !empty($erros) ) {
				foreach ($erros as $erro) {
					echo $erro;
				}
			}else{			
				$caminho = "imagens/";
				$hoje = date('d-m-Y_h-i');
				$novoNome = $hoje."-".$nomeImagem;
				$imagem = $novoNome;
				if (move_uploaded_file($nomeTemporario, $caminho.$novoNome)) {
					echo "Upload feito com Sucesso!<br>";
				}else{
					echo "Erro ao Enviar o arquivo";
				}
			}
		} //if imagem 

		if (strlen($nome) < 3) {
			$erros[] = "Preecha seu nome completo!";
		}
		if (empty($email)) {
			$erros[] = "Preencha um e-mail válido";
		}
		if ($_POST['senha'] != $_POST['repetesenha']) {
			$erros[] = "Senhas não são iguais!";
		}

		$queryEmail = "SELECT email FROM usuarios WHERE email = '$email'";
		$buscaEmail = mysqli_query( $conectar, $queryEmail);
		$resultEmail = mysqli_fetch_assoc( $buscaEmail );

		if ( !empty($resultEmail['email']) ) {
		 	$erros[] = "E-mail já cadastrado no sistema!";
		} 

		if (empty($erros)) {
			// executa o insert
			$query = "INSERT INTO usuarios (imagem, nome, email, senha, data_cadastro) VALUES ( '$imagem', '$nome', '$email', '$senha', NOW() )";
			$executar = mysqli_query( $conectar, $query);
			if ($executar) {
				echo "Usuário inserido com sucesso!";
			}else{
				echo "Erro ao inserir Usuário!";
			}

		}else{
			foreach ($erros as $erro) {
				echo $erro . "<br>";
			}
		}
	}else{
		echo "Erro ao inserir Usuário!";
	}

} 

##############################################################################
//deletar
##############################################################################

function deletar($conectar, $tabela, $where, $redirecionar = ""){
	if (!empty($where)) {
		//$id = is_int($where);
		$id = filter_var($where, FILTER_VALIDATE_INT);

		if ($id) {
			$query = "DELETE FROM $tabela WHERE id = $where";
			$executar = mysqli_query($conectar, $query);
			if ($executar) {
				echo "Usuário Deletado com Sucesso!";
				if (!empty($redirecionar)) {
					//header("location: $redirecionar");
					echo "<script>window.location.href = '$redirecionar'</script>";
				}
				
			}else{
				echo "Erro ao deletar!";
			}
		}else{
			echo "ID Inválido!";
		}
	}
}

##############################################################################
//updateimg
##############################################################################

function updateimg($conectar){
	if ( isset($_POST['salva']) AND !empty($_POST['id']) ) {
		$id = $_POST['id'];
		$nome = mysqli_real_escape_string($conectar, $_POST['nome']);
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$data = mysqli_real_escape_string($conectar, $_POST['data_cadastro']);
		$senha = "";
		if ( !empty($_POST['senha'])) {
			if ($_POST['senha'] == $_POST['repetesenha']) {
				$senha = sha1($_POST['senha']);
			}else{
				$erros[] = "Senhas não são iguais";
			}
		}
		$erros = array();

		if ( !empty($_FILES['imagem']['name']) ) {
			$imagem = $_FILES['imagem']['name'];
		}else{
			$imagem = "";
		}

		if (!empty($imagem)) {
			$nomeImagem = $_FILES['imagem']['name'];
			$type = $_FILES['imagem']['type'];
			$nomeTemporario = $_FILES['imagem']['tmp_name'];
			$tamanho = $_FILES['imagem']['size'];			

			$tamanhoMaximo = 1024 * 1024 * 5; //5MB

			if ($tamanho > $tamanhoMaximo) {
				$erros[] = "Seu arquivo excede o tamanho máximo<br>";
			}

			$arquivosPermitidos = ["png","jpg","jpeg","pdf"];
			$extensao = pathinfo($nomeImagem, PATHINFO_EXTENSION);

			if ( !in_array($extensao, $arquivosPermitidos) ) {
				$erros[] = "Extensão de arquivo não permitida!<br>";
			}

			$typesPermitidos = ["application/pdf", "image/png", "image/jpg","image/jpeg"];

			if ( !in_array( $type, $typesPermitidos )) {
				$erros[] = "Tipo de arquivo não permitido!<br>";
			}

			if ( !empty($erros) ) {
				foreach ($erros as $erro) {
					echo $erro;
				}
			}else{			
				$caminho = "imagens/";
				$hoje = date('d-m-Y_h-i');
				$novoNome = $hoje."-".$nomeImagem;
				$imagem = $novoNome;
				if (move_uploaded_file($nomeTemporario, $caminho.$novoNome)) {
					echo "Upload feito com Sucesso!<br>";
				}else{
					echo "Erro ao Enviar o arquivo";
				}
			}
		} //if imagem 
		$queryEmailAtual = "SELECT email FROM usuarios WHERE id = $id";
		$executaBusca = mysqli_query($conectar, $queryEmailAtual);
		$retornoEmail = mysqli_fetch_assoc($executaBusca);
		$emailAtual = $retornoEmail['email'];

		$queryEmail = "SELECT email FROM usuarios WHERE email = '$email' AND email <> '$emailAtual' ";
		$buscaEmail = mysqli_query( $conectar, $queryEmail);
		$resultEmail = mysqli_fetch_assoc( $buscaEmail );

		if ( !empty($resultEmail['email']) ) {
		 	$erros[] = "E-mail já cadastrado no sistema!";
		} 

		if (strlen($nome) < 3) {
			$erros[] = "Preecha seu nome completo!";
		}	
		if (empty($email)) {
			$erros[] = "Preencha um e-mail válido";
		}
		if (empty($data)) {
			$erros[] = "Preencha a data de cadastro";
		}
		if (empty($erros)) {
			if (!empty($senha)) {
				$query = "UPDATE usuarios SET senha = '$senha', nome = '$nome', email = '$email', data_cadastro = '$data' WHERE id = $id ";
			}else{
				$query = "UPDATE usuarios SET nome = '$nome', email = '$email', data_cadastro = '$data' WHERE id = $id ";
			}						
			$executar = mysqli_query( $conectar, $query);			
			if ($executar) {
				echo "Usuário atualizado com sucesso!";
			}else{
				echo "Erro ao atualizar o Usuário!";
			}

		}

		if (empty($erros)) {
			
			if (!empty($imagem)) {
				$query = "UPDATE usuarios SET imagem = '$imagem', senha = '$senha' , nome = '$nome', email = '$email', data_cadastro = '$data' WHERE id = $id ";
			}elseif(isset($_POST['delete-imagem'])){
				$imagem = "";
				$query = "UPDATE usuarios SET imagem = '$imagem', senha = '$senha' , nome = '$nome', email = '$email', data_cadastro = '$data' WHERE id = $id ";
			}else{
				$query = "UPDATE paginas SET senha = '$senha', nome = '$nome', email = '$email', data_cadastro = '$data' WHERE id = $id ";
			}
			
			


			$executar = mysqli_query( $conectar, $query);
			echo mysqli_error($conectar);

			if ($executar) {
				echo "Página Atualizada com sucesso!";
			}else{
				echo "Erro ao inserir página no BD!";
			}

		}else{
			foreach ($erros as $erro) {
				echo $erro . "<br>";
			}
		}
	}else{
		echo "Erro ao atualizar Página!";
	}

}
//end updateimg
##############################################################################
// insertPage
##############################################################################

Function insertPage($conectar){
	if ( isset($_POST['enviar']) AND !empty($_POST['titulo']) ) {
		$titulo = mysqli_real_escape_string($conectar, $_POST['titulo']);
		$descricao = mysqli_real_escape_string($conectar, $_POST['descricao']);
		$data = mysqli_real_escape_string($conectar, $_POST['data']);
		$erros = array();

		if ( !empty($_FILES['arquivo']['name']) ) {
			$arquivo = $_FILES['arquivo']['name'];
		}else{
			$arquivo = "";
		}

		if (!empty($arquivo)){
			$nomeArquivo = $_FILES['arquivo']['name'];
			$modelo = $_FILES['arquivo']['type'];
			$nameTemporary = $_FILES['arquivo']['tmp_name'];
			$size = $_FILES['arquivo']['size'];

			$maximumsize = 1024 * 1024 * 5;

			if($size > $maximumsize){
				$erros[] = "Seu arquivo excede o tamanho máximo<br>";
			}
			
			$filePermitidos = ["png","jpg","jpeg","pdf"];
			$extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);

			if ( !in_array($extensao, $filePermitidos) ) {
				$erros[] = "Extensão de arquivo não permitida!<br>";
			}

			$modeloPermitidos = ["application/pdf", "image/png", "image/jpg","image/jpeg"];

			if ( !in_array( $modelo, $modeloPermitidos )) {
				$erros[] = "Tipo de arquivo não permitido!<br>";
			}
			if ( !empty($erros) ) {
				foreach ($erros as $erro) {
					echo $erro;
				}
			}else{			
				$way = "imagens/";
				$today = date('d-m-Y_h-i');
				$newName = $today."-".$nomeArquivo;
				$arquivo = $newName;
				if (move_uploaded_file($nameTemporary, $way. $newName)) {
					echo "Upload feito com Sucesso!<br>";
				}else{
					echo "Erro ao Enviar o arquivo";
				}
			}
		}

		if (strlen($titulo) < 3) {
			$erros[] = "Preecha seu nome completo!";
		}
		if (empty($erros)) {
			$query = "INSERT INTO paginas (titulo, descricao, arquivo, data) VALUES ( '$titulo', '$descricao', '$arquivo', '$data' )";
			$executar = mysqli_query( $conectar, $query);
			if ($executar) {
				echo "Página inserida com sucesso!";
			}else{
				echo "Erro ao inserir página no BD!";
			}

		}else{
			foreach($erros as $erro ){
				echo $erro."<br>";
			}
		}
	}else{
		echo "Erro ao inserir pagina!";
	}
	
}
##############################################################################
##############################################################################

function updatePage($conectar){
	if ( isset($_POST['atualizar']) AND !empty($_POST['id']) ) {
		$id = $_POST['id'];
		$titulo = mysqli_real_escape_string($conectar, $_POST['titulo']);
		$descricao = mysqli_real_escape_string($conectar, $_POST['descricao']);
		$data = mysqli_real_escape_string($conectar, $_POST['data']);
		$erros = array();
		
		if ( !empty($_FILES['arquivo']['name']) ) {
			$arquivo = $_FILES['arquivo']['name'];
		}else{
			$arquivo = "";
		}

		if (!empty($arquivo)){
			$nomeArquivo = $_FILES['arquivo']['name'];
			$modelo = $_FILES['arquivo']['type'];
			$nameTemporary = $_FILES['arquivo']['tmp_name'];
			$size = $_FILES['arquivo']['size'];

			$maximumsize = 1024 * 1024 * 5;

			if($size > $maximumsize){
				$erros[] = "Seu arquivo excede o tamanho máximo<br>";
			}
			
			$filePermitidos = ["png","jpg","jpeg","pdf"];
			$extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);

			if ( !in_array($extensao, $filePermitidos) ) {
				$erros[] = "Extensão de arquivo não permitida!<br>";
			}

			$modeloPermitidos = ["application/pdf", "image/png", "image/jpg","image/jpeg"];

			if ( !in_array( $modelo, $modeloPermitidos )) {
				$erros[] = "Tipo de arquivo não permitido!<br>";
			}
			if ( !empty($erros) ) {
				foreach ($erros as $erro) {
					echo $erro;
				}
			}else{			
				$way = "imagens/";
				$today = date('d-m-Y_h-i');
				$newName = $today."-".$nomeArquivo;
				$arquivo = $newName;
				if (move_uploaded_file($nameTemporary, $way. $newName)) {
					echo "Upload feito com Sucesso!<br>";
				}else{
					echo "Erro ao Enviar o arquivo";
				}
			}
		}//if imagem 
		
		if (strlen($titulo) < 3) {
			$erros[] = "Preecha seu nome completo!";
		}
		
		if (empty($erros)) {
			
			if (!empty($arquivo)) {
				$query = "UPDATE paginas SET titulo = '$titulo', descricao = '$descricao', data = '$data', arquivo = '$arquivo' WHERE id = $id ";
			} elseif(isset($_POST['delete-arquivo'])){
				$imagem = "";
				$query = "UPDATE paginas SET titulo = '$titulo', descricao = '$descricao', data = '$data', arquivo = '$arquivo' WHERE id = $id ";
			}else{
				$query = "UPDATE paginas SET titulo = '$titulo', descricao = '$descricao', data = '$data' WHERE id = $id ";
			}
			
			$executar = mysqli_query( $conectar, $query);
			
			if ($executar) {
				echo "Página Atualizada com sucesso!";
			}else{
				echo "Erro ao inserir página no BD!";
			}

		}else{
			foreach ($erros as $erro) {
				echo $erro . "<br>";
			}
		}
	}else{
		echo "Erro ao atualizar Página!";
	}

}
