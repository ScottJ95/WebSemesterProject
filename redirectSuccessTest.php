<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
 "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Redirect Test</title>
  <meta http-equiv="Content-Type"
        content="application/xhtml+xml; charset=UTF-8" />
  <meta name="Author" content="Scott Jeffery" />

  <link rel="stylesheet" href="tagline.css" />
  <script type="text/javascript" src="./AjaxFunctions.js"></script>
   <script type="text/javascript"
          src="http://code.jquery.com/jquery-1.9.0.min.js"> </script>
</head>

<body>

<h1> Success! </h1>

<?php

if(isset($_GET['error'])){
	echo "<p> Looks like the image didn't upload right, but your group was created. </p>\n";

}

echo "<p> Your group was created successfully!</p>";

?>

</body>

</html>
