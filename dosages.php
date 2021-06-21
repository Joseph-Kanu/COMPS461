<?php
include "database_connection.php";
include "functions.php";
session_start();
$errors = array();
$success = array();

if (!isset($_SESSION['loggedIn'])) {
    header("location: login.php");
}

$query = "SELECT * FROM medicine WHERE user_ID = " . $_SESSION['user_id'] . "";
$result = mysqli_query($con, $query);

$user_id = intval($_SESSION["user_id"]);

if (isset($_POST["saveDosageRecord"])) {

    $medicine_id = $_POST["medicine_id"];
    $date_taken = $_POST["date_taken"];
    $time_taken = $_POST["time_taken"];

    $query = "INSERT INTO tbl_dosages(medicine_id,user_id, date_taken, time_taken)
        VALUES(?,?,?,?)";

    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("iiss", $medicine_id, $user_id, $date_taken, $time_taken);

        if ($stmt->execute()) {
            $success[] = "Dosage Saved Successfully";
        } else {
            $errors[] = "Error Saving dosage";
        }
    } else {
        $errors[] = "Interal Server Error";

    }
}

$query = 'SELECT dosage_id,medicine_name,date_taken,time_taken FROM tbl_dosages inner join medicine on tbl_dosages.medicine_id = medicine.ID WHERE tbl_dosages.user_id = ' . $user_id . '';
$dosage_result = mysqli_query($con, $query);

// if ($result) {

// } else {
//     echo '<h4 class="text-mute text-center">No Dosage Record Found</h4>';
// }

?>
<?php set_header("Track Dosage")?>

<div class="container">
    <div class="row mt-2">
        <div class="col-md-5 p-3">
            <h4 class="display-4">Save New Dosage</h4>
            <?php
if (isset($errors) && !empty($errors)) {
    foreach ($errors as $alert) {
        echo '  <div class="alert alert-danger" role="alert">
                ' . $alert . '
                </div>';

    }
}

if (isset($success) && !empty($success)) {
    foreach ($success as $msg) {
        echo '  <div class="alert alert-success" role="alert">
                ' . $msg . '
                </div>';

    }
}

?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="">Medicine Name</label>
                    <select name="medicine_id" id="medicine_id" class="form-select" required>
                        <?php
while ($row = mysqli_fetch_array($result)) {
    echo "<option value='" . $row['ID'] . "'>" . $row['medicine_name'] . "</option>";
}
?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Date Taken</label>
                    <input type="date" name="date_taken" id="date_taken" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Time Taken</label>
                    <input type="time" name="time_taken" id="time_taken" required class="form-control">
                </div>

                <input type="submit" name="saveDosageRecord" value="Save" class="btn btn-success my-2 btn-block">
            </form>
        </div>

        <div class="col-md-7 ">

            <h4 class="text-center display-4">All Dosages</h4>
            <table class="table">
                <thead class="thead-dark">
                    <tr>

                        <th scope="col">Medicine Name</th>
                        <th scope="col">Date Taken</th>
                        <th scope="col">Time Taken</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
if ($dosage_result != null) {

    while ($row = mysqli_fetch_array($dosage_result)) {
        echo ' <tr>

                        <td>' . $row["medicine_name"] . '</td>
                        <td>' . $row["date_taken"] . '</td>
                        <td>' . $row["time_taken"] . '</td>
                    </tr>';

    }

}

?>


                </tbody>
            </table>
        </div>
    </div>
</div>
<?php set_footer()?>