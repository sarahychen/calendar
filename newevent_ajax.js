document.getElementById("newevent_btn").addEventListener('click', createEvent, false);

function createEvent(event) {
    // get date value
    var date = document.getElementById("newevent-date").value;
    // get token
    var token = document.getElementById("token").value;
     // finds which radio button is selected     
    var event_tag= null;
    var radio = document.getElementsByName("newevent-tag");
    for(var i=0; i<radio.length; i++){
        if(radio[i].checked){
            event_tag = radio[i].value;  
            break;            
        }    
    }
    console.log(event_tag);
    // get title
    var title = document.getElementById("newevent-title").value;
    //var dataString = "date=" + encodeURIComponent(date) + "&title=" + encodeURIComponent(title); 
    var dataString = "title=" + encodeURIComponent(title) + "&date=" + encodeURIComponent(date) + "&tag=" + encodeURIComponent(event_tag) +
        "&token=" + encodeURIComponent(token);
    console.log(dataString);
    // hi
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "newevent_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests

	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			console.log(jsonData.message);
			alert("Event Created: "+jsonData.message);
            window.location.replace("calendar.php");
		}else{
			alert("Could not create: "+jsonData.message);
			//console.log(jsonData.success);
		}
        //console.log("event");
		
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}








