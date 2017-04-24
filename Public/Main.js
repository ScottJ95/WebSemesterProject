function start() {


	//ADD USERNAME HERE
	//document.getElementById("userName").innerHTML="";
        
	document.getElementById("all").click();
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
                        var groupData = JSON.parse(response);

                        for(i = 0;i<groupData.length;i++)
                        {
                                document.getElementById("groupList").innerHTML+="<Button class=\"groupButton\" id=\"group\" onclick=\"moveToChat();\">"+groupData[i].group_name+" Size: "+groupData[i].group_numUsers+" Description: "+ groupData[i].group_description+"</button>";
                        }
                  }

                });
}

function groupButton(){
	//Send to Group page
	//window.location.href = "newpassword.html";
}

function createGroup(){
	window.location.href = "createGroup.php";
}
function logout(){
	window.location.href = "login.html";
	//Set session variables to null
}
function editProfile(){
	window.location.href = "editProfile.php";
}
function moveToChat(){
	//Set Session variables
	window.location.href = "Chats.html";
}
