var tab = '0';
var userID = null;
var username = null;

//The start function is called onload
//It is used to set the Session ID and Username 
//Aswell as click the first tab and show all groups
function start() {

    getSessionUsername();
    getSessionID();
    setUserImage();
		
    document.getElementById("all").click();
}

//The Groups function is called when someone changes clicks a tab
//There are three tabs ALL(x=0), CREATED(x=1), and IN(x=2)
//This function uses ajax to capture a list of groups that can be clicked on
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
                    +"<div class = \"chatName\">"+groupData[i].group_name+"</div><div class=\"chatSize\">Chat size: "+groupData[i].group_numUsers+"</div><div class = \"chatDesc\">"
                    + groupData[i].group_description+"</div></div>"
                    +"</a><a onclick=\"leaveGroup("+groupData[i].group_ID+","+"\'"+groupData[i].group_name+"\'"+");\" <div class=\"leaveButton\">Leave</div></a></div>";
                    +"</a><a onclick=\"\" <div class=\"leaveButton\">Leave</div></a></div>";
                }

            }
	}
    });
}

function setSessionVarAndImage()
{
	
}

//This function gets the session Username and displays it
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

//This function gets the session ID and calls the setUserImage() function
function getSessionID(){
    $.ajax({
        type: 'POST',
         url:  'DBFuncs.php',
        data: { functionName:'getSessionUserID'},

        success: function (response) {
               userID = response;
                console.log(response);
               //setUserImage(); 
        }

    });

}

//This function gets the user Image and displays it
function setUserImage(){
    $.ajax({
        type: 'POST',
         url:  'DBFuncs.php',
        data: { functionName:'getUserImageAjax'},

        success: function (response) {
            if(response){
                $('#userImage').css("background-image", "url(" + response + ")");
            	console.log(response);
		    //$('#userImage').src = response;
	    }
		
        }

    });


}


//This function sets the session variables to null
function setSessionVar(){
    $.ajax({
        type: 'POST',
        url:  'DBFuncs.php',
        data: { functionName:'sessionOff'},

        success: function (response) {}

    });
}

//This function sends you to a page where you can create a group
function createGroup(){
	window.location.href = "createGroup.php";
}
//This function sends you to a page where you can join a group
function joinGroup(){
        window.location.href = "joinGroup.php";
}
//This function logs you out and sends you to the login page
function logout(){
	setSessionVar();
	window.location.href = "login.html";
}
//This function given a Group ID and Name allows you to leave a group
//Once you leave it will refresh whicheber tab you are on.
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
				case '0':
					document.getElementById("all").click();
					break;
				case '1':
                                        document.getElementById("created").click();
                                        break;
				case '2':
                                        document.getElementById("In").click();
                                        break;
			}
		}

    		});
	}
}
//This function sends you to a page where you can edit your profile
function editProfile(){
    window.location.href = "profile.php?userID=" + userID;


}
//This function sends you to a page where your chat is depending on what group
/////you clicked on
function moveToChat(idOfGroup){
	window.location.href = "Chats.php?groupID="+idOfGroup;
}
