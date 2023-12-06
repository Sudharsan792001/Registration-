<?php
$uname1 = $_POST['Username'];
$email  = $_POST['Email'];
$upswd1 = $_POST['Password'];


if (!empty($uname1) || !empty($email) || !empty($upswd1) )
{
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "project";

    // Create connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    if (mysqli_connect_error()){
        die('Connect Error ('. mysqli_connect_errno() .') ' . mysqli_connect_error());
    }
    else {
        $SELECT = "SELECT email FROM register WHERE email = ? LIMIT 1";
        $INSERT = "INSERT INTO register (Username, Email, Password) VALUES (?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s",  $email);
       // Corrected bind_param line
       // $stmt->bind_param("ss", $uname1, $email);


        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        // Checking username
        if ($rnum == 0) {
            $stmt->close();

            // Hash the password before storing it
            $hashed_password = password_hash($upswd1, PASSWORD_DEFAULT);

            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("sss", $uname1, $email, $hashed_password);
            $stmt->execute();
            echo "Submitted Successfully";
        } else {
            echo "Email is already in use";
        }
        $stmt->close();
        $conn->close();
    }
} else {
    echo "All fields are required";
    die();
}
?>
