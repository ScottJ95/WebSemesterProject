const userNameChats = getSessionUsername();
const groupIDChats = urlSample = window.location.href.split('=')[1];
getNumMembers();


function getSessionUsername(){//function that gets the current users name
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getSessionVar'},

                   success: function (response) {                   
				return response;
                   }

                });

}

function getNumMembers(){//function adds the number of members to the 
						// userbar on the side
	$.ajax({

                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getGroupUserList', argument: [groupIDChats]},
                   success: function (response) { 
				   var result = JSON.parse(response);
				   members(response);
				document.getElementById("numMem").innerHTML=" "+result.length;
                   }

                });

}
function getEmailFromUserID(user) { //function adds the appropriate source to the calendar 
    $.ajax({
        type: 'POST',
        url: 'DBFuncs.php',
        data: {
            functionName: 'getCreatorEmail',
            argument: [user]
        },

        success: function(response) {
			if(document.getElementById("calendar").getAttribute("src") == undefined)
				document.getElementById("calendar").setAttribute("src", "https://calendar.google.com/calendar/embed?src=" + response + "&ctz=America/New_York");
        }
    });
}

function getSource() { //function adds the appropriate source to the calendar 
    $.ajax({
        type: 'POST',
        url: 'DBFuncs.php',
        data: {
            functionName: 'getCreator',
            argument: [groupIDChats]
        },

        success: function(response) {
			getEmailFromUserID(response);
        }
    });
}

function members(members) {
	
	var memberCollection = JSON.parse(members);
	if(memberCollection.length == 0)
	{
       document.getElementById("memberList").innerHTML += "<li class=\"groupMembers\">None</li>";
	}
	else
	{
                for (i = 0; i < memberCollection.length; i++) {
                    document.getElementById("memberList").innerHTML += "<li class=\"groupMembers\">" + memberCollection[i].username + "</li>";
                }		
	}
	
}

function messages(){
	
	document.getElementById("groupContainer").innerHTML="";
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getGroupMessageList', argument:[groupIDChats]},

                   success: function (response) {
				var messageCollection = JSON.parse(response);
				window.alert(messageCollection);

			   if(messageCollection.length == 0)
				   window.alert(messageCollection);
				   //window.location.href = "login.html";
			   else
			   {
                        var messages = JSON.parse(response);

                        for(i = 0;i<messages.length;i++)
                        {	
                                document.getElementById("groupContainer").innerHTML+= "<div class = \"post\">";
								document.getElementById("groupContainer").innerHTML+= "<div class = \"userContainer\">";
								document.getElementById("groupContainer").innerHTML+= "<div class=\"userImageChat\">";
								document.getElementById("groupContainer").setAttribute("backgroundImage","url(\"/home/jefferys0/public_html/web/WebSemesterProject/UPLOADED/archive/users/ FUCKING SAMPLE IMAGE\")");//TODO: ADD FETCH image path METHOD HERE FOR CORRECT DISPLAY
								document.getElementById("groupContainer").innerHTML+= "</div>";
								document.getElementById("groupContainer").innerHTML+= "<div class=\"userNameChat\"> FUCK THIS USERNAME </div>";//TODO: ADD FETCH USERNAME METHOD HERE FOR CORRECT DISPLAY
								document.getElementById("groupContainer").innerHTML+= "</div><br>";
								document.getElementById("groupContainer").innerHTML+= "<div class = \"messageChat\">FUCK THIS MESSAGE AREA BECAUSE ITS HARDCODED";
								document.getElementById("groupContainer").innerHTML+= "</div>"
								document.getElementById("groupContainer").innerHTML+= "</div>";
                        }
                  }
		   }

                });
}

function logout(){
	window.location.href = "login.html";
}
function editProfile(){
	window.location.href = "editProfile.php";
}
function main(){
        window.location.href = "Main.html";
}

function getUserID(){//gets current logged in username from Session
    $.ajax({
        type: 'POST',
         url:  'DBFuncs.php',
        data: { functionName:'getSessionUserID'},

        success: function (response) {                   
		return response;
        }

    });
}
function submitMessage(){
    var message = document.getElementById("inputMessage").value; //get value of message
	var userID = getUserID();  //get current UserID
	
		$.ajax({
   	    type: 'POST',
    	    url:  'DBFuncs.php',
            data: { functionName:'addMessage',argument:[groupIDChats, userID, message] },
                
            success: function (response) {
				document.getElementById("inputMessage").value="";
				messages();
			}
		
		
        });
	
}

