const userName = getSessionUsername();


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
                   data: { functionName:'getGroupUserList'},

                   success: function (response) {                   
				document.getElementById("numMem").innerHTML=" "+count(response);
                   }

                });

}

function getSource(){//function adds the appropriate source to the calendar 
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getCreator', argument:group_Id},

                   success: function (response) {                   
				document.getElementById("calendar").setAttribute("src", "https://calendar.google.com/calendar/embed?src="+response+"&ctz=America/New_York");
                   }

                });

}

function members(evt,x){
	
	document.getElementById("memberList").innerHTML="";
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getGroupUserList', argument:x},

                   success: function (response) {
			   if(response == 0)
				   window.location.href = "login.html";
			   else
			   {
                        var memberList = JSON.parse(response);

                        for(i = 0;i<memberList.length;i++)
                        {
                                document.getElementById("memberList").innerHTML+="<li class=\"groupMembers\">"+memberList[i].userName"</li>";
                        }
                  }
		   }

                });
}

function messages(evt,x){
	
	document.getElementById("groupContainer").innerHTML="";
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getGroupMessageList', argument:x},

                   success: function (response) {
			   if(response == 0)
				   window.location.href = "login.html";
			   else
			   {
                        var messages = JSON.parse(response);

                        for(i = 0;i<messages.length;i++)
                        {	
                                document.getElementById("groupContainer").innerHTML+= "<div class = \"post\">";
								document.getElementById("groupContainer").innerHTML+= "<div class = \"userContainer\">";
								document.getElementById("groupContainer").innerHTML+= "<div class=\"userImageChat\">";
								document.getElementById("groupContainer").setAttribute("backgroundImage","url(\"" + getImagePath() + "\")");//TODO: ADD FETCH image path METHOD HERE FOR CORRECT DISPLAY
								document.getElementById("groupContainer").innerHTML+= "</div>";
								document.getElementById("groupContainer").innerHTML+= "<div class=\"userNameChat\">" + messages[i].getUserId + "</div>";//TODO: ADD FETCH USERNAME METHOD HERE FOR CORRECT DISPLAY
								document.getElementById("groupContainer").innerHTML+= "</div><br>";
								document.getElementById("groupContainer").innerHTML+= "<div class = \"messageChat\">"+messages[i].getMessage();
								document.getElementById("groupContainer").innerHTML+= "</div>"
								document.getElementById("groupContainer").innerHTML+= "</div>";
                        }
                  }
		   }

                });
}



