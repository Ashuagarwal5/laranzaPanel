<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/animate.min.css">
  <!-- <link rel="stylesheet" href="css/font-awesome.min.css"> -->
  <link rel="stylesheet" href="css/template.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> 
<link href="css/dashboard.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/charts/apexcharts.css">
  <title>My Referrals</title>
</head>

<body style="background:#f4f6fd;">
<header class="navbar navbar-dark sticky-top bg-primary flex-md-nowrap p-0">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#"><img src="images/logo.png"/></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <span class="mx-3 border border-light d-flex align-items-center rounded-pill px-3 bg-light me-auto">
  <i class="bi bi-search"></i>
  <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
  </span>
   <a href="#"><i class="bi bi-bell fa-2x text-white"></i></a>
  <div class="navbar-nav">
 
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="#">Sign out</a>
    </div>
  </div>
</header>

<div class="container-fluid">
  <div class="row">
    <?php include('./left-bar.php')?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    
    <div class="profile-box animate__animated animate__zoomInDown">
<div class="row g-4 position-relative">
                            
                            
                            <div class="col">
                                <div class="p-2 text-center">
                                    <h3 class="text-white mb-1 mt-5">My Referrals</h3>
                                    <p class="text-white-75 mb-1">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. </p>
                                    
                                </div>
                            </div>
                           
                            
                           

                        </div>
    </div>
      
<div class="row position-relative  px-3" style="margin-top:-80px;">


<div class="col-lg-10 mx-auto">

 <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">
                                
                                <div class="card-body p-5">
                                 <div class="table-responsive">
                                 <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col" style="width: 70px;">Sr. No.</th>
      <th scope="col" style="width: 30%;">Full Name</th>
      <th scope="col"> Email </th>
      <th scope="col">Mobile Number </th>
      <th>Join Date   </th>
       <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1.</th>
      <td>
     <div> <strong>Manoj Kumar</strong></div>

</td>
      <td>manojkumar@gmail.com </td>
      <td><strong>9829012345</strong></td>
            <td>12/july/2020</td>
      <td><span class="badge rounded-pill bg-success">Paid</span> </td>
    </tr>
    <tr>
      <th scope="row">2.</th>
      <td>
     <div> <strong>Ashok Kumar</strong></div>

</td>
      <td>ashokkumar@gmail.com </td>
      <td><strong>9829012345</strong></td>
            <td>12/july/2020</td>
      <td><span class="badge rounded-pill bg-primary">Free</span> </td>
    </tr>
    <tr>
      <th scope="row">3.</th>
      <td>
     <div> <strong>Preeti singh</strong></div>

</td>
      <td>preetis@gmail.com </td>
      <td><strong>9829012345</strong></td>
            <td>12/july/2020</td>
      <td><span class="badge rounded-pill bg-success">Paid</span> </td>
    </tr>
    <tr>
      <th scope="row">4.</th>
      <td>
     <div> <strong>Dinesh Singh</strong></div>

</td>
      <td>dinesh@gmail.com </td>
      <td><strong>9829012345</strong></td>
            <td>12/july/2020</td>
      <td><span class="badge rounded-pill bg-primary">Free</span> </td>
    </tr>
    <tr>
      <th scope="row">5.</th>
      <td>
     <div> <strong>Manoj Kumar</strong></div>

</td>
      <td>manojkumar@gmail.com </td>
      <td><strong>9829012345</strong></td>
            <td>12/july/2020</td>
      <td><span class="badge rounded-pill bg-success">Paid</span> </td>
    </tr>
    
  </tbody>
</table>
 </div>   
 </div>
 </div>
</div>
</div>


 
  
    </main>
  </div>
</div>



  <!-- Optional JavaScript -->
  <script src="js/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a57a87ec82.js" crossorigin="anonymous"></script>
  <script src="js/wow.min.js"></script>





<script src="js/charts/apexcharts.min.js"></script>
<script src="js/chart-apex.js"></script>

  <script src="js/app.js"></script>
  <script>

    $(document).ready(function () {
      new WOW().init();
    });
  </script>

  </body>
</body>

</html>