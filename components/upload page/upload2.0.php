<!DOCTYPE html>
<html>
<head>
<title>Image Uploaded!</title>
<meta charset="utf-8">
</head>

<body>




Static content
<br>

<?php

echo "Content generated by server using PHP";
echo "<br>";



/*

following PHP code generated using MySQL Workbench (IMPORTANT: $password has to be set or else it won't be able to connect to database):
Tools > Utilities > Copy as PHP Code (Connect to Server)

*/
$host="127.0.0.1";
$port=3306;
$socket="";
$user="c2375a04";
$password="c2375aU!";
$dbname="c2375a04test";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());


/*

following PHP code generated using MySQL Workbench:
Tools > Utilities > Copy as PHP Code (Iterate SELECT Results)

*/



$imageFile = $_POST["fileToUpload"]; // Image file being Uploaded.





$query = "select date_format(curtime(), '%M %D, %Y')";


if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($date_format);
    while ($stmt->fetch()) {
        printf("<b>Today:</b> %s\n", $date_format);
    }
    $stmt->close();
}




$con->close();



// if form method="post", you would use $_POST["test"] to get the values
 $test = $_GET["test"]; 

// if form method="post", you would use $_POST["test"] to get the values
$choices = $_GET["choice"];
/*
echo "<br>";
echo "Number of options : "; 
echo count($test);

echo "<br>";
echo "Number of choices : " ; 
echo count(options[0]);

echo "<br>";
echo "Image : " ; 
 */

//echo readfile($imageFile);

/* } */


//echo readfile($target_file);

//$imageFile = $_POST["fileToUpload"];

echo "<br>";
echo "Image Name:   ";
echo $_POST['imageName'];
echo "<br>";
echo "File Uploaded: $imageFile ";

echo "<br>";
echo "Category Chosen:  ";
echo $_POST["category"];
echo "<br>";

echo "Number of Choices Selected:   ";

$choices = $_POST["choice"];
echo count($choices);
    
 
    



//echo count(options[0]);
echo "<br>";




echo "Choices Selected:   ";

$name = $_GET["choice[]"];
   
foreach ($name as $choice){ 
    echo $choice."<br />";
    echo "<br>";
}


 ?>
<div class="gallery">
    <img src= <?php readfile($imageFile) ?> alt="" width="600" height="400">
    <?php  readfile($imageFile); ?>

</div>

</body>
</html>