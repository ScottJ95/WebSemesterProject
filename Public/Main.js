var tab = 0;
function start() {

    getSessionUsername();
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
                for(i = 0;i<groupData.length;i++)
                {
                    document.getElementById("groupList").innerHTML+="<div class=\"group\"><a onclick=\"moveToChat("
                    +groupData[i].group_ID+");\"><div class = \"groupButton\"><img class = \"groupImage\" alt=\"groupIcon\" src=\""
                    +"\"><div class = \"chatName\">"+groupData[i].group_name+"</div><div class=\"chatSize\">"+groupData[i].group_numUsers+"</div><div class = \"chatDesc\">"
                    + groupData[i].group_description+"</div></div>"
                    +"</a><a onclick=\"leaveGroup("+groupData[i].group_ID+","+"\'"+groupData[i].group_name+"\'"+");\" <div class=\"leaveButton\">Leave</div></a></div>";
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
         $.ajax({
        type: 'POST',
         url:  'DBFuncs.php',
        data: { functionName:'getSessionUserID'},

        success: function (response) {
                window.location.href = "profile.php?userID=" + response;

        }

    });
}
function moveToChat(idOfGroup){
	//Set Session variables
	window.location.href = "Chats.html?groupID="+idOfGroup;
}
