<?php 
$tabela = 'acessos';
require_once("../../verificar.php");
require_once("../../../conexao.php");

$query = $pdo->query("SELECT * from $tabela order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
echo <<<HTML

	<table class="table text-nowrap border-bottom dt-responsive" id="tabela">
	<thead> 
	<tr class="bg-primary"> 
	<th align="center" width="5%" class="text-center">Selecionar</th>
	<th>Nome</th>	
	<th>Chave</th>	
	<th>Grupo</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i<$linhas; $i++){
	$id = $res[$i]['id'];
	$nome = $res[$i]['nome'];
	$grupo = $res[$i]['grupo'];
	$chave = $res[$i]['chave'];
	$pagina = $res[$i]['pagina'];

$query2 = $pdo->query("SELECT * from grupo_acessos where id = '$grupo' ");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_grupo = $res2[0]['nome'];
}else{
	$nome_grupo = 'Sem Grupo';
}

		
echo <<<HTML
<tr>
<td align="center">
<div class="custom-checkbox custom-control">
<input type="checkbox" class="custom-control-input" id="seletor-{$id}" onchange="selecionar('{$id}')">
<label for="seletor-{$id}" class="custom-control-label mt-1 text-dark"></label>
</div>
</td>
<td>{$nome}</td>
<td class="esc">{$chave}</td>
<td class="esc">{$nome_grupo}</td>

<td>
	<big><a class="btn btn-info btn-sm" href="#" onclick="editar('{$id}','{$nome}','{$chave}','{$grupo}','{$pagina}')" title="Editar Dados"><i class="fa fa-edit "></i></a></big>

		<div class="dropdown" style="display: inline-block;">                      
                        <a class="btn btn-danger btn-sm" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-trash"></i> </a>
                        <div  class="dropdown-menu tx-13">
                        <div class="dropdown-item-text botao_excluir">
                        <p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>


</td>
</tr>
HTML;

}


echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
HTML;

}else{
	echo 'Nenhum Registro Encontrado!';
}

?>



<script type="text/javascript">
	$(document).ready( function () {	
	var ordenar = "<?=$filtrar_colunas?>";
	
	if(ordenar.trim() === 'true'){
		 $('#tabela').DataTable({
    		"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
	        },
	        "ordering": true,
			"stateSave": true
	    });	
	}else{
		
		$('#tabela').DataTable({
    		"language" : {
            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'
	        },
	        "ordering": false,
			"stateSave": true
	    });	
	}
   


} );
</script>

<script type="text/javascript">
	function editar(id, nome, chave, grupo, pagina){
		$('#mensagem').text('');
    	$('#titulo_inserir').text('Editar Registro');

    	$('#id').val(id);
    	$('#nome').val(nome);
    	$('#chave').val(chave);
    	$('#grupo').val(grupo).change();
    	$('#pagina').val(pagina).change();
    
    	$('#modalForm').modal('show');
	}



	function limparCampos(){
		$('#id').val('');
    	$('#nome').val('');
    	$('#chave').val('');
    	$('#grupo').val('0').change();

    	$('#ids').val('');
    	$('#btn-deletar').hide();	
	}

	function selecionar(id){

		var ids = $('#ids').val();

		if($('#seletor-'+id).is(":checked") == true){
			var novo_id = ids + id + '-';
			$('#ids').val(novo_id);
		}else{
			var retirar = ids.replace(id + '-', '');
			$('#ids').val(retirar);
		}

		var ids_final = $('#ids').val();
		if(ids_final == ""){
			$('#btn-deletar').hide();
		}else{
			$('#btn-deletar').show();
		}
	}

	function deletarSel(){
		var ids = $('#ids').val();
		var id = ids.split("-");
		
		for(i=0; i<id.length-1; i++){
			excluirMultiplos(id[i]);			
		}

		setTimeout(() => {
		  	listar();	
		}, 1000);
		
		limparCampos();
	}
</script>