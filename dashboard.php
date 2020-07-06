<?php
require_once("assets/php/DB.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin Dashboard</title>
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
      <div class="container" >

        <div class="section-title">
          <h2>Valid Tickets</h2>
            <div class="text-center">
                <input class="form-control secondary col-lg-12" type="text" id="myInput" onkeyup="myFunction()" placeholder="Search by Order Code.." title="Type in a name">
            </div>
        </div>
        
        <strong><?php echo @$_GET["id"]; ?></strong>

            <div class="col-lg-12 table-responsive mt-5 mt-lg-0 d-flex align-items-stretch" id="myTable">
              
                  <table id="dtVerticalScrollExample" class="table table-bordered table-sm" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Ticket Type (GHS)</th>
                            <th>Order Code</th>
                            <th>Purchase Date</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                      </thead>
                      <!-- sql fetch query -->
                      <?php 

                      global $ConnectingDB;

                      $sql = "SELECT * FROM valid_ticket_records";
                      $stmt = $ConnectingDB->query($sql);
                      while($DataRown = $stmt->fetch()){
                          $id           = $DataRown['id'];
                          $fullName     = $DataRown['fullName'];
                          $eMail        = $DataRown['eMail'];
                          $phoneNumber  = $DataRown['phoneNumber'];
                          $ticketType   = $DataRown['ticketType'];
                          $orderCode    = $DataRown['orderNumber'];
                          $purchaseDate = $DataRown['purchaseDate'];
                      ?>
                      <tbody>
                          <tr>
                              <td><?php echo $id; ?></td>
                              <td><?php echo $fullName; ?></td>
                              <td><?php echo $eMail; ?></td>
                              <td><?php echo $phoneNumber; ?></td>
                              <td><?php echo $ticketType; ?></td>
                              <td><?php echo $orderCode; ?></td>
                              <td><?php echo $purchaseDate; ?></td>
                              <td><a href="update.php?id=<?php echo $id; ?>" type="button" class="btn btn-success">Update</button></td>
                              <td><a href="delete.php?id=<?php echo $id; ?>" type="button" class="btn btn-danger">Delete</a></td>
                          </tr>
                      </tbody>
                      <?php } ?>
                  </table>

                    <script>
                        function myFunction() {
                            var input, filter, table, tr, td, i, txtValue;
                            input = document.getElementById("myInput");
                            filter = input.value.toUpperCase();
                            table = document.getElementById("myTable");
                            tr = table.getElementsByTagName("tr");
                            for (i = 0; i < tr.length; i++) {
                                td = tr[i].getElementsByTagName("td")[2, 5];
                                if (td) {
                                txtValue = td.textContent || td.innerText;
                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                    tr[i].style.display = "";
                                } else {
                                    tr[i].style.display = "none";
                                }
                                }       
                            }
                        }
                    </script>
            </div>

      </div>

      <!-- ======= Summary Stats ======= -->
      <br>
    <section id="cliens" class="cliens section-bg info">
      <div class="container">

        <div class="row" data-aos="zoom-in">
          
          <?php 
          global $ConnectingDB;
          $sql = "SELECT soldTickets FROM raffle_stats";
          $stmt = $ConnectingDB->query($sql);
          while($DataRows = $stmt->fetch()){
                $ticketCount = $DataRows['soldTickets'];
          ?>
          <div class="col-lg-4 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <h4>Sold Tickets: <strong><?php echo $ticketCount; ?></strong></h4>
          </div>
          <?php } ?>

          <?php 
          global $ConnectingDB;
          $sql = "SELECT orders FROM raffle_stats";
          $stmt = $ConnectingDB->query($sql);
          $totalOrders = [];
          while($DataRows = $stmt->fetch()){
                $orderCount = $DataRows['orders'];
                array_push($totalOrders, $orderCount); 
          }?>
          <div class="col-lg-4 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <h4># of Orders: <strong><?php echo array_sum($totalOrders); ?></strong></h4>
          </div>

          <?php 
          global $ConnectingDB;
          //$sql = "SELECT SUM(ticketType) AS 'sales' FROM valid_ticket_records GROUP BY orderNumber";
          $sql = "SELECT netSales FROM raffle_stats";
          $stmt = $ConnectingDB->query($sql);
          $totalSales = [];
          while($DataRows = $stmt->fetch()){
                $sale = $DataRows['netSales'];
                array_push($totalSales, $sale); 
          }
          ?>
          <div class="col-lg-4 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <h4>Total Sales: <strong><?php echo "GHS " . array_sum($totalSales); ?></strong></h4>
          </div>

        </div>

      </div>
    </section>
    <br>
    <section id="cliens" class="cliens section-bg info">
      <div class="container">

        <div class="row" data-aos="zoom-in">
          
          <?php 
          global $ConnectingDB;
          $sql = "SELECT validTickets FROM raffle_stats";
          $stmt = $ConnectingDB->query($sql);
          while($DataRows = $stmt->fetch()){
                $ticketCount = $DataRows['validTickets'];
          ?>
          <div class="col-lg-6 col-md-6 col-6 d-flex align-items-center justify-content-center">
            <h4>Valid Tickets: <strong><?php echo $ticketCount; ?></strong></h4>
          </div>
          <?php } ?>

          <?php 
          global $ConnectingDB;
          $sql = "SELECT decomTickets FROM raffle_stats";
          $stmt = $ConnectingDB->query($sql);
          $totalOrders = [];
          while($DataRows = $stmt->fetch()){
                $orderCount = $DataRows['decomTickets'];
                array_push($totalOrders, $orderCount); 
          }?>
          <div class="col-lg-6 col-md-6 col-6 d-flex align-items-center justify-content-center">
            <h4>Decom. Tickets: <strong><?php echo array_sum($totalOrders); ?></strong></h4>
          </div>

        </div>

      </div>
    </section>
    <br>
    <!-- Summary Stats -->

    <!-- ======= RUN RAFFLE ======= -->
    <footer id="footer">
        <div class="footer-newsletter">
        <div class="container">
            <div class="row justify-content-center">
            <div class="col-lg-8">
                <h4>SELECT A WINNER</h4>
                <p>Clicking the "Raffle" button randomly selects a ticket from the database of valid tickets</p>
                <a href="winner.php" type="button" class="btn btn-outline-success btn-lg"><strong>--- RAFFLE ---</strong></a>
            </div>
            </div>
        </div>
        </div>
    </footer>
    <br>
      <!-- Invalid Tickets -->
      <div class="container" >

        <div class="section-title">
          <h2>Decomissioned Tickets</h2>
        </div>

            <div class="col-lg-12 table-responsive mt-5 mt-lg-0 d-flex align-items-stretch" id="myTable">
              
                  <table id="dtVerticalScrollExample" class="table table-bordered table-sm" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Ticket Type (GHS)</th>
                            <th>Order Code</th>
                            <th>Purchase Date</th>
                            <th>Decom. Date</th>
                            <th>Comment</th>
                        </tr>
                      </thead>
                      <!-- sql fetch query -->
                      <?php 

                      global $ConnectingDB;

                      $sql = "SELECT * FROM decom_tickets";
                      $stmt = $ConnectingDB->query($sql);
                      while($DataRows = $stmt->fetch()){
                            $id           = $DataRows['id'];
                            $fullName     = $DataRows['fullName'];
                            $eMail        = $DataRows['eMail'];
                            $phoneNumber  = $DataRows['phoneNumber'];
                            $ticketType   = $DataRows['ticketType'];
                            $orderCode    = $DataRows['orderNumber'];
                            $purchaseDate = $DataRows['purchaseDate'];
                            $decomDate    = $DataRows["decomDate"];
                            $comment      = $DataRows["comment"];
                      ?>
                      <tbody>
                          <tr>
                              <td><?php echo $id; ?></td>
                              <td><?php echo $fullName; ?></td>
                              <td><?php echo $eMail; ?></td>
                              <td><?php echo $phoneNumber; ?></td>
                              <td><?php echo $ticketType; ?></td>
                              <td><?php echo $orderCode; ?></td>
                              <td><?php echo $purchaseDate; ?></td>
                              <td><?php echo $decomDate; ?></td>
                              <td><?php echo $comment; ?></td>
                          </tr>
                      </tbody>
                      <?php } ?>
                  </table>
            </div>

      </div>
      <!-- Invalid Tickets end -->

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