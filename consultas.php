<?php
include "php/conn.php";

$alteraID = $_GET['altera'];
$btnAlterar = false;
$formAction = 'php/insertScripts.php?tabela=tbconsultas';

if (!is_null($alteraID)) {
    $sql = "SELECT `medico_FK`, `paciente_FK`, `data`, `horario` FROM `tbconsultas` WHERE `consulta` = " . $alteraID;
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $btnAlterar = true;
    $formAction = 'php/updateScripts.php?tabela=tbconsultas&id=' . $alteraID;
    $medicoFKInput = $row["medico_FK"];
    $pacienteFKInput = $row["paciente_FK"];
    $dataConsultaInput = $row["data"];
    $horarioConsultaInput = $row["horario"];
}

include 'topSite.html';
?>

<div class="container-fluid pr-5 pl-5">
  <div class="card mt-4 mb-4">
    <div class="card-header bg-dark text-white">
      <h3 class="card-title m-0"><i class="fas fa-calendar-check mr-2"></i>Agendar Consulta</h3>
    </div>
    <div class="card-body">
      <form action="<?php echo $formAction; ?>" method="post">
        <div class="form-group">
          <label class="font-weight-bold">Médico:</label>
          <select class="form-control" name="inputMedicoFK" required>
            <option value="">Selecione o Médico</option>
            <?php
              $sql = "SELECT `medico`, `nome` FROM `tbmedicos` ORDER BY `nome`";
              $result = $conn->query($sql);    
              while($row = $result->fetch_assoc()) {
                $selected = ($btnAlterar && $medicoFKInput == $row["medico"]) ? "selected" : "";
                echo '<option value="'.$row["medico"].'" '.$selected.'>'.$row["nome"].'</option>';
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label class="font-weight-bold">Paciente:</label>
          <select class="form-control" name="inputPacienteFK" required>
            <option value="">Selecione o Paciente</option>
            <?php
              $sql = "SELECT `paciente`, `nome` FROM `tbpacientes` ORDER BY `nome`";
              $result = $conn->query($sql);    
              while($row = $result->fetch_assoc()) {
                $selected = ($btnAlterar && $pacienteFKInput == $row["paciente"]) ? "selected" : "";
                echo '<option value="'.$row["paciente"].'" '.$selected.'>'.$row["nome"].'</option>';
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label class="font-weight-bold">Data da Consulta:</label>
          <input type="date" class="form-control" name="inputDataConsulta" value="<?php echo $btnAlterar ? $dataConsultaInput : ''; ?>" required>
        </div>
        <div class="form-group">
          <label class="font-weight-bold">Horário da Consulta:</label>
          <input type="time" class="form-control" name="inputHorarioConsulta" value="<?php echo $btnAlterar ? $horarioConsultaInput : ''; ?>" required>
        </div>
        <?php if ($btnAlterar) { ?>
          <input class="btn btn-primary" style="width:120px" type="submit" value="Alterar">
          <a class="btn btn-secondary" href="consultas.php" style="width:120px" role="submit">Cancelar</a>
        <?php } else { ?>
          <input class="btn btn-primary" style="width:120px" type="submit" value="Agendar">
        <?php } ?>
      </form>
    </div>
  </div>

  <div class="card mt-4 mb-4">
    <div class="card-header bg-dark text-white">
      <h3 class="card-title m-0"><i class="fas fa-list mr-2"></i>Consultas Agendadas</h3>
    </div>
    <div class="card-body">
      <table id="tbConsultas" class="display" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Médico</th>
            <th>Paciente</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Ação</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT 
                      c.consulta AS id_consulta,
                      m.nome AS nome_medico,
                      p.nome AS nome_paciente,
                      c.data AS data_consulta,
                      c.horario AS horario_consulta
                    FROM 
                      tbconsultas c
                    INNER JOIN 
                      tbmedicos m ON c.medico_FK = m.medico
                    INNER JOIN 
                      tbpacientes p ON c.paciente_FK = p.paciente
                    ORDER BY 
                      c.data, c.horario";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["id_consulta"] . '</td>';
                echo '<td>' . $row["nome_medico"] . '</td>';
                echo '<td>' . $row["nome_paciente"] . '</td>';
                echo '<td>' . date("d/m/Y", strtotime($row["data_consulta"])) . '</td>';
                echo '<td>' . $row["horario_consulta"] . '</td>';
                echo '<td>
                        <a href="consultas.php?altera='.$row["id_consulta"].'"><i class="fas fa-sync-alt text-info mr-3"></i></a>
                        <i redirect="php/deleteScripts.php?tabela=tbconsultas&id='.$row["id_consulta"].'" class="fas fa-trash-alt text-danger" onclick="dialogDelete(this)" style="cursor:pointer"></i>
                      </td>';
                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="6">Nenhuma consulta agendada.</td></tr>';
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="vendor/DataTables/datatables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#tbConsultas').DataTable({
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
      },
      "order": [[0, "asc"]]
    });
  });

  function dialogDelete(element) {
    if (confirm("Tem certeza que deseja excluir esta consulta?")) {
      window.location.href = element.getAttribute("redirect");
    }
  }
</script>

<?php $conn->close(); ?>