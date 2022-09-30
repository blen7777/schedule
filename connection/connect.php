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

if(isset($_POST['action']) and $_POST['action'] == 'scheduleMeeting')
{
    $meeting_name = $_POST['meeting_name'] != '' ? $_POST['meeting_name'] : '0';
    $start_time = $_POST['start_time'] != '' ? $_POST['start_time'] : '0';
    $end_time = $_POST['end_time'] != '' ? $_POST['end_time'] : '0';
    $users = $_POST['users'] != '' ? $_POST['users'] : '0';
    if($meeting_name == '0' or $start_time == '0' or $end_time == '0' or $users == '0')
    {
        echo "All data is required";
    }else
    {
        schedule_meeting($users, $meeting_name, $start_time, $end_time);
    }
   
}
// Function to save meeting in to database
function save_register($user, $start_time, $end_time, $meeting_name)
{
    $conn = db();

    $sql = "INSERT INTO meetings (user_id, start_time, end_time, meeting_name) VALUES ($user, '$start_time', '$end_time', '$meeting_name')";
    if (mysqli_query($conn, $sql)) {
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
}

// function to detect conflict with another meetings
function detect_conflict($user_id, $start_time, $end_time)
{
    $conn = db();
    $user_conflited = "";
    $dateTime = new DateTime($start_time);
    $dateTimeEnd = new DateTime($end_time);
    $sql = "SELECT * FROM meetings WHERE user_id = {$user_id} AND start_time BETWEEN '".$dateTime->format('Y-m-d H:i:s')."' AND '".$dateTimeEnd->format('Y-m-d H:i:s')."' OR end_time BETWEEN '".$dateTime->format('Y-m-d H:i:s')."' AND '".$dateTimeEnd->format('Y-m-d H:i:s')."' LIMIT 1";
    //echo $sql; die();
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0)
    {
        while($data = mysqli_fetch_assoc($result))
        {
            $user_conflited = "User {$user_id} has a conflicting meeting: {$data['meeting_name']}";
            return $user_conflited;
        }
    }
    return $user_conflited;
}

// Function to schedule a meetings
function schedule_meeting($users, $meeting_name, $start_time, $end_time)
{
    if(empty($users))
        exit();

    $users = explode(',', $users);
    $user_conflicts = array();
    foreach ($users as $user) 
    {
        $flag_conflict = detect_conflict($user, $start_time, $end_time);
        if($flag_conflict != "")
        {
            $user_conflicts[] = $flag_conflict;
        }
        else
        {
            $startTime = new DateTime($start_time);
            $endTime = new DateTime($end_time);
            save_register($user, $startTime->format('Y-m-d H:i:s'), $endTime->format('Y-m-d H:i:s'), $meeting_name);
        }
    }

    if(count($user_conflicts) > 0)
    {
        foreach ($user_conflicts as $conflict)
        {
            echo $conflict."<br>";
        }
        echo "The meeting has not been booked";
    }
    else{
        echo "The meeting was booked!";
    }
}
?>