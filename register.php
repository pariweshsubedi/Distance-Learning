
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<link type="image/png" href="./images/favicon.png" rel="shortcut icon"/>
    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }
     

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="./css/style.css" rel="stylesheet"> 
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="bootstrap/js/html5shiv.js"></script>
    <![endif]-->

    </head>


  <?php if(isset($_POST['password'])){
  	include("/classes/DbHandler.php");
	$dbh = new DbHandler();
	$dbh->connect('./');
	$dbh->addUser($_POST);
	$msg = urlencode("User added succesfully");
	//header("Location: main.php?msg=".$msg);
}?>


<?php if(array_key_exists('msg', $_GET)){ ?>
       	<div class="msg">
        	<?php echo $_GET['msg'] ?>
        </div>
       <?php } ?>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="form-signin" onSubmit="return trytosubmit();">
	<h2 class="form-signin-heading">Register</h2>
        <table class="table">
            <tr>
            	<td>Full Name</td>
                <td><input type="text" placeholder="" name="name" id="name" required></td>
            </tr>
            <tr>
            	<td>Username</td>
                <td><input type="text" placeholder="" name="username" id="username" required></td>
            </tr>
            <tr>
            	<td>Password</td>
                <td><input type="password" placeholder="" name="password" id="password" required></td>
            </tr>
            <tr>
              <td>Usage:</td>
                <td>
                    <SELECT name="register_type">
                  <option value="ace">
                    Ace Learning 
                  </option>
                  <option value="novo">
                    Novo Learning
                  </option>
                  </SELECT>
            </tr>
            <tr>
              <td></td>

                <td class= "hidden-first" style = "display:hidden">
                    <SELECT name="Dtype">
                  <option value="student">
                    Student 
                  </option>
                  <option value="teacher">
                    Teacher
                  </option>
                  </SELECT>
            </tr>
           <!-- <tr>
              
                <td name = "dType" >
                  <select>
                    <option value= "student">Student</option>
                    <option value= "teacher">Teacher</option>
                </select>
              </td>
            
            </tr>-->
            <tr>
            	<td colspan="2"><input type="submit" class="btn" value="Submit" required /></td>
            </tr>
        </table>
        </form>