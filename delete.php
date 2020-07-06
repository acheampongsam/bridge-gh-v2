<?php
require_once("assets/php/DB.php");
$SearchQueryParameter = $_GET["id"];
if(isset($_POST["Delete"])){
    // Grab from input form
    $id = $_POST["id"];
    $fullName = $_POST["name"];
    $eMail = $_POST["email"];
    $phoneNumber = $_POST["phone"];
    $ticketType   = $_POST["tickettype"];
    $orderNumber    = $_POST["ordernumber"];
    $purchaseDate = $_POST["purchasedate"];
    $comment = $_POST["comment"];
    // Database connection
    global $ConnectingDB;
    
    // retrive current stats
    $sql = "SELECT validTickets, decomTickets FROM raffle_stats WHERE PK=1";
    $stmt = $ConnectingDB -> query($sql);
    $row = $stmt->fetch();
    $x = $row['validTickets'];
    $y = $row['decomTickets'];
    //count no. of valid tickets
    // echo $orderNumber;
    $sql = "SELECT COUNT(orderNumber) FROM valid_ticket_records WHERE orderNumber='$orderNumber'";
    $stmt = $ConnectingDB -> query($sql);
    $temp = $stmt->fetchColumn();
    // echo $temp;
    $x = $x - $temp;
    $y = $y + $temp;
    // Update raffle_stats Table (validTickets subtraction)
      $update_raffle_stats = "UPDATE raffle_stats SET validTickets='$x', decomTickets='$y' WHERE PK=1";
      $Execute = $ConnectingDB -> query($update_raffle_stats);
    // Paymet info insertion
    $sqlA = "SELECT * FROM valid_ticket_records WHERE orderNumber='$orderNumber'";
                      $stmtA = $ConnectingDB->query($sqlA);
                      while($DataRows = $stmtA->fetch()){
                          $id           = $DataRows['id'];
                          $fullName     = $DataRows['fullName'];
                          $eMail        = $DataRows['eMail'];
                          $phoneNumber  = $DataRows['phoneNumber'];
                          $ticketType   = $DataRows['ticketType'];
                          $orderNumber    = $DataRows['orderNumber'];
                          $purchaseDate = $DataRows['purchaseDate'];
                      
                          $sqlB = "INSERT INTO decom_tickets(id,fullName,eMail,phoneNumber,ticketType,orderNumber,purchaseDate,comment) 
                          VALUES(:iD, :fnamE, :emaiL, :phonE, :tickettypE, :ordernumbeR, :purchasedatE, :commenT)";
                          $stmtB = $ConnectingDB -> prepare($sqlB);

                          $stmtB -> bindValue(':iD', $id);
                          $stmtB -> bindValue(':fnamE', $fullName);
                          $stmtB -> bindValue(':emaiL', $eMail);
                          $stmtB -> bindValue(':phonE', $phoneNumber);
                          $stmtB -> bindValue(':tickettypE', $ticketType);
                          $stmtB -> bindValue(':ordernumbeR', $orderNumber);
                          $stmtB -> bindValue(':purchasedatE', $purchaseDate);
                          $stmtB -> bindValue(':commenT', $comment);

                          $Execute = $stmtB->execute();
                      }
    // Finally, delete from valid_tickets_records
    $sql = "DELETE FROM valid_ticket_records WHERE orderNumber='$orderNumber'";
    $Execute = $ConnectingDB -> query($sql);

    if($Execute){
      echo '<script>window.open("dashboard.php?id=Record Deleted Successfully","_self")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Delete Record</title>
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
          <h2>Delete Record</h2>
        </div>

        <div class="row align-center">
            <?php 
            global $ConnectingDB;
            $sql = "SELECT * FROM valid_ticket_records WHERE id='$SearchQueryParameter'";
            $stmt = $ConnectingDB->query($sql);
            while($DataRows = $stmt->fetch()){
                $id           = $DataRows['id'];
                $fullName     = $DataRows['fullName'];
                $eMail        = $DataRows['eMail'];
                $phoneNumber  = $DataRows['phoneNumber'];
                $ticketType   = $DataRows['ticketType'];
                $orderCode    = $DataRows['orderNumber'];
                $purchaseDate = $DataRows['purchaseDate'];
            }
            ?>
          <div class="col-lg-12 mt-5 mt-lg-0 d-flex align-items-stretch">
            <form action="delete.php?id=<?php echo $SearchQueryParameter; ?>" method="post" role="form" class="php-email-form">
              
            <div class="form-row">
              <div class="form-group col-md-2">
                    <label for="name">ID:</label>
                    <input type="text" name="id" value="<?php echo $id; ?>" class="form-control" id="name" data-rule="minlen:4" data-msg="Please enter at least 4 chars"  />
                    <div class="validate"></div>
                </div>
                <div class="form-group col-md-10">
                    <label for="name">Your Full Name:</label>
                    <input type="text" name="name" value="<?php echo $fullName; ?>" class="form-control" id="name" data-rule="minlen:4" data-msg="Please enter at least 4 chars"  />
                    <div class="validate"></div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Your Email:</label>
                  <input type="email" class="form-control" value="<?php echo $eMail; ?>" name="email" id="email" data-rule="email" data-msg="Please enter a valid email"  />
                  <div class="validate"></div>
                </div>
                <div class="form-group col-md-6">
                  <label for="name">Phone Number:</label>
                  <input type="tel" class="form-control" value="<?php echo $phoneNumber; ?>" name="phone" id="phone" data-rule="minlen:10" data-msg="Please enter a valid phone number"  />
                  <div class="validate"></div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="name">Ticket Type:</label>
                  <input type="number" class="form-control" value="<?php echo $ticketType; ?>" name="tickettype" id="email" data-rule="" data-msg="Please enter a valid email"  />
                  <div class="validate"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="name">Order #:</label>
                  <input type="text" class="form-control" value="<?php echo $orderCode; ?>" name="ordernumber" id="phone" data-rule="" data-msg="Please enter a valid phone number"  />
                  <div class="validate"></div>
                </div>
                <div class="form-group col-md-4">
                  <label for="name">Purchase Date:</label>
                  <input type="text" class="form-control" value="<?php echo $purchaseDate; ?>" name="purchasedate" id="phone" data-rule="" data-msg="Please enter a valid phone number"  />
                  <div class="validate"></div>
                </div>
              </div>
              <div class="form-group">
                  <label for="name">Comment:</label>
                  <input type="text" class="form-control" value="disqualified" name="comment" id="email" data-rule="email" data-msg="Please enter a valid email"  />
                  <div class="validate"></div>
              </div>
              <div class="mb-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Payment Successful. Thank you for your support!</div>
              </div>
                <div class="text-right">
                  <button type="submit" name="Delete">Delete Record</button>
                </div>

            </form>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

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