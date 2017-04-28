const urlForGroup = document.url;
const urlSplit = urlForGroup.split('groupID');
const groupID = urlSplit[urlSplit.length-1];

function getSource(){//function adds the appropriate source to the calendar 
	$.ajax({
                   type: 'POST',
                   url:  'DBFuncs.php',
                   data: { functionName:'getCreator', argument:groupID},

                   success: function (response) {                   
				document.getElementById("calendar").setAttribute("src", "https://calendar.google.com/calendar/embed?src="+response+"&ctz=America/New_York");
                   }

                });

}