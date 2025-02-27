<?php
include "conn.php";
$tabela = $_GET['tabela'];

if ($tabela == 'tbmedicos') {
    // Inserção de Médicos
    $inputMedicoNome = $_POST['inputMedicoNome'];
    $inputCRM = $_POST['inputCRM'];
    $inputEspecialidadeFK = $_POST['inputEspecialidadeFK'];   
    $dtCadastro = date("Y-m-d");
    
    $sqlQuery = "INSERT INTO `tbmedicos`( `nome`, `CRM`, `especialidade_FK`, `data_cadastro`) 
                      VALUES (
                      '$inputMedicoNome',
                      '$inputCRM',
                      '$inputEspecialidadeFK',
                      '$dtCadastro')";
    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Médico incluído com sucesso";            
    } else {
        $msg = "ERRO SQL: " . $sqlQuery . " - Mensagem do Servidor: " . $conn->error;
    }  
    echo '<script>alert("'.$msg.'");window.location.href="../medicos.php";</script>';                    

} elseif ($tabela == 'tbpacientes') {
    // Inserção de Pacientes
    $inputPacienteNome = $_POST['inputPacienteNome'];
    $inputCPF = $_POST['inputCPF'];
    $inputPlano = $_POST['inputPlano'];   
    $inputDataNascimento = $_POST['inputDataNascimento'];
    
    $sqlQuery = "INSERT INTO `tbpacientes`( `nome`, `cpf`, `plano`, `data_nascimento`) 
                      VALUES (
                      '$inputPacienteNome',
                      '$inputCPF',
                      '$inputPlano',
                      '$inputDataNascimento')";
    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Paciente incluído com sucesso";            
    } else {
        $msg = "ERRO SQL: " . $sqlQuery . " - Mensagem do Servidor: " . $conn->error;
    }  
    echo '<script>alert("'.$msg.'");window.location.href="../pacientes.php";</script>';                      

} elseif ($tabela == 'tbconsultas') {
    // Inserção de Consultas
    $inputMedicoFK = $_POST['inputMedicoFK'];
    $inputPacienteFK = $_POST['inputPacienteFK'];
    $inputDataConsulta = $_POST['inputDataConsulta'];
    $inputHorarioConsulta = $_POST['inputHorarioConsulta'];
    
    $sqlQuery = "INSERT INTO `tbconsultas`( `medico_FK`, `paciente_FK`, `data`, `horario`) 
                      VALUES (
                      '$inputMedicoFK',
                      '$inputPacienteFK',
                      '$inputDataConsulta',
                      '$inputHorarioConsulta')";
    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Consulta agendada com sucesso";            
    } else {
        $msg = "ERRO SQL: " . $sqlQuery . " - Mensagem do Servidor: " . $conn->error;
    }  
    echo '<script>alert("'.$msg.'");window.location.href="../consultas.php";</script>';                      
}
?>