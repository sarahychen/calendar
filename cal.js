/*----- on the first page load ----*/

// Get today's information
var today = new Date();
var dd= today.getDate();
var mm = today.getMonth();
var yyyy = today.getFullYear();
var currentMonth = new Month(yyyy, mm);
var temp = "personal-work-reminder-appointment";
//alert("The current month is " + currentMonth.month+" "+currentMonth.year);
drawCalendar(temp);

// add event listeners for previous and next month
document.getElementById("nextmonth_btn").addEventListener("click", function(event){
    currentMonth = currentMonth.nextMonth();
    drawCalendar(temp);
    //alert("The current month is " + currentMonth.month+" "+currentMonth.year);
    }, false);
document.getElementById("prevmonth_btn").addEventListener("click", function(event){
    currentMonth = currentMonth.prevMonth();
    drawCalendar(temp);
    //alert("The current month is " + currentMonth.month+" "+currentMonth.year);
    }, false);

document.getElementById("manage_events").addEventListener("click", getEventInfo, false);
document.getElementById("delete_event").addEventListener("click", deleteEvent, false);
document.getElementById("filter_btn").addEventListener("click", filterEvents, false);
document.getElementById("share_event").addEventListener("click", shareEvent, false);


/*----- FUNCTION: drawCalendar  ----*/
function drawCalendar(checkedstr) {
    //var events = loadEvents();
    //console.log(events);
    // remove all children nodes of the calendar table
    var tableNode = document.getElementById("calendar-table");
    while (tableNode.firstChild) {
        tableNode.removeChild(tableNode.firstChild);
    }
    var titleNode = document.getElementById("calendar-title");
    while (titleNode.firstChild) {
        titleNode.removeChild(titleNode.firstChild);
    }
    // constants
    var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    // add the Month's name as a title to h1
    var monthTitle = document.createTextNode(monthNames[currentMonth.month] + ", " + currentMonth.year);
    document.getElementById("calendar-title").appendChild(monthTitle);
    // draw the names of the week
    var newtr = document.createElement("tr");
    for (var j = 0; j<7; j++){
        var newth = document.createElement("th");
        newth.appendChild(document.createTextNode(dayNames[j]));
        newtr.appendChild(newth);
    }
    document.getElementById("calendar-table").appendChild(newtr);
    // draw the current months' calendar
    var weeks = currentMonth.getWeeks();
    var daysbefore = 0;
    var daysafter = 0;
    var calendar = [];
    var c = 0;
    for(var w in weeks){
        var days = weeks[w].getDates();
        var newtr = document.createElement("tr");
        for(var d in days){
            //console.log(days[d].toISOString());
            if (days[d].getMonth() == (currentMonth.month-1)) {
                daysbefore++;
                var newtd = document.createElement("td");
                var classatt = document.createAttribute("class");
                classatt.value = "prev-month";
                newtd.setAttributeNode(classatt);
                newtd.appendChild(document.createTextNode("prev month"));
                newtr.appendChild(newtd);
            }
            if (days[d].getMonth() == currentMonth.month){
                calendar[c] = days[d].getDate();
                var newtd = document.createElement("td");
                newtd.setAttribute("class", "curr-month");
                var newtext = document.createTextNode(calendar[c]);
                var todayatt = document.createAttribute("class");
                todayatt.value = "today-date";
                var newid = document.createAttribute("id");
                newid.value = "date-dialog-"+calendar[c];
                newtd.setAttributeNode(newid);
                newtd.appendChild(newtext);
                if ((calendar[c] == dd) && (days[d].getMonth() == mm) && (days[d].getYear())) {
                    //newtd.setAttributeNode(todayatt);
                    newtd.setAttribute("class", "curr-month today-date");
                    newtd.appendChild(document.createTextNode(" -Today-"));
                }
                newtr.appendChild(newtd);
                c++;
            }
            if (days[d].getMonth() == (currentMonth.month+1)) {
                daysafter++;
                var newtd = document.createElement("td");
                var classatt = document.createAttribute("class");
                classatt.value = "next-month";
                newtd.setAttributeNode(classatt);
                newtd.appendChild(document.createTextNode("next month"));
                newtr.appendChild(newtd);
            }
        }
        document.getElementById("calendar-table").appendChild(newtr);
    }

//}

/* ---- FUNCTION: LOAD events ----*/
//function loadEvents(checkedstr) {
    var month = currentMonth.month;
    var year = currentMonth.year;
    var dataString = "month=" + encodeURIComponent(month) + "&year=" + encodeURIComponent(year);
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "loadevents_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests

	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
            var result = jsonData.result;
            console.log(result);
            var eventNode = document.getElementById("event-list");
            while (eventNode.firstChild) {
                eventNode.removeChild(eventNode.firstChild);
            }
            for (var i = 0; i<result.length;i++){
                var p = document.createElement("p");
                p.setAttribute("class", result[i].tags + " event");
                var text = "'"+result[i].title+"'"+" on "+result[i].day+" at "+result[i].time;
                if (result[i].sharedby !== "") {
                    text += " shared by " + result[i].sharedby;
                }
                p.innerHTML += "<input type='radio' name='event-items' value='"+result[i].id+"'/>"+text;
                document.getElementById("event-list").appendChild(p);
                
                var children = $('#calendar-table').find('*').toArray();
                for (var t=0; t< children.length; t++) {
                    var pattern = "\\d+";
                    var str = children[t].id;
                    var matched = str.match(pattern);
                    if (matched !== null) {
                        if (matched == result[i].day) {
                            document.getElementById(children[t].id).className += " has-event";
                            var newp = document.createElement("p");
                            newp.setAttribute("class", result[i].tags + " event");
                            var newtext = document.createTextNode(result[i].time + "  " + result[i].title);
                            newp.appendChild(newtext);
                            document.getElementById(children[t].id).appendChild(newp);
                        }
                    }
                    
                }
            }
            var matches = checkedstr.match(/\w+/g);
            var matchstr="";
            for (var m=0; m < matches.length; m++) {
                matchstr += matches[m];
            }
            var eventslist = document.getElementsByClassName("event");
            //for (var m=0; m<matches.length; m++) {
                for (var e= 0; e < eventslist.length; e++) {
                    var classname = eventslist[e].className;
                    var eventreplace = classname.replace(" event", "");
                    if (matchstr.match(eventreplace) === null) {
                        eventslist[e].style.visibility = "hidden"; 
                    }
                }
            //}
		} else{
			alert("Could not access events"+jsonData.message);
			//console.log(jsonData.success);
		}
		
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data  
}


/* --------- FUNCTION getevent info/ manage events ------*/
function getEventInfo(event) {
    console.log("hello");
    var eventid = null;
    var radio = document.getElementsByName("event-items");
    for(var i=0; i<radio.length; i++){
        if(radio[i].checked){
            eventid = radio[i].value;  
            break;            
        }    
    }
    var token = document.getElementById("token").value;
    
    var dataString = "id=" + encodeURIComponent(eventid) + "&token=" + encodeURIComponent(token);
    
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "getevent_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests

	xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText);
        if (jsonData.success) {
            var dialogNode = document.getElementById("dialog");
            while (dialogNode.firstChild) {
                dialogNode.removeChild(dialogNode.firstChild);
            }
            var title = jsonData.title;
            var day = jsonData.day;
            var month = jsonData.month;
            var year = jsonData.year;
            var time = jsonData.time;
            var tag = jsonData.tag;
            var linkedid = jsonData.linkedid;
            
            var etitle = prompt("Title", title);
            var eday = prompt("Day", day);
            var emonth = prompt("Month", month);
            var eyear = prompt("Year", year);
            var etime = prompt("Time", time);
            var etag = prompt("Tag: reminder, work, appointment, personal", tag);
            
            if ((etag !== 'reminder') && (etag !== 'work') && (etag !== 'appointment') && (etag !== 'personal') ) {
                alert('Please enter one of the provided tags: reminder, work, appointment, personal');
            }
            
            if ((etitle !== null) && (eday !== null) && (emonth !== null) && (eyear !== null) &&
                (etime !== null) && (etag !== null)) {
                sendUpdate(etitle, eday, emonth, eyear, etime, etag, eventid, linkedid);
            }
        } else {
            alert("Could not edit event:" + jsonData.message);
        }
        
    });
    xmlHttp.send(dataString);
    //alert(event);
}

/*- ------ FUNCTION send the updated information, update php and mysql, edit event ------*/
function sendUpdate(etitle, eday, emonth, eyear, etime, etag, id, linkedid) {
    console.log(etitle);
    console.log(id);
    //var link = 0;
    //if (linkedid !== ""){
    //    link = linkedid;
    //}
    var dataString = "title=" + encodeURIComponent(etitle) + "&day=" + encodeURIComponent(eday) +
    "&month=" + encodeURIComponent(emonth)+"&year=" + encodeURIComponent(eyear) + "&time=" + encodeURIComponent(etime) +
    "&tag=" + encodeURIComponent(etag)+ "&id=" + encodeURIComponent(id) + "&linkedid=" + encodeURIComponent(linkedid);
    var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "editevent_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText);
        if (jsonData.success) {
            alert("Okay! You're event is now: " + jsonData.message);
            window.location.replace("calendar.php");
            //window.location.referesh();
            //return;
        } else {
            alert("fail");
            //return;
        }
        });
    xmlHttp.send(dataString);
}

/* ---- FUNCTION: delete event ------ */
function deleteEvent(args) {
    var eventid = null;
    var radio = document.getElementsByName("event-items");
    for(var i=0; i<radio.length; i++){
        if(radio[i].checked){
            eventid = radio[i].value;  
            break;            
        }    
    }
    var token = document.getElementById("token").value;
    var dataString = "id=" + encodeURIComponent(eventid) + "&token=" + encodeURIComponent(token);
    
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "deleteevent_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests

	xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText);
        if (jsonData.success) {
            alert("Event deleted!");
             window.location.replace("calendar.php");
             //location.refresh();
        } else{
            alert("Could not delete event.");
        }   
        
    });
    xmlHttp.send(dataString);
}

function filterEvents(args) {
    //get checked boxes
    var checked = "";
    var checkbox = document.getElementsByName("select-tag");
    for (var i=0; i<checkbox.length; i++) {
        if (checkbox[i].checked) {
            checked += checkbox[i].value + "\-";
        }
    }
    drawCalendar(checked);
    //window.location.replace('calendar.php');
    
}

function shareEvent(args) {
    var eventid = null;
    var radio = document.getElementsByName("event-items");
    for(var i=0; i<radio.length; i++){
        if(radio[i].checked){
            eventid = radio[i].value;  
            break;            
        }    
    }
    var token = document.getElementById("token").value;
    var sharer = prompt("Enter the username of the user you want to share the event with:");
    console.log(sharer);
    var dataString = "eventid=" + encodeURIComponent(eventid) + "&token=" + encodeURIComponent(token) + "&sharer=" + encodeURIComponent(sharer);
    
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "shareevent_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests

	xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText);
        if (jsonData.success) {
            alert("Ok, event shared with " + sharer);
             window.location.replace("calendar.php");
        } else{
            alert("Could not share event." + jsonData.message);
        }   
        
    });
    xmlHttp.send(dataString);
    
}




// logout stuff ----------------------------------------------------------------------------
document.getElementById("logout_btn").addEventListener("click", logoutAjax, false);

function logoutAjax(event) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", "logout.php", true);
    xmlHttp.addEventListener("load", function(event) {
        var jsonData = JSON.parse(event.target.responseText);
        if (jsonData.success) {
            alert("You have been logged out!");
            window.location.replace("home.html");
        }
    }, false);
    xmlHttp.send(null);
     
}


