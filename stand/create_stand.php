<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$nome_stand = $responsabile = $tipologia = $numero_dipendenti = $fiera_id = "";
$nome_stand_err = $responsabile_err = $tipologia_err = $numero_dipendenti_err = $fiera_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate nome_stand
    $input_nome_stand = trim($_POST["nome_stand"]);
    if (empty($input_nome_stand)) {
        $nome_stand_err = "Please enter a stand name.";
    } else {
        $nome_stand = $input_nome_stand;
    }

    // Validate responsabile
    $input_responsabile = trim($_POST["responsabile"]);
    if (empty($input_responsabile)) {
        $responsabile_err = "Please enter the responsible person.";
    } else {
        $responsabile = $input_responsabile;
    }

    // Validate tipologia
    $input_tipologia = trim($_POST["tipologia"]);
    if (empty($input_tipologia)) {
        $tipologia_err = "Please enter the stand type.";
    } else {
        $tipologia = $input_tipologia;
    }

    // Validate numero_dipendenti
    $input_numero_dipendenti = trim($_POST["numero_dipendenti"]);
    if (empty($input_numero_dipendenti)) {
        $numero_dipendenti_err = "Please enter the number of employees.";
    } elseif (!ctype_digit($input_numero_dipendenti)) {
        $numero_dipendenti_err = "Please enter a valid number.";
    } else {
        $numero_dipendenti = $input_numero_dipendenti;
    }

    // Validate fiera_id
    $input_fiera_id = trim($_POST["fiera_id"]);
    if (empty($input_fiera_id)) {
        $fiera_id_err = "Please select a fair ID.";
    } elseif (!ctype_digit($input_fiera_id)) {
        $fiera_id_err = "Please enter a valid fair ID.";
    } else {
        $fiera_id = $input_fiera_id;
    }

    // Check input errors before inserting in database
    if (empty($nome_stand_err) && empty($responsabile_err) && empty($tipologia_err) && empty($numero_dipendenti_err) && empty($fiera_id_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO Stand (nome_stand, responsabile, tipologia, numero_dipendenti, fiera_id) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssii", $param_nome_stand, $param_responsabile, $param_tipologia, $param_numero_dipendenti, $param_fiera_id);

            // Set parameters
            $param_nome_stand = $nome_stand;
            $param_responsabile = $responsabile;
            $param_tipologia = $tipologia;
            $param_numero_dipendenti = $numero_dipendenti;
            $param_fiera_id = $fiera_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Record created successfully. Redirect to landing page
                header("location: indexstand.php");
                exit();
            } else {
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
    <title>Create Stand Record</title>
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
                    <h2 class="mt-5">Create Stand Record</h2>
                    <p>Please fill this form and submit to add a stand record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Nome Stand</label>
                            <input type="text" name="nome_stand" class="form-control <?php echo (!empty($nome_stand_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome_stand; ?>">
                            <span class="invalid-feedback"><?php echo $nome_stand_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Responsabile</label>
                            <input type="text" name="responsabile" class="form-control <?php echo (!empty($responsabile_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $responsabile; ?>">
                            <span class="invalid-feedback"><?php echo $responsabile_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Tipologia</label>
                            <input type="text" name="tipologia" class="form-control <?php echo (!empty($tipologia_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tipologia; ?>">
                            <span class="invalid-feedback"><?php echo $tipologia_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Numero Dipendenti</label>
                            <input type="text" name="numero_dipendenti" class="form-control <?php echo (!empty($numero_dipendenti_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $numero_dipendenti; ?>">
                            <span class="invalid-feedback"><?php echo $numero_dipendenti_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Fiera ID</label>
                            <input type="text" name="fiera_id" class="form-control <?php echo (!empty($fiera_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fiera_id; ?>">
                            <span class="invalid-feedback"><?php echo $fiera_id_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="indexstand.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
