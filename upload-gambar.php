<?php
header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");

include 'dbconfig.php';

$data = json_decode(file_get_contents("php://input"), true);

$fileName = $_FILES['sendimage']['name'];
$tempPath = $_FILES['sendimage']['tmp_name'];
$fileSize = $_FILES['sendimage']['size'];

if(empty($fileName))
{
    $errorMSG = json_encode(array("message" => "please select image", "status" => false));
    echo $errorMSG;
}
else 
{
    $upload_path = 'upload/';

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $valid_extentions = array('jpeg', 'jpg', 'png', 'gif',);

    if(in_array($fileExt, $valid_extentions))
    {
        if(!file_exists($upload_path . $fileName))
        {
            if($fileSize < 5000000) {
                move_uploaded_file($tempPath, $upload_path . $fileName);
            }
            else{
                $errorMSG = json_encode(array("message" => "Sorry, your file is to large, please upload 5 MB size", "status" => false));
                echo $errorMSG;
            }
        }
        else{
            $errorMSG = json_encode(array("message" => "Sorry, your file already exists check upload folder", "status" => false));
            echo $errorMSG;
        }
    }	
    else
	{		
		$errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed", "status" => false));	
		echo $errorMSG;		
	}
}
		
if(!isset($errorMSG))
{
	$query = mysqli_query($conn,'INSERT into tbl_image (name) VALUES("'.$fileName.'")');
			
	echo json_encode(array("message" => "Image Uploaded Successfully", "status" => true));	
}

?>