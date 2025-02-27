<?php
include "php/conn.php"; // Inclui a conexão com o banco de dados

include 'topSite.html'; // Inclui o cabeçalho padrão
?>

<div class="container-fluid pr-5 pl-5">
  <!-- Formulário para selecionar o paciente -->
  <div class="card mt-4 mb-4">
    <div class="card-header bg-dark text-white">
      <h3 class="card-title m-0"><i class="fas fa-user-injured mr-2"></i>Consulta de Pacientes</h3>
    </div>
    <div class="card-body">
      <form method="get" action="">
        <div class="form-group">
          <label class="font-weight-bold">Selecione o Paciente:</label>
          <select class="form-control" name="paciente" required onchange="this.form.submit()">
            <option value="">Selecione um paciente</option>
            <?php
              // Busca os pacientes cadastrados
              $sql = "SELECT `paciente`, `nome` FROM `tbpacientes` ORDER BY `nome`";
              $result = $conn->query($sql);    
              while($row = $result->fetch_assoc()) {
                $selected = (isset($_GET['paciente']) && $_GET['paciente'] == $row["paciente"]) ? "selected" : "";
                echo '<option value="'.$row["paciente"].'" '.$selected.'>'.$row["nome"].'</option>';
              }
            ?>
          </select>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabela para exibir o histórico de consultas do paciente selecionado -->
  <?php
  if (isset($_GET['paciente']) && !empty($_GET['paciente'])) {
    $pacienteID = $_GET['paciente'];

    // Consulta para obter o histórico de consultas do paciente selecionado
    $sql = "SELECT 
              c.consulta AS id_consulta,
              m.nome AS nome_medico,
              c.data AS data_consulta,
              c.horario AS horario_consulta
            FROM 
              tbconsultas c
            INNER JOIN 
              tbmedicos m ON c.medico_FK = m.medico
            WHERE 
              c.paciente_FK = " . $pacienteID . "
            ORDER BY 
              c.data, c.horario";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo '<div class="card mt-4 mb-4">';
      echo '<div class="card-header bg-dark text-white">';
      echo '<h3 class="card-title m-0"><i class="fas fa-list mr-2"></i>Histórico de Consultas</h3>';
      echo '</div>';
      echo '<div class="card-body">';
      echo '<table id="tbConsultasPaciente" class="display" style="width:100%">';
      echo '<thead>';
      echo '<tr>';
      echo '<th>Médico</th>';
      echo '<th>Data</th>';
      echo '<th>Horário</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';

      // Exibe as consultas do paciente selecionado
      while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["nome_medico"] . '</td>';
        echo '<td>' . date("d/m/Y", strtotime($row["data_consulta"])) . '</td>';
        echo '<td>' . $row["horario_consulta"] . '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
      echo '</div>';
    } else {
      echo '<div class="alert alert-warning mt-4">Nenhuma consulta encontrada para este paciente.</div>';
    }
  }
  ?>
</div>

<!-- Scripts necessários para o DataTables -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="vendor/DataTables/datatables.min.js"></script>
<script>
  $(document).ready(function() {
    // Inicializa a tabela com DataTables
    $('#tbConsultasPaciente').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json" // Tradução para português
      },
      "order": [[1, "asc"]] // Ordena a tabela pela data em ordem crescente
    });
  });
</script>

<?php $conn->close(); // Fecha a conexão com o banco de dados ?>