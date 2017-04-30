//Check the current group name. This is an AJAX call. 
//I found this source code online at: 
//http://talkerscode.com/webtricks/check%20username%20and%20email%20availability%20from%20database%20using%20ajax.php
//TODO: Have this be in a seperate file?
//
var nameCheck = true;
function checkName(edit){

    var groupName = $("#groupName").val();

    //console.log(groupName); //Debugging. Comment out.

    if(groupName) { //If it's not null, let's check it.
        //Jquery to setup AJAX we can give it a bunch of stuff
        //type: post or get?
        //url: What script do we run?
        //data: What data are we sending?
        //success or failure callbacks
        //This is equivalent to jquery.post(), but this made more sense to me.
        //https://api.jquery.com/jquery.post/
        if(groupName.toLowerCase() == "users"){
            $('#name_status').html("You cannot name your group that!");
            nameCheck = true;
            return true;
        }
        $.ajax({ 
           type: 'post',
               url:  'checkGroup.php',
           data: {
               group_name:groupName,
           },
        
           success: function (response) {
               //Call was successful, so do this function
               //First, set the name_status html to the response.
            console.log(response);
            $( '#name_status').html(response);
            console.log(response == "Group Name Already Exists");
            //Check the response so we can return the check
            if(response === 'Group Name Already Exists') {
                nameCheck = true;
                console.log($('#name_status').val());
                return true;
            }

            else {
                console.log("False");
                nameCheck = false;
                return false;
            }
        }
        });//End Ajax
    } //End If

    else //Nothing typed into the group name
    {
        $( '#name_stats').html("");
        if(edit === true){
            console.log("Edit True");
            nameCheck = false;
            return false;
        }
        else{
            nameCheck = true;
            return true;
        }
    }
}

function checkDescription(edit){
    var descriptionText = $('#description').val();
    console.log(descriptionText);
    if(descriptionText === ""){
        console.log("I got here");
        if(edit === true){
            console.log("Description True");
            return true;
        }
        else{
            alert("Please enter a description");
            return false;
        }
    }
    else{
        return true;
    }

}

function checkForm(edit) {
    if(edit === false){
        if(!nameCheck && $("#groupName").val() != "") {
            if(checkDescription(edit)){
                return true;
            }
            else{
                return false;
            }
        }
        else {
            alert("Please Enter a valid group name!");
            return false;
        }
    }
    else {
        console.log("Ok");
        if(!nameCheck) {
            if(checkDescription(edit)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            alert("Please Enter a valid group name!");
            return false;
        }


    }
}

function findGroups()
{
	$('#name_stats').html("");
	if($("#groupName").val() === ""){
		$('#name_stats').html("Please enter a Group Name");
		return false;
	}
	else
	{
	$.ajax({
           type: 'post',
               url:  'DBFuncs.php',
           data: {
               functionName:'joinGroups', argument:[$("#groupName").val(),$("#groupSubject").val()]
           },

           success: function (response) {
            if(response == 0) {
                window.location.href = "login.html";
		return false;    
            }

            else {

	//	 $('#name_stats').html(response);
                //Move to list of groups
            	return false;
	    }
        }
        });
	}
}


function descriptionCount() {
    var descriptionText = $("#description").val();
    $("#description_charCount").html(250 - descriptionText.length);
}

function deleteGroupCheck(){
    if(confirm("Are you SURE you want to delete? THIS CANNOT BE UNDONE!!!")){
        $.ajax({
           type: 'post',
               url:  'DBFuncs.php',
           data: {
               functionName:'deleteGroup',
           },

           success: function (response) {
            console.log(response);
            if(response == 0) {
                alert("Group has been deleted");
                window.history.back();
            }

            else {
                alert(response);
                return false;
            }
        }
        });
    }
    else{
    }
}
