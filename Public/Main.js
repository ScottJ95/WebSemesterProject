function start() {

	getSessionUsername();
	//ADD USERNAME HERE
	//document.getElementById("userName").innerHTML="";
        
	document.getElementById("all").click();
}

function joinGroup(){

}

function Groups(evt,x){
	
tablinks = document.getElementsByClassName("tabButton");	
for (i = 0; i < tablinks.length; i++) {
	tablinks[i].className = tablinks[i].className.replace(" active", "");
}
evt.currentTarget.className += " active";

document.getElementById("groupList").innerHTML="";
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getGroups', argument:x},

                   success: function (response) {
			   if(response == 0)
				   window.location.href = "login.html";
			   else
			   {
                        var groupData = JSON.parse(response);

                        for(i = 0;i<groupData.length;i++)
                        {
                                document.getElementById("groupList").innerHTML+="<button class=\"groupButton\" id=\"group\" onclick=\"moveToChat();\">"+groupData[i].group_name+" Size: "+groupData[i].group_numUsers+" Description: "+ groupData[i].group_description+"</button> <button class=\"leaveButton\" id=\"leave\">Leave</button>";
                        }
                  }
		   }

                });
}
function getSessionUsername(){
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getSessionVar'},

                   success: function (response) {                   
				document.getElementById("userName").innerHTML=response;
                   }

                });

}
function setSessionVar(){
$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'sessionOff'},

                   success: function (response) {}

                });
}

function groupButton(){
	//Send to Group page
	//window.location.href = "newpassword.html";
}

function createGroup(){
	window.location.href = "createGroup.php";
}
function joinGroup(){
        window.location.href = "joinGroup.php";
}
function logout(){
//	setSessionVar();
	window.location.href = "login.html";
}
function editProfile(){
	window.location.href = "editProfile.php";
}
function moveToChat(){
	//Set Session variables
	window.location.href = "Chats.html";
}
