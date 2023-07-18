<?php
// Verificar si se reciben los parámetros esperados
if (isset($_GET['rut']) && isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $rut = $_GET['rut'];
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    // Realizar la lógica de exportación del empleado
    try {
        $host = 'magallanes.inf.unap.cl';
        $port = '5432';
        $dbname = 'jgomez';
        $user = 'jgomez';
        $password = '262m79VhrgMj';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";
        $conexion = new PDO($dsn);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $consulta = "SELECT * FROM asistencia WHERE rut = :rut AND fecha_asistencia BETWEEN :startDate AND :endDate";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$resultado) {
            throw new Exception("No se encontraron registros de asistencia para el empleado entre las fechas especificadas.");
        }

        // Generar el archivo Excel con los datos del empleado
        require_once 'Classes/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Historial de Asistencia');

        // Escribir la cabecera
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID Asistencia');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'RUT');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Fecha de Asistencia');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Hora de Entrada');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Hora de Salida');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Estado de Asistencia');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Horas Trabajadas');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Razón de Ausencia');

        // Escribir los datos del empleado
        $row = 2;
        foreach ($resultado as $fila) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $fila['id_asistencia']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $fila['rut']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $fila['fecha_asistencia']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $fila['hora_entrada']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $fila['hora_salida']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $fila['estado_asistencia']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $fila['horas_trabajadas']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $fila['razon_ausencia']);
            $row++;
        }

        // Establecer el estilo de la tabla
        $styleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]],
            'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => ['rgb' => 'EFEFEF']],
        ];

        $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
        $objPHPExcel->getActiveSheet()->getStyle('A1:' . $lastColumn . '1')->applyFromArray($styleArray);

        // Ajustar el ancho de las columnas
        foreach (range('A', $lastColumn) as $column) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }

        // Crear un nombre de archivo único para evitar conflictos
        $fileName = 'historial_asistencia_' . time() . '.xlsx';
        $filePath = '/ruta/a/tu/carpeta/de/descargas/' . $fileName;

        // Guardar el archivo Excel en el servidor
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save($filePath);

        // Configurar el encabezado de la respuesta HTTP para la descarga
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));

        // Leer y enviar el contenido del archivo
        readfile($filePath);

        // Eliminar el archivo temporal
        unlink($filePath);

        exit();
    } catch (Exception $e) {
        // Manejo de errores en la exportación del empleado
        echo "Error al exportar el empleado: " . $e->getMessage();
        exit();
    }
} else {
    // No se recibieron los parámetros esperados, redirigir o mostrar un mensaje de error
    echo "Error: Parámetros faltantes para exportar el empleado.";
    exit();
}
?>
