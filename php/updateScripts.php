<?php
include "conn.php"; // Inclui a conexão com o banco de dados
$tabela = $_GET['tabela']; // Obtém a tabela a ser alterada

if ($tabela == 'tbmedicos') {
    // Atualização de Médicos
    $id = $_GET['id']; // Obtém o ID do médico

    $inputMedicoNome = $_POST['inputMedicoNome'];
    $inputCRM = $_POST['inputCRM'];
    $inputEspecialidadeFK = $_POST['inputEspecialidadeFK'];

    $sqlQuery = "UPDATE `tbmedicos` 
                 SET 
                     `nome` = '".$inputMedicoNome."',
                     `CRM` = '".$inputCRM."',
                     `especialidade_FK` = '".$inputEspecialidadeFK."'
                 WHERE 
                     `medico` = ".$id;

    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Registro de médico alterado com sucesso";
    } else {
        $msg = "ERRO SQL: " . $sqlQuery . " - Mensagem do Servidor: " . $conn->error;
    }
    echo '<script>alert("'.$msg.'");window.location.href="../medicos.php";</script>';

} elseif ($tabela == 'tbpacientes') {
    // Atualização de Pacientes
    $id = $_GET['id']; // Obtém o ID do paciente

    $inputPacienteNome = $_POST['inputPacienteNome'];
    $inputCPF = $_POST['inputCPF'];
    $inputPlano = $_POST['inputPlano'];
    $inputDataNascimento = $_POST['inputDataNascimento'];

    $sqlQuery = "UPDATE `tbpacientes` 
                 SET 
                     `nome` = '".$inputPacienteNome."',
                     `cpf` = '".$inputCPF."',
                     `plano` = '".$inputPlano."',
                     `data_nascimento` = '".$inputDataNascimento."'
                 WHERE 
                     `paciente` = ".$id;

    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Registro de paciente alterado com sucesso";
    } else {
        $msg = "ERRO SQL: " . $sqlQuery . " - Mensagem do Servidor: " . $conn->error;
    }
    echo '<script>alert("'.$msg.'");window.location.href="../pacientes.php";</script>';

} elseif ($tabela == 'tbconsultas') {
    // Atualização de Consultas
    $id = $_GET['id']; // Obtém o ID da consulta

    $inputMedicoFK = $_POST['inputMedicoFK'];
    $inputPacienteFK = $_POST['inputPacienteFK'];
    $inputDataConsulta = $_POST['inputDataConsulta'];
    $inputHorarioConsulta = $_POST['inputHorarioConsulta'];

    $sqlQuery = "UPDATE `tbconsultas` 
                 SET 
                     `medico_FK` = '".$inputMedicoFK."',
                     `paciente_FK` = '".$inputPacienteFK."',
                     `data` = '".$inputDataConsulta."',
                     `horario` = '".$inputHorarioConsulta."'
                 WHERE 
                     `consulta` = ".$id;

    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Registro de consulta alterado com sucesso";
    } else {
        $msg = "ERRO SQL: " . $sqlQuery . " - Mensagem do Servidor: " . $conn->error;
    }
    echo '<script>alert("'.$msg.'");window.location.href="../consultas.php";</script>';

} else {
    // Se a tabela não for reconhecida
    $msg = "Tabela inválida.";
    echo '<script>alert("'.$msg.'");window.location.href="../medicos.php";</script>';
}

$conn->close(); // Fecha a conexão com o banco de dados
?>