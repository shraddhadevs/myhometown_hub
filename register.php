<?php
session_start();
include("db.php");

if(
    isset($_POST['fullname']) &&
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['village']) &&
    isset($_POST['pincode'])
) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $village = mysqli_real_escape_string($conn, $_POST['village']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    //1. check email first
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0) {
        // if the email already exist redirect the user back to the registration page 
        echo "<script>
                alert('Email Already Registered! Please use another email.');
                window.location='../register.html';
              </script>";
    } else {
        // 2. if the email does not already exist, insert the new users data into the database
        $sql = "INSERT INTO users (fullname, email, password, village, pincode) 
                VALUES ('$fullname', '$email', '$password', '$village', '$pincode')";

        if(mysqli_query($conn, $sql)) {
            // after successful registration, redirect the user to index.html
            echo "<script>
                    alert('Registration Successful! Please Login.');
                    window.location='../index.html';
                  </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    echo "All fields are required!";
}
?>