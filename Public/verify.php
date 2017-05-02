
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Account Verification</title>
<link href="verify.css" rel="stylesheet" type="text/css">
<script src="register.js"></script>
</head>

<body>
<div class = "header"> Account Verification</div>
     
    <div id="container">
        <?php
            require_once('/home/jefferys0/source_html/web/WebSemesterProject/Connect.php');
            $dbh = ConnectDB();

            if(isset($_GET['email']) && !empty($_GET['email']) AND
            isset($_GET['hash']) && !empty($_GET['hash'])){


            $email = $_GET['email']; // Set email variable
            $hash = $_GET['hash']; // Set hash variable

            $query = "SELECT email, hash_link, verified FROM students WHERE
            email= :email AND hash_link= :hashLink AND verified='0'";

            $stmt = $dbh-> prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':hashLink', $hash);
            $stmt->execute();

            if($stmt -> rowCount() != 0){
                //Match is found for the email and hash, change their verfied to 1
                $query = "UPDATE students SET verified='1' WHERE email= :email
                AND hash_link= :hashLink";
                $stmt = $dbh-> prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':hashLink', $hash);
                $stmt->execute();
                echo '<div class="validate">Your account has been activated,
                you can now login.</div>';
            }
            else{
                // No match, invalid url or account has already been activated.
                echo '<div class="validate">The url is either invalid or you 
                already have activated your account.</div>';
            }
        }
        else{
            // Invalid approach, ie user manually goes to verify.php
            echo '<div class="validate">Invalid approach, please use the link that
            has been send to your email.</div>';
        }
        ?>

        <div class = "buttonContainer">
            <button onclick="cancel()" class = "homebuttons"  id = "cancel">Back to Login</button>
        </div>
 
    </div>
</body>
</html>
