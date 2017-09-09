<!DOCTYPE html>
    <html>
        <head>
            <title>Online Calendar</title>
            <!--jQuery-->
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" type="text/javascript"></script>
            <!--<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>-->
            <!--<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />-->
            <!--Calendar Helper-->
            <script src="http://classes.engineering.wustl.edu/cse330/content/calendar.min.js" type="text/javascript"></script>
            <link rel="stylesheet" type="text/css" href="style.css"/>
            <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
            <link href="https://fonts.googleapis.com/css?family=Zilla+Slab" rel="stylesheet">                
        </head>
        <body>
             <?php
                ini_set("session.cookie_httponly", 1);
                session_start();
                $username = $_SESSION['username'];
                $token = $_SESSION['token'];
                //echo htmlspecialchars($token);
                echo "
                    <button id='logout_btn'>Logout</button>You are logged in as $username.
                    <h1>Calendar</h1>
                    <input type='hidden' name='token' id='token' value='$token'/>
                    <div id='new-event-form'>
                        <h3 id='createevent-title'>Create Event</h3>
                        
                        Title <input type='text' id='newevent-title'/>
                        Date <input type='datetime-local' id='newevent-date'/>
                        Category:
                        <input type='radio' name='newevent-tag' value='work' checked='checked'/> Work
                        <input type='radio' name='newevent-tag' value='personal'/> Personal
                        <input type='radio' name='newevent-tag' value='appointment'/> Appointment
                        <input type='radio' name='newevent-tag' value='reminder'/> Reminder
                        <button id='newevent_btn'>Create Event</button>
                    </div>

                
                ";
                
            ?>
            <div id="calendar-div">
                <h2 id="calendar-title"></h2>
                <button id="prevmonth_btn">Previous Month</button>
                <button id="nextmonth_btn">Next Month</button>
                Filter Events:
                <input type='checkbox' name='select-tag' value='work'>Work
                <input type='checkbox' name='select-tag' value='personal'>Personal
                <input type='checkbox' name='select-tag' value='reminder'>Reminder
                <input type='checkbox' name='select-tag' value='appointment'>Appointment
                <button id="filter_btn">Update!</button>
                <table id="calendar-table"> </table>
            </div>
            <h3>Events this month:</h3>
            <div id='event-div'>
                <div id="event-list">
                </div>
                <button id='manage_events'>Edit!</button>
                <button id='delete_event'>Delete!</button>
                <button id='share_event'>Share!</button>
            </div>
            <div id="dialog"></div>
            <!--<input type='hidden' name='token' value='$token'/>-->
            
            <script type="text/javascript" src="cal.js"></script>
            <script type="text/javascript" src="newevent_ajax.js"></script>
            <script type="text/javascript">
          </script>
        </body>
        
    </html>