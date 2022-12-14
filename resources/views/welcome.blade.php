<!DOCTYPE html>
<html class="full" lang="en">
<head>
    <title>Google Calendar API</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/Calendar.css" rel="stylesheet" />
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js"></script>
	
    <script type="text/javascript">
        // date variables
        var now = new Date();
        
        today = now.toISOString();
        
        var twoHoursLater = new Date(now.getTime() + (2 * 1000 * 60 * 60));
        twoHoursLater = twoHoursLater.toISOString();

        // Google api console clientID and apiKey 
        var clientId = '213354085712-mba08e7rndvauh8lp5ebghhikjvvdtce.apps.googleusercontent.com';
        var apiKey = 'AIzaSyBqnyYR6f0_eRebRIAAFDjHlH2lEeNgwg0';

        // enter the scope of current project (this API must be turned on in the Google console)
        var scopes = 'https://www.googleapis.com/auth/calendar';

        // OAuth2 functions
        function handleClientLoad() {
            gapi.client.setApiKey(apiKey);
            window.setTimeout(checkAuth, 1);
        }

        function checkAuth() {
            gapi.auth.authorize({ client_id: clientId, scope: scopes, immediate: true }, handleAuthResult);
        }
        // alert(authResult);
        // show/hide the 'authorize' button, depending on application state
        function handleAuthResult(authResult) {
            var authorizeButton = document.getElementById('authorize-button');
            var eventButton = document.getElementById('btnCreateEvents');
            var resultPanel = document.getElementById('result-panel');
            var resultTitle = document.getElementById('result-title');

            if (authResult && !authResult.error) {
                authorizeButton.style.visibility = 'hidden'; 		// if authorized, hide button
                resultPanel.className = resultPanel.className.replace(/(?:^|\s)panel-danger(?!\S)/g, '')	// remove red class
                resultPanel.className += ' panel-success'; 			// add green class
                resultTitle.innerHTML = 'Application Authorized'		// display 'authorized' text
                eventButton.style.visibility = 'visible';
                $("#txtEventDetails").attr("visibility", "visible");
            } else {													// otherwise, show button
                authorizeButton.style.visibility = 'visible';
                $("#txtEventDetails").attr("visibility", "hidden");
                eventButton.style.visibility = 'hidden';
                resultPanel.className += ' panel-danger'; 			// make panel red
                authorizeButton.onclick = handleAuthClick; 			// setup function to handle button click
            }
        }

        // function triggered when user authorizes app
        function handleAuthClick(event) {
            gapi.auth.authorize({ client_id: clientId, scope: scopes, immediate: false }, handleAuthResult);
            return false;
        }

        function refreshICalendarframe() {
            var iframe = document.getElementById('divifm')
            iframe.innerHTML = iframe.innerHTML;
        }
        // setup event details
        
        // function load the calendar api and make the api call
        function makeApiCall() {
            var eventResponse = document.getElementById('event-response');
           
            gapi.client.load('calendar', 'v3', function () {			// load the calendar api (version 3)
                var request = gapi.client.calendar.events.insert
                ({
                    'calendarId': 'ruhi83076@gmail.com', // calendar ID
                    "resource": resource							// pass event details with api call
                });
                
                // handle the response from our api call
                request.execute(function (resp) {
                    if (resp.status == 'confirmed') {
                        eventResponse.innerHTML = "Event created successfully. View it <a href='" + resp.htmlLink + "'>online here</a>.";
                        eventResponse.className += ' panel-success';
                        refreshICalendarframe();
                    } else {
                        document.getElementById('event-response').innerHTML = "There was a problem. Reload page and try again.";
                        eventResponse.className += ' panel-danger';
                    }
                });
            });
        }
        console.log(resource);
        var resource = {
            "summary": "My Event",
			"start": {
                "dateTime": today
            },
            "end": {
                "dateTime": twoHoursLater
            },
            "description":"We are organizing events",
            "location":"US",
            "attendees":[
			{
					"email":"xyz@gmail.com",
					"displayName":"Shaveta",
					"organizer":true,
					"self":false,
					"resource":false,
					"optional":false,
					"responseStatus":"needsAction",
					"comment":"This is event first",
					"additionalGuests":3
					
			},
			{	
				"email":"abc@gmail.com",
					"displayName":"Chatak",
					"organizer":true,
					"self":false,
					"resource":false,
					"optional":false,
					"responseStatus":"needsAction",
					"comment":"This is office event",
					"additionalGuests":3
			}
			],
		};

		// FUNCTION TO DELETE EVENT
	   function deleteEvent() {
		 gapi.client.load('calendar', 'v3', function() {  
		   var request = gapi.client.calendar.events.delete({
			 'calendarId': 'ruhi83076@gmail.com',
			 'eventId': 'Hdusrtsbs8'
		   });
		 request.execute(function(resp) {
			if (resp.status == 'confirmed') {
				alert("Event was successfully removed from the calendar!");
			}
			else{
				alert('An error occurred, please try again later.')
			}
		 });
		 });
	   } 
 

    </script>
    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad" type="text/javascript"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">
        <div class="container">
            <div class="navbar-header">
                
                <a class="navbar-brand" href="#">Google Calendar API</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">Simple Way to embed you calendar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-12">
                <button id="authorize-button" style="visibility: hidden" class="btn btn-primary">
                    Authorize</button>
            </div>
            <!-- .col -->
            <div class="col-md-10 col-sm-10 col-xs-12">
                <div class="panel panel-danger" id="result-panel">
                    <div class="panel-heading">
                        <h1>
                            My Calendar</h1>
                        <h3 class="panel-title" id="result-title">
                            Application Not Authorized</h3>
                        &nbsp;
                        <p>
                            Insert Event into Public Calendar&hellip;</p>
                    </div>
                </div>
                       <!--  <input id="txtEventDetails" type="text" /> -->
                <button id="btnCreateEvents" class="btn btn-primary" onclick="makeApiCall();">
                    Create Events</button>  
				<button id="btnDeleteEvents" class="btn btn-primary" onclick="deleteEvent();">
                    Delete Events</button> 					
                <div id="event-response">
                   
                </div>
                <div id="divifm">
                <iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=Asia%2FKolkata&src=cnVoaTgzMDc2QGdtYWlsLmNvbQ&src=YWRkcmVzc2Jvb2sjY29udGFjdHNAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&src=ZW4uaW5kaWFuI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%23039BE5&color=%2333B679&color=%230B8043" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
