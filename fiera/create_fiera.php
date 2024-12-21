<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nome = $luogo = $data_inizio = $data_fine = $costo_ingresso = $numero_visitatori_attesi = $categoria = "";
$nome_err = $luogo_err = $data_inizio_err = $data_fine_err = $costo_ingresso_err = $numero_visitatori_attesi_err = $categoria_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate nome
    $input_nome = trim($_POST["nome"]);
    if(empty($input_nome)){
        $nome_err = "Please enter the name of the fair.";
    } elseif(!filter_var($input_nome, FILTER_SANITIZE_STRING)){
        $nome_err = "Please enter a valid name.";
    } else{
        $nome = $input_nome;
    }
    
    // Validate luogo
    $input_luogo = trim($_POST["luogo"]);
    if(empty($input_luogo)){
        $luogo_err = "Please enter the location.";     
    } else{
        $luogo = $input_luogo;
    }
    
    // Validate data_inizio
    $input_data_inizio = trim($_POST["data_inizio"]);
    if(empty($input_data_inizio)){
        $data_inizio_err = "Please enter the start date.";
    } elseif(!strtotime($input_data_inizio)){
        $data_inizio_err = "Please enter a valid date format.";
    } else{
        $data_inizio = $input_data_inizio;
    }

    // Validate data_fine
    $input_data_fine = trim($_POST["data_fine"]);
    if(empty($input_data_fine)){
        $data_fine_err = "Please enter the end date.";
    } elseif(!strtotime($input_data_fine)){
        $data_fine_err = "Please enter a valid date format.";
    } else{
        $data_fine = $input_data_fine;
    }

    // Validate costo_ingresso
    $input_costo_ingresso = trim($_POST["costo_ingresso"]);
    if(empty($input_costo_ingresso)){
        $costo_ingresso_err = "Please enter the entrance cost.";
    } elseif(!is_numeric($input_costo_ingresso)){
        $costo_ingresso_err = "Please enter a valid number.";
    } else{
        $costo_ingresso = $input_costo_ingresso;
    }

    // Validate numero_visitatori_attesi
    $input_numero_visitatori_attesi = trim($_POST["numero_visitatori_attesi"]);
    if(empty($input_numero_visitatori_attesi)){
        $numero_visitatori_attesi_err = "Please enter the expected number of visitors.";
    } elseif(!ctype_digit($input_numero_visitatori_attesi)){
        $numero_visitatori_attesi_err = "Please enter a positive integer value.";
    } else{
        $numero_visitatori_attesi = $input_numero_visitatori_attesi;
    }

    // Validate categoria
    $input_categoria = trim($_POST["categoria"]);
    if(empty($input_categoria)){
        $categoria_err = "Please enter the category.";     
    } elseif(!filter_var($input_categoria, FILTER_SANITIZE_STRING)){
        $categoria_err = "Please enter a valid category.";
    } else{
        $categoria = $input_categoria;
    }

    // Check input errors before inserting in database
    if(empty($nome_err) && empty($luogo_err) && empty($data_inizio_err) && empty($data_fine_err) && empty($costo_ingresso_err) && empty($numero_visitatori_attesi_err) && empty($categoria_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Fiera (nome, luogo, data_inizio, data_fine, costo_ingresso, numero_visitatori_attesi, categoria) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssdds", $param_nome, $param_luogo, $param_data_inizio, $param_data_fine, $param_costo_ingresso, $param_numero_visitatori_attesi, $param_categoria);
            
            // Set parameters
            $param_nome = $nome;
            $param_luogo = $luogo;
            $param_data_inizio = $data_inizio;
            $param_data_fine = $data_fine;
            $param_costo_ingresso = $costo_ingresso;
            $param_numero_visitatori_attesi = $numero_visitatori_attesi;
            $param_categoria = $categoria;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: indexfiera.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add fair record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nome Fiera</label>
                            <input type="text" name="nome" class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
                            <span class="invalid-feedback"><?php echo $nome_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Luogo</label>
                            <input type="text" name="luogo" class="form-control <?php echo (!empty($luogo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $luogo; ?>">
                            <span class="invalid-feedback"><?php echo $luogo_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Data Inizio</label>
                            <input type="date" name="data_inizio" class="form-control <?php echo (!empty($data_inizio_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $data_inizio; ?>">
                            <span class="invalid-feedback"><?php echo $data_inizio_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Data Fine</label>
                            <input type="date" name="data_fine" class="form-control <?php echo (!empty($data_fine_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $data_fine; ?>">
                            <span class="invalid-feedback"><?php echo $data_fine_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Costo Ingresso</label>
                            <input type="text" name="costo_ingresso" class="form-control <?php echo (!empty($costo_ingresso_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $costo_ingresso; ?>">
                            <span class="invalid-feedback"><?php echo $costo_ingresso_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Numero Visitatori Attesi</label>
                            <input type="text" name="numero_visitatori_attesi" class="form-control <?php echo (!empty($numero_visitatori_attesi_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $numero_visitatori_attesi; ?>">
                            <span class="invalid-feedback"><?php echo $numero_visitatori_attesi_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Categoria Fiera</label>
                            <input type="text" name="categoria" class="form-control <?php echo (!empty($categoria_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $categoria; ?>">
                            <span class="invalid-feedback"><?php echo $categoria_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="indexfiera.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
