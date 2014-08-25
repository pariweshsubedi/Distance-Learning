<?php
session_start();

//  include('./classes/Checker.php');
include('./classes/DbHandler.php');
//$chk = new Checker();
//if(!$chk->checkInstallation("./"))
//	header("Location: ./install/index.php");
//else{
	// All authenticated users have $_SESSION['plainuserauth'] == 1
	// If the user is already authenticated by the system then he/she stays. If not he/she is been directed to index.php page
	// in order to fill in the authentication form
	if($_SESSION['plainuserauth'] != 1)
		header("Location: ./index.php");
	else{
	// the application is installed and configured properly, the user is authenticated
	// we instantiate an object of the class DbHandler
		$dbh = new DbHandler();
	// we connect to the database
		$dbh->connect();	
	// and get all the registered users
		$users = $dbh->getUsers();
    $aceusers = $dbh->getAceUsers();
    $novousers = $dbh->getNovoUsers();
	// vLine
	// Before anything else, first we have to include the JWT.php file 
		include("./includes/JWT.php");
	// And now we create the authToken for vLine authentication by setting the user and calling the init method of the
	// Vline class
		include("./classes/Vline.php");
		$vline = new Vline();
		$vline->setUser($_SESSION['user']['id'], $_SESSION['user']['name']);
		$vline->init();
	// Almost ready. All we have to do is to include the vline.js script in the head section.
	}
//}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Ace Novo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	<link type="image/png" href="./images/favicon.png" rel="shortcut icon"/>
    <!-- Le styles -->
    <link href="./bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
    
    <script src="scripts/jquery-1.10.1.min.js"></script>    
    <script src="https://static.vline.com/vline.js" type="text/javascript"></script>
	


    
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="brand" href="#">Ace Novo</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logged in as <?php echo $_SESSION['user']['name'] ?>
            </p>  
            <p class="navbar-text">
             <?php if (!($_SESSION['user']['name']=="Super Admin")){?>
              <a href="./logout.php">Logout</a><? }else{ ?>
            </p>
            <ul class="nav">
              <li>
                <a href="./admin/index.php">Go to admin panel</a></li>
              <?}?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
            <div class="well sidebar-nav">
            <div style="padding:2%; width:96%">
                <h4>Make calls</h4>
                 <p>Click on any of the online users listed below in order to initiate a call. <br>
Users that are online are highlighted in blue.</p>
             </div>
          </div><!--/.well -->
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Users List</li>
              <?php
              if (!isset($_SESSION['type'] )){
               $_SESSION['type'] = "admin";
             }
              
               if ($_SESSION['type']=='novo')    //if logged in user is ace user then display related ace users
              { 
                while($row = mysqli_fetch_array($aceusers, MYSQLI_ASSOC)){  
                  if (is_null($row['tid'])){
                $type = "";
            }else{ $type = "Teacher"; }
                ?>
                     <li class="callbutton disabled" data-userid="<?php echo $row['id'] ?>"><a href="#"><?php echo $row['name']; echo " | ";
                      echo "<b>".$type."</b>"; ?></a></li>
              <?php } 
              }elseif ($_SESSION['type']=='ace'){//if logged in user is novo user then display related novo users
                 while($row = mysqli_fetch_array($novousers, MYSQLI_ASSOC)){ 

                 ?>
                       <li class="callbutton disabled" data-userid="<?php echo $row['id'] ?>"><a href="#"><?php echo $row['name'];  echo " | "; echo "<b>".$row['language']."</b>"; ?></a></li>
            <?php
          } 
        }else{
           while($row = mysqli_fetch_array($users, MYSQLI_ASSOC)){ 
            if($row['accent']=='0'){
                $type = "Ace USER";
            }
            else{
              $type = "Novo USER";
            }

                 ?>
                       <li class="callbutton disabled" data-userid="<?php echo $row['id'] ?>"><a href="#"><?php echo $row['name'] ; echo "  |   ". $type; ?></a></li>
            <?php
          } 
        }?>
            
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit">
            <h1><?php if($_SESSION['type']=="novo")
            {
                echo "Novo [Distance Learning]";?>
              </h1>
                        <p>Novo users can only see Novo users</p>
          <?php 
            }elseif($_SESSION['type']=="ace"){ 
              echo "Ace [Language/ Culture exchange]";?>
              </h1>
                        <p>Ace Users can only see Ace users</p>
          <?php 
            }
            else{
              echo "Welcome Admin";?>
              </h1>
                        <p>Admin can see both Ace as well as Novo users</p>
          <?php  }
            ?>
                        
                      </div>
                      <div class="row-fluid" width="10px">
                        <div class="span4">
                          <? //echo $_SESSION['u_id'];
                          if(!isset($_SESSION['u_id']))
                          {
                              $_SESSION['u_id']=0;
                          }
                          $a=$_SESSION['u_id'];
                          
                          $sql="SELECT dLearning,tid,sub_select from user where id=$a";
                          $con=  mysqli_connect("localhost", "root", "","vline-php-example");
                          $res=mysqli_query($con,$sql);
                          $row=  mysqli_fetch_row($res);
                          //print_r($row);
                       if(($row[0]==1)&&($row[1]!="")&&($row[2]==0))
                         {?>
                            <html>
                                <body>
                                    <form name="form" method="post">
                                        Subject:<select name="subject">
                                        <option value="1">Math</option>
                                        <option value="2">Science</option>
                                         <option value="3">Social</option>
                                             </select>
                                        <input type="submit" name="submit"/>
                                    </form>
                                </body>
                            </html>
                            <?php
                        if(isset($_POST['subject']))
                        {
    
                        $sub=$_POST['subject'];
                        $query="UPDATE user set tid='$sub',sub_select=1 where id='$a'";
                        mysqli_query($con,$query);
                        echo"Inserted";  
                        }
                        }
                           else{ 
                        ?>     

                          <h2>Guide</h2>
                          <p>On the left is a list of the application's users. You get subscribe to any user's presence. Users that are online are highlighted in
                          blue.</p>
                        </div><!--/span-->  
                           <?php } ?>
                        <div class="span4">                      


                          <?php if($_SESSION['type']=="novo"){?>
                          <h2>Find Teacher</h2>
                             <p><form name="selection" method="post">
                                Subject:<select name="teacher">
                                    <option value="math">Math</option>
                                    <option value="science">Science</option>
                                    <option value="english">English</option>
                                    <option value="social">Social</option>
                                </select>
                                <input type="submit" value="search" name="submit">
                            </form></p>
                            <? } ?>
                        </div><!--/span-->
            <div class="span4">
              
              <p>    <?php 
                          if(isset($_POST['teacher'])){
                  ?><h3>Teachers available</h3> <?php
                    $sub=$_POST['teacher'];
                    $con=mysqli_connect("localhost","root","","vline-php-example");
                    $query="SELECT a.name,a.id from user as a inner join teacher as t on a.tid=t.id where t.subject='$sub'";
                    $result=mysqli_query($con,$query);
                    $row=  mysqli_num_rows($result);
                     if($row==0)       
                     {
                         echo"<BR>".'Sorry no teacher for the particular subject is found';
                     }
                    foreach($result as $r ):
                      $r=mysqli_fetch_array($result);                        
                        echo '<li style="list-style-type:none;" class="callbutton disabled" data-userid="'.$r[1].'"><a href="#">'. $r[0]."</a> 
                        </li>";
                    endforeach;

                    }

                          ?>           </p>
              <p></p>
            </div><!--/span-->
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

      <hr>
    </div><!--/.fluid-container-->

	<!-- vLine ------------------------------------------->
	<script>
	var vlineClient = (function(){
	  if('<?php echo $vline->getServiceID() ?>' == 'YOUR_SERVICE_ID' || '<?php echo $vline->getServiceID() ?>' == 'YOUR_SERVICE_ID'){
		alert('Please make sure you have created a vLine service and that you have properly set the $serviceID and $apiSecret variables in classes/Vline.php file.');	  
	  }
	  
	  
	  var client, vlinesession,
		authToken = '<?php echo $vline->getJWT() ?>',
		serviceId = '<?php echo $vline->getServiceID() ?>',
		profile = {"displayName": '<?php echo $vline->getUserDisplayName() ?>', "id": '<?php echo $vline->getUserID() ?>'};
	
	  // Create vLine client  
	  window.vlineClient = client = vline.Client.create({"serviceId": serviceId, "ui": true});
	  // Add login event handler
	  client.on('login', onLogin);
	  // Do login
	  
	  
      client.login(serviceId, profile, authToken);
      
	
	  function onLogin(event) {
		vlinesession = event.target;
		// Find and init call buttons and init them
		$(".callbutton").each(function(index, element) {
           initCallButton($(this)); 
        });
	  }
	
	  // add event handlers for call button
	  function initCallButton(button) {
		var userId = button.attr('data-userid');
	  
		// fetch person object associated with username
		vlinesession.getPerson(userId).done(function(person) {
		  // update button state with presence
		  function onPresenceChange() {
			if(person.getPresenceState() == 'online'){
			    button.removeClass().addClass('active');
			}else{
			    button.removeClass().addClass('disabled');
			}
			button.attr('data-presence', person.getPresenceState());
		  }
		
		  // set current presence
		  onPresenceChange();
		
		  // handle presence changes
		  person.on('change:presenceState', onPresenceChange);
		
		  // start a call when button is clicked
		  button.click(function() {
		      	  if (person.getId() == vlinesession.getLocalPersonId()) {
				alert('You cannot call yourself. Login as another user in an incognito window');
				return;
		       	  }
			  if(button.hasClass('active'))
				person.startMedia();
		  });
		});
		
	  }
	  
	  return client;
	})();
	
	$(window).unload(function() {
	  vlineClient.logout();
	});
	</script>
    <!-- /vLine -------------------------------------------->
    
  </body>
</html>
