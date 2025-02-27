<?php
include "conn.php"; // Inclui a conexão com o banco de dados
$tabela = $_GET['tabela']; // Obtém a tabela a ser alterada

if ($tabela == 'tbmedicos') {
    // Exclusão de Médicos
    $id = $_GET['id']; // Obtém o ID do médico

    $sqlQuery = "DELETE FROM `tbmedicos` WHERE `medico` = " . $id;

    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Registro de médico apagado com sucesso";
    } else {
        $msg = "ERRO SQL: " . $sqlQuery . " - Mensagem do Servidor: " . $conn->error;
    }
    echo '<script>alert("'.$msg.'");window.location.href="../medicos.php";</script>';

} elseif ($tabela == 'tbpacientes') {
    // Exclusão de Pacientes
    $id = $_GET['id']; // Obtém o ID do paciente

    $sqlQuery = "DELETE FROM `tbpacientes` WHERE `paciente` = " . $id;

    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Registro de paciente apagado com sucesso";
    } else {
        $msg = "ERRO SQL: " . $sqlQuery . " - Mensagem do Servidor: " . $conn->error;
    }
    echo '<script>alert("'.$msg.'");window.location.href="../pacientes.php";</script>';

} elseif ($tabela == 'tbconsultas') {
    // Exclusão de Consultas
    $id = $_GET['id']; // Obtém o ID da consulta

    $sqlQuery = "DELETE FROM `tbconsultas` WHERE `consulta` = " . $id;

    if ($conn->query($sqlQuery) === TRUE) {
        $msg = "Registro de consulta apagado com sucesso";
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