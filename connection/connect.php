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

    //Convert dates to datime PHP and validate the diff in minutes
    $start_time = new DateTime($start_time);
    $end_time = new DateTime($end_time);
    $interval = $start_time->diff($end_time);
    $diffMinutes = $interval->i;
    $diffhours = $interval->h;
    //echo $diffhours;

    if($meeting_name == '0' or $start_time == '0' or $end_time == '0' or $users == '0')
    {
        echo "All data is required";
    }
    elseif($start_time->format('Y-m-d H:i:s') >= $end_time->format('Y-m-d H:i:s'))
    {
        echo "The end time must be greater than the start time";
    }
    elseif($diffMinutes < 15 and $diffhours == 0)
    {
        echo "The minimum space for a meeting is 15 minutes";
    }else
    {
        schedule_meeting($_POST['users'], $meeting_name, $start_time, $end_time);
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
    $start_time->setTime($start_time->format('H'), $start_time->format('i')+1,$start_time->format('s'));
    $conn = db();
    $user_conflited = "";
    $sql = "SELECT * FROM meetings WHERE user_id = {$user_id} AND (start_time BETWEEN '".$start_time->format('Y-m-d H:i:s')."' AND '".$end_time->format('Y-m-d H:i:s')."' OR end_time BETWEEN '".$start_time->format('Y-m-d H:i:s')."' AND '".$end_time->format('Y-m-d H:i:s')."') LIMIT 1";
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
    //echo $users;
    $users = explode(',', $users);
    $user_conflicts = array();

    // This foreach will validate that the user is not already in the meeting list
    foreach ($users as $user) 
    {
        $flag_conflict = detect_conflict($user, $start_time, $end_time);
        if($flag_conflict != "")
        {
            $user_conflicts[] = $flag_conflict;
        }
    }

    // Function save users in the calendar or send the conflicting messages
    if(count($user_conflicts) > 0)
    {
        foreach ($user_conflicts as $conflict)
        {
            echo $conflict."<br>";
        }
        echo "The meeting has not been booked";
    }
    else{
        foreach($users as $userAdd)
        {
            save_register($userAdd, $start_time->format('Y-m-d H:i:s'), $end_time->format('Y-m-d H:i:s'), $meeting_name);
        }
        echo "200";
    }
}
?>