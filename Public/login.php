<?php
require_once('DBFuncs.php');
require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');

$dbh = ConnectDB();

?>

<script type="text/javascript">

function findUserName(){

        var username = $("#emailBox").val();

        console.log(username); //Debugging. Comment out.

        if(username) { //If it's not null, let's check it.
                //Jquery to setup AJAX we can give it a bunch of stuff
                //type: post or get?
                //url: What script do we run?
                //data: What data are we sending?
                //success or failure callbacks
                //This is equivalent to jquery.post(), but this made more sense to me.
                //https://api.jquery.com/jquery.post/
                $.ajax({ 
                   type: 'post',
                   url:  'DBFuncs.php',
                   data: {
                        functionname: 'getUserInfoThroughUserName', arguments: [$dbh,username ]},
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
</script>
