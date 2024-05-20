<?php 
    $url = 'http://localhost/Technical_round/';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty($_POST['full_name'])) {
            $error[] = 'Full name is required';
        }
        if (empty($_POST['phone_number'])) {
            $error[] = 'Phone number is required';
        }
        if (empty($_POST['email'])) {
            $error[] = 'Email is required';
        } else if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Invalid Email';
        }
        if (empty($_POST['subject'])) {
            $error[] = 'Subject is required';
        }
        if (empty($_POST['message'])) {
            $error[] = 'Message is required';
        }
        

        $full_name = $_POST['full_name'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
       

        if (empty($error)) {
            if (($_POST['num1'] + $_POST['num2']) == $_POST['captcha']) {

                $hostname = 'localhost';
                $database = 'test';
                $username = 'root';
                $password = '';
                $conn = new mysqli($hostname,$username,$password,$database);
                if ($conn->connect_error) {
                    die("connection failed");
                }
                $result = $conn->query('Select id from contact_from where phone_number="'.$phone_number.'" or email="'.$email.'" ' );

                if($result->num_rows == 0) {
                    $query = $conn->prepare("INSERT INTO `contact_form`(`full_name`, `phone_number`, `email`, `subject`, `message`) VALUES (?,?,?,?,?)");
                
                    $query->bind_param("sssss", $full_name, $phone_number, $email, $subject, $message);
                    if ($query->execute()) {
                        header("Location: ".$url."?status=success");
                    }
                } else {
                    $error = "Invalid Entry";
                }
            } else {
                $error[] = 'InValid Captcha';
            }
        } 
        
    } else {
        $full_name = '';
        $phone_number = '';
        $email = '';
        $subject = '';
        $message = '';
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if (!empty($error)) {
            foreach ($error as $val) {
                echo $val .'<br>';
            }
        }
        if (isset($_GET['status']) && $_GET['status'] == 'success') {
            echo 'Data Submited Success Fully';
            echo '<script>history.replaceState({},"",'.$url.')</script>';
        }
    ?>
    <form action="<?php echo $url; ?>" method="post">

    <label for="full_name">Full Name :</label>
    <br>

    <input type="text" name="full_name" id="full_name" value = "<?php echo $full_name; ?>" style="width:200px" required>
    <br>
    <label for="phone_number">Phone number:</label>
    <br>
    <input type="text" name="phone_number" id="phone_number" value = "<?php echo $phone_number; ?>" style="width:200px"  required>
    <br>

    <label for="email">Email :</label>
    <br>
    <input type="text" name="email" id="email" value = "<?php echo $email; ?>" style="width:200px" required>
    <br>

    <label for="subject">Subject :</label>
    <br>
    <input type="text" name="subject" id="subject" value = "<?php echo $subject; ?>" style="width:200px" required>
    <br>

    <label for="message">Message :</label>
    <br>
    <textarea name="message" id="message" style="width:200px" value = "<?php echo $message; ?>" required></textarea>
    <br>
    <?php 
    $num1 = rand(10,99);
    $num2 = rand(10,99);
    ?>
    <label for="message">Captcha :</label>
    <br>
    <p><?php echo $num1; ?> + <?php echo $num2; ?> = 
    <input type="text" name="captcha" value="" style="width:40px"> </p>
    <input type="hidden" name="num1" value="<?php echo $num1; ?>">
    <input type="hidden" name="num2" value="<?php echo $num2; ?>">
    <input type="submit" value="submit">

    </form>
</body>
</html>
<!-- 

please check this video link also (i got some internet connectivity issue due to electricity that why video is in chunk)    
https://www.loom.com/share/5cad22f513bf4de8b7d9f53b3023af85?sid=1dc36f0f-787e-4aaf-b0b7-6f5bc5691e0a 

-->

