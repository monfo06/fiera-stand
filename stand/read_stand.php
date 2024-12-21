<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $sql = "SELECT Stand.*, Fiera.nome AS fiera_nome 
            FROM Stand 
            JOIN Fiera ON Stand.fiera_id = Fiera.id 
            WHERE Stand.id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                // Fetch result row as an associative array
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $nome_stand = $row["nome_stand"];
                $responsabile = $row["responsabile"];
                $tipologia = $row["tipologia"];
                $numero_dipendenti = $row["numero_dipendenti"];
                $fiera_nome = $row["fiera_nome"];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Stand Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Stand Record</h1>
                    <div class="form-group">
                        <label>Nome Stand</label>
                        <p><b><?php echo $nome_stand; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Responsabile</label>
                        <p><b><?php echo $responsabile; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Tipologia</label>
                        <p><b><?php echo $tipologia; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Numero Dipendenti</label>
                        <p><b><?php echo $numero_dipendenti; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Fiera Associata</label>
                        <p><b><?php echo $fiera_nome; ?></b></p>
                    </div>
                    <p><a href="indexstand.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
