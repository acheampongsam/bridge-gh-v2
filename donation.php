<?php
require_once("assets/php/DB.php");
$SearchQueryParameter = $_GET["id"];
if(isset($_POST["Submit"])){
  if(!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"])){
    $costOfTicketA = 10;
    $costOfTicketB = 25;
    $costOfTicketC = 50;
    $costOfTicketD = 100;
    // Grab from input form
    $fullName = $_POST["name"];
    $eMail = $_POST["email"];
    $phoneNumber = $_POST["phone"];
    if($SearchQueryParameter == 100){$type = 'a'; }
    if($SearchQueryParameter == 250){$type = 'b'; }
    if($SearchQueryParameter == 1000){$type = 'c'; }
    if($SearchQueryParameter == 2000){$type = 'd'; }
    $quantity = 1;
    // Database connection
    global $ConnectingDB;
    // retrivecurrent stats
    $sql = "SELECT orders,soldTickets,validTickets,netSales FROM raffle_stats WHERE PK=1";
    $stmt = $ConnectingDB -> query($sql);
    $row = $stmt->fetch();
    $x = $row['orders'];
    $y = $row['soldTickets'];
    $z = $row['validTickets'];
    $zz = $row['netSales'];
    // Update raffle_stats Table
    if($type == 'a'){
      $sale = $costOfTicketA * $quantity;

      $x = ($x+1);
      $y = ($y + ($quantity * 10));
      $z = ($z + ($quantity * 10));
      $zz = ($zz + $sale);
      //sql
      $update_raffle_stats = "UPDATE raffle_stats SET orders='$x', soldTickets='$y', validTickets='$z', netSales='$zz' WHERE PK=1";
      $Execute = $ConnectingDB -> query($update_raffle_stats);
    }else if($type == 'b'){
      $sale = $costOfTicketB * $quantity;
    
      $x = ($x+1);
      $y = ($y + ($quantity * 25));
      $z = ($z + ($quantity * 25));
      $zz = ($zz + $sale);
      //sql
      $update_raffle_stats = "UPDATE raffle_stats SET orders='$x', soldTickets='$y', validTickets='$z', netSales='$zz' WHERE PK=1";
      $Execute = $ConnectingDB -> query($update_raffle_stats);
    }else if($type == 'c'){
      $sale = $costOfTicketC * $quantity;
    
      $x = ($x+1);
      $y = ($y + ($quantity * 100));
      $z = ($z + ($quantity * 100));
      $zz = ($zz + $sale);
      //sql
      $update_raffle_stats = "UPDATE raffle_stats SET orders='$x', soldTickets='$y', validTickets='$z', netSales='$zz' WHERE PK=1";
      $Execute = $ConnectingDB -> query($update_raffle_stats);
    }else if($type == 'd'){
      $sale = $costOfTicketD * $quantity;
    
      $x = ($x+1);
      $y = ($y + ($quantity * 200));
      $z = ($z + ($quantity * 200));
      $zz = ($zz + $sale);
      //sql
      $update_raffle_stats = "UPDATE raffle_stats SET orders='$x', soldTickets='$y', validTickets='$z', netSales='$zz' WHERE PK=1";
      $Execute = $ConnectingDB -> query($update_raffle_stats);
    }
    // Order # generation
    $fetch_last_row_id = "SELECT id FROM valid_ticket_records ORDER BY id DESC LIMIT 1";
    $stmt = $ConnectingDB -> query($fetch_last_row_id);
    $row = $stmt->fetch();
    $last_row_id = $row['id'];
    $currentId = $last_row_id + 1;
    $orderNumber = 'BR' . $fullName[0] . $phoneNumber[strlen($phoneNumber)-1] . '-' . $currentId .'-'. '20';
    // Paymet info insertion
    $sql = "INSERT INTO valid_ticket_records(fullName,eMail,phoneNumber,ticketType,orderNumber) 
    VALUES(:fnamE, :emaiL, :phonE, :tickettypE, :ordernumbeR)";
    $stmt = $ConnectingDB -> prepare($sql);

    if($type == 'a'){
      $ticketType = 10;
    }else if($type == 'b'){
      $ticketType = 25;
    }else if($type == 'c'){
      $ticketType = 50;
    }else if($type == 'd'){
      $ticketType = 100;
    }
    
    $stmt -> bindValue(':fnamE', $fullName);
    $stmt -> bindValue(':emaiL', $eMail);
    $stmt -> bindValue(':phonE', $phoneNumber);
    $stmt -> bindValue(':tickettypE', $ticketType);
    $stmt -> bindValue(':ordernumbeR', $orderNumber);
    
    if($type == 'a'){
      $count = 10 * $quantity;
    }else if($type == 'b'){
      $count = 25 * $quantity;
    }else if($type == 'c'){
      $count = 100 * $quantity;
    }else if($type == 'd'){
      $count = 200 * $quantity;
    }

    for($i=0; $i<$count; $i++){
      $Execute = $stmt->execute();
    }

    if($Execute){
      // email
      $to = $eMail; 
      $from = 'acheampongsamuel97@gmail.com'; 
      $fromName = 'Bridge Tech Team'; 
      
      $subject = "Bridge Order Receipt - Test"; 
      
      $htmlContent = ' 
          <html> 
          <head> 
              <title>Thanks to CodexWorld</title> 
          </head> 
          <body> 
              <h1>Thanks you for joining with us!</h1> 
              <table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%;"> 
                  <tr> 
                      <th>Name:</th><td>CodexWorld</td> 
                  </tr> 
                  <tr style="background-color: #e0e0e0;"> 
                      <th>Email:</th><td>contact@codexworld.com</td> 
                  </tr> 
                  <tr> 
                      <th>Website:</th><td><a href="http://www.codexworld.com">www.codexworld.com</a></td> 
                  </tr> 
              </table> 
          </body> 
          </html>'; 
      
      // Set content-type header for sending HTML email 
      $headers = "MIME-Version: 1.0" . "\r\n"; 
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
      
      // Additional headers 
      $headers .= 'From: '.$fromName.'<'.$from.'>' . "\r\n"; 
      // $headers .= 'Cc: welcome@example.com' . "\r\n"; 
      $headers .= 'Bcc: ' . $from . "\r\n"; //this should be bridge receipt backup system (dedicated mail)
      
      // Send email 
      if(mail($to, $subject, $htmlContent, $headers)){ 
          echo 'Email has sent successfully.'; 
      }else{ 
        echo 'Email sending failed.'; 
      }
      // email end
      echo "Record addedd successfully";
    }
  }else{
    echo "Please make sure all fields are filled";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Donation Payment</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Arsha - v2.1.0
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  

  <main id="main">

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Make your donation</h2>
        </div>

        <div class="row align-center">

          <div class="col-lg-5 mt-lg-0 d-flex align-items-stretch">
            <form action="" method="" role="form" class="php-email-form">
              <div class="form-group col-md-12 content">
                <p>Order Summary:</p>
                <hr>
                <p class="font-italic text-primary">Support Sarkodie Foundation</p>
                <label><strong><h3 >Win a 2018 Toyota Yaris & ₵10,000 cedis</h3></strong></label>
                <p class="text-secondary">
                  <?php if($SearchQueryParameter == 100) : ?> (100 entries - GHS 10)<?php endif; ?>
                  <?php if($SearchQueryParameter == 250) : ?> (250 entries - GHS 25)<?php endif; ?>
                  <?php if($SearchQueryParameter == 1000) : ?> (1000 entries - GHS 50)<?php endif; ?>
                    <?php if($SearchQueryParameter == 2000) : ?> (2000 entries - GHS 100)<?php endif; ?>
                </p>
                <hr>
                <div class="row"><h5 class="text-left col-lg-6">Total</h5>
                  <h5 class="text-right col-lg-6">
                    <?php if($SearchQueryParameter == 100) : ?> GHS 10.00 <?php endif; ?>
                    <?php if($SearchQueryParameter == 250) : ?> GHS 25.00 <?php endif; ?>
                    <?php if($SearchQueryParameter == 1000) : ?> GHS 50.00 <?php endif; ?>
                    <?php if($SearchQueryParameter == 2000) : ?> GHS 100.00 <?php endif; ?>
                  </h5>
                </div>
              </div>
            </form>
          </div>

          <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
            <form action="donation.php?id=<?php echo $SearchQueryParameter; ?>" method="post" role="form" class="php-email-form">
            <?php $SearchQueryParameter = $_GET["id"]; ?>
              
              <div class="form-group">
                <label for="name">Your Full Name*</label>
                  <input type="text" name="name" class="form-control" id="name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                  <div class="validate"></div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Your Email*</label>
                  <input type="email" class="form-control" name="email" id="email" data-rule="email" data-msg="Please enter a valid email" />
                  <div class="validate"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="name">Phone Number*</label>
                  <input type="tel" class="form-control" name="phone" id="phone" data-rule="minlen:10" data-msg="Please enter a valid phone number" />
                  <div class="validate"></div>
                </div>
              </div>

              <div class="mb-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Payment Successful. Thank you for your support!</div>
              </div>
              <div class="text-right"><button type="submit" name="Submit">Pay</button></div>
            </form>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>BRIDGE</h3>
            <p>
              Xxx Street <br>
              Xxx<br>
              Accra, Ghana <br>
              <strong>Phone:</strong> +1 5589 55488 55<br>
              <strong>Email:</strong> info@bridge.com.gh<br>
            </p>
          </div>

          <div class="col-lg-6 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Official Rules</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of Use</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy Policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Social Networks</h4>
            <p>Follow us</p>
            <div class="social-links mt-3">
              <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="#" class="google-plus"><i class="bx bxl-youtube"></i></a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container footer-bottom clearfix">
      <div class="copyright">
        &copy; 2020 <strong><span>Bridge</span></strong> Ltd.
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top"><i class="ri-arrow-up-line"></i></a>
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>