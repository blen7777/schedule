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

function save_register($user, $start_time, $end_time, $meeting_name)
{
    $conn = db();
    $sql = "INSERT INTO meetings (user_id, start_time, end_time, meeting_name) VALUES ($user, $start_time, $end_time, $meeting_name)";
    if (mysqli_query($conn, $sql)) {
        echo "Record saved successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}

function detect_conflict($user_id, $start_time)
{
    $conn = db();

    $date = date_create($start_time);
    $sql = "SELECT * FROM meetings WHERE user_id = {$user_id} AND start_time = {date_format($date,'Y-m-d H:i:s')}";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) { 

        mysqli_close($conn);
        return true;
    }
    
    mysqli_close($conn);
    return false;
}

// Function to schedule a meetings
function schedule_meeting($meeting_name, $start_time, $end_time, $users)
{
    $user_conflicts = array();
    foreach ($users as $user) 
    {
        if(detect_conflict($user, $start_time))
        {
            $user_conflicts[] = $user;
        }
        else
        {
            save_register($user, $start_time, $end_time, $meeting_name);
        }
    }

    if(count($user_conflicts) > 0)
    {
        foreach ($user_conflicts as $conflict)
        {
            echo "User {$conflict} has a conflicting meeting: {$meeting_name}<br>";
            echo "The meeting has not been booked";
        }
    }
    else{
        echo "All meetings were booked";
    }
}
?>