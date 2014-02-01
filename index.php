<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

if (!empty($_FILES) && !empty($_POST)){
    $tempFile = $_FILES['csv']['tmp_name'];
    $targetFile = __DIR__.'/uploads/'.uniqid().'.csv';

        move_uploaded_file($tempFile, $targetFile);
    	
        include 'library/PHPExcel/IOFactory.php';

    	$objReader = PHPExcel_IOFactory::createReader('CSV');

    	// If the files uses a delimiter other than a comma (e.g. a tab), then tell the reader
    	$objReader->setDelimiter(",");
    	// If the files uses an encoding other than UTF-8 or ASCII, then tell the reader
    	$objReader->setInputEncoding('UTF-8');
        $objPHPExcel = $objReader->load($targetFile);

        if ($_POST['output'] == 1){
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="output.xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

    	   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        }else{
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="output.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        }

        unlink($targetFile);
    	$objWriter->save('php://output');
        exit;
    
}?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CSV to XLS/XLSX converter</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">

</head>

<body>

    <div id="top" class="header">
        <div class="vert-text">
            <form method="POST" enctype="multipart/form-data" action="" class="form-inline" role="form">
                <h1>CSV to XLS / XLSX converter</h1>
                <h3>Choose your CSV file and select output format</h3>
                <div id="form" class="container">
                    <div class="row">
                        <div class="form-group">
                            <input class="form-control" type="file" name="csv"/>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="output">
                                <option value="1">XLS</option>
                                <option value="2">XLSX</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-danger btn-lg" value="Submit">
            </form>
        </div>
    </div>

    <!-- /.container -->

    <!-- JavaScript -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>

</body>

</html>