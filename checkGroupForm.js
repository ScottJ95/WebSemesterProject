//Check the current group name. This is an AJAX call. 
//I found this source code online at: 
//http://talkerscode.com/webtricks/check%20username%20and%20email%20availability%20from%20database%20using%20ajax.php
//TODO: Have this be in a seperate file?
function checkName(){

    var groupName = $("#groupName").val();

    console.log(groupName); //Debugging. Comment out.

    if(groupName) { //If it's not null, let's check it.
        //Jquery to setup AJAX we can give it a bunch of stuff
        //type: post or get?
        //url: What script do we run?
        //data: What data are we sending?
        //success or failure callbacks
        //This is equivalent to jquery.post(), but this made more sense to me.
        //https://api.jquery.com/jquery.post/
        $.ajax({ 
           type: 'post',
               url:  'checkGroup.php',
           data: {
               group_name:groupName,
           },
        
           success: function (response) {
               //Call was successful, so do this function
               //First, set the name_status html to the response.
            $( '#name_status').html(response);
            //Check the response so we can return the check
            if(response == "OK") {
                return true;
            }

            else {
                return false;
            }
        }
        });//End Ajax
    } //End If

    else //Nothing typed into the group name
    {
        $( '#name_stats').html("");
        return false;
    }
}

function checkDescription(){
    var descriptionText = $('#description').val();

    if(descriptionText === ""){
        console.log("I got here");
        alert("Please enter a description");
        return false;
    }
    else{
        return true;
    }

}

function checkForm() {
    if(!checkName() && $("#groupName").val() != "") {
        if(checkDescription()){
            return true;
        }
        else{
            return false;
        }
    }
    else {
        alert("Please Enter a group name!");
        return false;
    }

}

function descriptionCount() {
    var descriptionText = $("#description").val();
    $("#description_charCount").html(250 - descriptionText.length);
}
