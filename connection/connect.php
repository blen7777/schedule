<?php

function db(){
    // In this file, the database connection is set up 
    // database parameters
    static $conn;

    $servername = "localhost";
    $database = "schedule";
    $username = "root";
    $password = "";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check the database connection

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function save_register()
{
    $conn = db();
    $sql = "INSERT INTO meetings (user_id, start_time, end_time, meeting_name) VALUES ('Test', 'Testing', 'Testing@tesing.com')";
    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}

function detect_conflict($user_id, $start_time)
{
    $conn = db();
    $sql = "SELECT * FROM meetings WHERE user_id = {$user_id} AND start_time = {$start_time}";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) { }
    mysqli_close($conn);

}

// Function to schedule a meetings
function schedule_meeting($meeting_name, $start_time, $end_time, $users)
{
    $start_time = $start_time;
    $end_time = $end_time;
    $users = $users;
    $meeting_name = $meeting_name;
    foreach ($users as $user) {
        
    }

    if(detect_conflicts($user_id, $start_time))
    {

    }

}
?>