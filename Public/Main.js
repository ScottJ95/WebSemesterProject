<<<<<<< HEAD
var tab = 0;
=======
var userID = null;
var username = null;

>>>>>>> 8e4acec67b957501c660c1477d422500c8ef879f
function start() {

    getSessionUsername();
    getSessionID();
    //ADD USERNAME HERE
//	document.getElementById("userName").innerHTML="HELP";
        
    document.getElementById("all").click();
}


function Groups(evt,x){
    tab = x;
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
                for(i = 0;i<groupData.length;i++){
                    document.getElementById("groupList").innerHTML+="<div class=\"group\"><a onclick=\"moveToChat("
                    +groupData[i].group_ID+");\"><div class = \"groupButton\">"
                    +"<div class = \"chatName\">"+groupData[i].group_name+"</div><div class=\"chatSize\">"+groupData[i].group_numUsers+"</div><div class = \"chatDesc\">"
                    + groupData[i].group_description+"</div></div>"
<<<<<<< HEAD
                    +"</a><a onclick=\"leaveGroup("+groupData[i].group_ID+","+"\'"+groupData[i].group_name+"\'"+");\" <div class=\"leaveButton\">Leave</div></a></div>";
=======
                    +"</a><a onclick=\"\" <div class=\"leaveButton\">Leave</div></a></div>";
>>>>>>> 8e4acec67b957501c660c1477d422500c8ef879f
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
                console.log(response);                   
		document.getElementById("userName").innerHTML=response;
                
        }

    });

}

function getSessionID(){
    $.ajax({
        type: 'POST',
         url:  'DBFuncs.php',
        data: { functionName:'getSessionUserID'},

        success: function (response) {
               userID = response;
                console.log(response);
               setUserImage(); 
        }

    });

}

function setUserImage(){
    $.ajax({
        type: 'POST',
         url:  'DBFuncs.php',
        data: { functionName:'getUserImageAjax', argument: userID},

        success: function (response) {
            if(response){
                $('#userImage').css("background-image", "url(" + response + ")");
            }
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
	setSessionVar();
	window.location.href = "login.html";
}
function leaveGroup(idOfGroup,groupName){
	var x = confirm("Are you sure you want to leave "+groupName );	
	if(x == true)
	{
		$.ajax({
        	type: 'POST',
         	url:  'DBFuncs.php',
        	data: { functionName:'leaveAGroup', argument:idOfGroup },

        	success: function (response) {
        		switch(tab){
				case 0:
					document.getElementById("all").click();
					break;
				case 1:
                                        document.getElementById("created").click();
                                        break;
				case 2:
                                        document.getElementById("In").click();
                                        break;
			}
		}

    		});
	}
}

function editProfile(){
    window.location.href = "profile.php?userID=" + userID;


}
function moveToChat(idOfGroup){
	//Set Session variables
	window.location.href = "Chats.html?groupID="+idOfGroup;
}
