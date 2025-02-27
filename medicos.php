<?php
include "php/conn.php";

$alteraID = $_GET['altera'];
$btnAlterar = false;
$formAction = 'php/insertScripts.php?tabela=tbmedicos';

if (!is_null($alteraID)) {
    $sql = "SELECT `nome`, `CRM`, `especialidade_FK`, `data_cadastro` FROM `tbmedicos` WHERE `medico` =" . $alteraID;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $btnAlterar = true;
    $formAction = 'php/updateScripts.php?tabela=tbmedicos&id=' . $alteraID;
    $nomeInput = $row["nome"];
    $CRMInput = $row["CRM"];
    $especialidade_FKInput = $row["especialidade_FK"];
    $data_cadastroInput = $row["data_cadastro"];
}

include 'topSite.html';
?>

<div class="container-fluid pr-5 pl-5">
  <div class="card mt-4 mb-4">
    <div class="card-header bg-dark text-white">
      <h3 class="card-title m-0"><i class="fas fa-user-md mr-2"></i>Cadastro de Médicos</h3>
    </div>
    <div class="card-body">
      <form action="<?php echo $formAction; ?>" method="post">
        <div class="form-group">
          <label class="font-weight-bold">Nome:</label>
          <input type="text" class="form-control" name="inputMedicoNome" placeholder="Digite o nome" value="<?php echo $nomeInput; ?>" required>
        </div>
        <div class="form-group">
          <label class="font-weight-bold">CRM:</label>
          <input type="text" class="form-control" name="inputCRM" placeholder="Digite o CRM" value="<?php echo $CRMInput; ?>" required>
        </div>
        <div class="form-group">
          <label class="font-weight-bold">Especialidade:</label>
          <select class="form-control" name="inputEspecialidadeFK" required>
            <option value="">Selecione</option>
            <?php
            // Busca as especialidades cadastradas
            $sql = "SELECT `especialidade`, `descricao` FROM `tbespecialidades` ORDER BY `descricao`";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $selected = ($especialidade_FKInput == $row["especialidade"]) ? "selected" : "";
                echo '<option value="' . $row["especialidade"] . '" ' . $selected . '>' . $row["descricao"] . '</option>';
            }
            ?>
          </select>
        </div>
        <?php
        if ($btnAlterar) {
        ?>
          <input class="btn btn-primary" style="width:120px" type="submit" value="Alterar">
          <a class="btn btn-secondary" href="medicos.php" style="width:120px" role="submit">Cancelar</a>
        <?php } else { ?>
          <input class="btn btn-primary" style="width:120px" type="submit" value="Cadastrar">
        <?php } ?>
      </form>

      <hr class="mt-5">
      <h3 class="card-title mt-4 mb-4">Médicos Cadastrados</h3>
      <table id="tbMedicos" class="display" style="width:100%">
        <thead>
          <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>CRM</th>
            <th>Especialidade</th>
            <th>Data de Cadastro</th>
            <th>Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Consulta para obter os médicos cadastrados
          $sql = "SELECT m.medico, m.nome, m.CRM, e.descricao AS especialidade, m.data_cadastro 
                  FROM tbmedicos m
                  INNER JOIN tbespecialidades e ON m.especialidade_FK = e.especialidade
                  ORDER BY m.nome";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $dataCadastro = date_create($row["data_cadastro"]);
                  $dataCadastroFormatada = date_format($dataCadastro, "d/m/Y - H:i");
                  echo '<tr>';
                  echo '<td>' . $row["medico"] . '</td>';
                  echo '<td>' . $row["nome"] . '</td>';
                  echo '<td>' . $row["CRM"] . '</td>';
                  echo '<td>' . $row["especialidade"] . '</td>';
                  echo '<td>' . $dataCadastroFormatada . '</td>';
                  echo '<td>
                          <a href="medicos.php?altera=' . $row["medico"] . '"><i class="fas fa-sync-alt text-info mr-3"></i></a>
                          <i redirect="php/deleteScripts.php?tabela=tbmedicos&id=' . $row["medico"] . '" class="fas fa-trash-alt text-danger" onclick="dialogDelete(this)" style="cursor:pointer"></i>
                        </td>';
                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="6">Nenhum médico cadastrado.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Scripts necessários para o DataTables -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="vendor/DataTables/datatables.min.js"></script>
<script>
  $(document).ready(function() {
    // Inicializa a tabela com DataTables
    $('#tbMedicos').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json" // Tradução para português
      },
      "order": [[0, "asc"]] // Ordena a tabela pela primeira coluna (ID) em ordem crescente
    });
  });

  // Função para confirmar exclusão
  function dialogDelete(element) {
    if (confirm("Tem certeza que deseja excluir este médico?")) {
      window.location.href = element.getAttribute("redirect");
    }
  }
</script>

<?php $conn->close(); ?>