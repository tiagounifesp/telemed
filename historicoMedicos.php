<?php
include "php/conn.php"; // Inclui a conexão com o banco de dados

include 'topSite.html'; // Inclui o cabeçalho padrão
?>

<div class="container-fluid pr-5 pl-5">
  <!-- Formulário para selecionar o médico -->
  <div class="card mt-4 mb-4">
    <div class="card-header bg-dark text-white">
      <h3 class="card-title m-0"><i class="fas fa-user-md mr-2"></i>Consulta de Médicos</h3>
    </div>
    <div class="card-body">
      <form method="get" action="">
        <div class="form-group">
          <label class="font-weight-bold">Selecione o Médico:</label>
          <select class="form-control" name="medico" required onchange="this.form.submit()">
            <option value="">Selecione um médico</option>
            <?php
              // Busca os médicos cadastrados
              $sql = "SELECT `medico`, `nome` FROM `tbmedicos` ORDER BY `nome`";
              $result = $conn->query($sql);    
              while($row = $result->fetch_assoc()) {
                $selected = (isset($_GET['medico']) && $_GET['medico'] == $row["medico"]) ? "selected" : "";
                echo '<option value="'.$row["medico"].'" '.$selected.'>'.$row["nome"].'</option>';
              }
            ?>
          </select>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabela para exibir o histórico de consultas do médico selecionado -->
  <?php
  if (isset($_GET['medico']) && !empty($_GET['medico'])) {
    $medicoID = $_GET['medico'];

    // Consulta para obter o histórico de consultas do médico selecionado
    $sql = "SELECT 
              c.consulta AS id_consulta,
              p.nome AS nome_paciente,
              c.data AS data_consulta,
              c.horario AS horario_consulta
            FROM 
              tbconsultas c
            INNER JOIN 
              tbpacientes p ON c.paciente_FK = p.paciente
            WHERE 
              c.medico_FK = " . $medicoID . "
            ORDER BY 
              c.data, c.horario";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      echo '<div class="card mt-4 mb-4">';
      echo '<div class="card-header bg-dark text-white">';
      echo '<h3 class="card-title m-0"><i class="fas fa-list mr-2"></i>Histórico de Consultas</h3>';
      echo '</div>';
      echo '<div class="card-body">';
      echo '<table id="tbConsultasMedico" class="display" style="width:100%">';
      echo '<thead>';
      echo '<tr>';
      echo '<th>Paciente</th>';
      echo '<th>Data</th>';
      echo '<th>Horário</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';

      // Exibe as consultas do médico selecionado
      while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["nome_paciente"] . '</td>';
        echo '<td>' . date("d/m/Y", strtotime($row["data_consulta"])) . '</td>';
        echo '<td>' . $row["horario_consulta"] . '</td>';
        echo '</tr>';
      }

      echo '</tbody>';
      echo '</table>';
      echo '</div>';
      echo '</div>';
    } else {
      echo '<div class="alert alert-warning mt-4">Nenhuma consulta encontrada para este médico.</div>';
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
    $('#tbConsultasMedico').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json" // Tradução para português
      },
      "order": [[1, "asc"]] // Ordena a tabela pela data em ordem crescente
    });
  });
</script>

<?php $conn->close(); // Fecha a conexão com o banco de dados ?>