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
<title>My Profile</title>
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
                            <div class="col-auto">
                                <div class="avatar-lg">
                                    <img src="images/avatar-1.jpg" alt="user-img" class="img-thumbnail rounded-circle">
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="p-2">
                                    <h3 class="text-white mb-1">Anna Adame</h3>
                                    <p class="text-white-75 mb-1">Owner &amp; Founder</p>
                                    <div class="hstack text-white-50 gap-1">
                                        <div class="me-2"><i class="bi bi-geo-alt me-1 text-white-75 fs-16"></i>California, United States</div>
                                        <div>
                                            <i class="bi bi-building me-1 text-white-75 fs-16"></i>Themesbrand
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            <!--<div class="col-12 col-lg-auto order-last order-lg-0">
                                <div class="row text text-white-50 text-center">
                                    <div class="col-lg-6 col-4">
                                        <div class="p-2">
                                            <h4 class="text-white mb-1">24.3K</h4>
                                            <p class="fs-14 mb-0 text-white-75">Followers</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-4">
                                        <div class="p-2">
                                            <h4 class="text-white mb-1">1.3K</h4>
                                            <p class="fs-14 mb-0 text-white-75">Following</p>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                           

                        </div>
    </div>
      
<div class="row position-relative  px-3" style="margin-top:-80px;">
<div class="col-sm-12">
<div class="d-flex mb-3">
                                    
                                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                                            </a>
                                        </li>
                                   <!--  <li class="nav-item">
                                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab">
                                                <i class="ri-list-unordered d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Activities</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                                <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Projects</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab">
                                                <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Documents</span>
                                            </a>
                                        </li>-->
                                    </ul>
                                    <div class="flex-shrink-0">
                                        <a href="editprofile.html" class="btn btn-outline-light rounded-pill px-3"><i class="bi bi-pencil-square"></i> Edit Profile</a>
                                    </div>
                                </div>
</div>
<div class="col-lg-4  col-xxl-3">

<div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-3">Person Info</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Full Name :</th>
                                                                        <td class="text-muted">Anna Adame</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Mobile :</th>
                                                                        <td class="text-muted">+(1) 987 6543</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">E-mail :</th>
                                                                        <td class="text-muted">daveadame@velzon.com</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Location :</th>
                                                                        <td class="text-muted">California, United States
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Joining Date</th>
                                                                        <td class="text-muted">24 Nov 2021</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div><!-- end card body -->
                                                </div>
<div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-0">Refer & Earn</h5>
                                        </div>
                                        
                                    </div>
                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                                            <img class="img-fluid" src="images/connections.png"/>
                                        </div>
                                        <div class="flex-fill">
                                        <small>Referral Code</small>
                                       <h4 class="fw-bold text-danger">5J640D458</h4>
                                       </div>
                                       <img style="width:50px;" class="img-fluid" src="images/qrcode.png"/>
                                    </div>

                                </div>
                            </div>
 <div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-0">Total Earnings</h5>
                                        </div>
                                        
                                    </div>
                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                                            <img class="img-fluid" src="images/wallet.png"/>
                                        </div>
                                        <div class="flex-fill">
                                        <small>Earnings Amount</small>
                                       <h4 class="fw-bold text-success">Rs.25,000</h4>
                                       </div>
                                      <a href="#" class="btn btn-danger rounded-pill"><i class="bi bi-currency-exchange"></i> Withdraw</a>
                                    </div>

                                </div>
                            </div>                                                
</div>
<div class="col-lg-8  col-xxl-9">
<div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3">
                                                    <div class="card-body p-4">
                                                        <h5 class="card-title mb-4">Basic Detail</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row" style="width:110px;">Firm Name :</th>
                                                                        <td class="text-muted">Codespur Software Pvt Ltd</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Firm Mobile :</th>
                                                                        <td class="text-muted">+91 987 6543 500</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Firm E-mail :</th>
                                                                        <td class="text-muted">support@codespursoftware.com</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Photo</th>
                                                                        <td>
                                                                        <div class="img-thumb">
                                                                        <img src="images/mar.jpg" class="img-thumbnail" alt="...">
                                                                        </div>
                                                                         <div class="img-thumb">
                                                                        <img src="images/offic-staff.jpg" class="img-thumbnail" alt="...">
                                                                        </div>
                                                                         <div class="img-thumb">
                                                                        <img src="images/bg2.png" class="img-thumbnail" alt="...">
                                                                        </div>
                                                                         <div class="img-thumb">
                                                                        <img src="images/profile-bg.jpg" class="img-thumbnail" alt="...">
                                                                        </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>





                                                    </div>
                                                </div>
<div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3">
                                                    <div class="card-body p-4">
                                                        <h5 class="card-title mb-4">Firm Address</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row" style="width:110px;">Country :</th>
                                                                        <td class="text-muted">India</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Stats :</th>
                                                                        <td class="text-muted">Rajasthan</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">City :</th>
                                                                        <td class="text-muted">Jaipur</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Location :</th>
                                                                        <td class="text-muted"><strong>Codespur Software Pvt Ltd.</strong><br> F1, 21A,Moti Nagar West Akshardham Mandir Road Chitrakoot, Vaishali Nagar, Jaipur, Rajasthan 302021
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>





                                                    </div>
                                                </div>                                                
<div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3">
                                                    <div class="card-body p-4">
                                                        <h5 class="card-title mb-4">Contact Person</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Person Name :</th>
                                                                        <td class="text-muted">Anna Adame</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Person Mobile :</th>
                                                                        <td class="text-muted">+91-9829012345</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Person E-mail :</th>
                                                                        <td class="text-muted">daveadame@velzon.com</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Person Designation :</th>
                                                                        <td class="text-muted">Owner & Founder
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>





                                                    </div>
                                                </div>                                                
                                                
 <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3">
                                                    <div class="card-body p-4">
                                                        <h5 class="card-title mb-4">Bank Detail</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Beneficiary Name :</th>
                                                                        <td class="text-muted">Anna Adame</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Bank Name :</th>
                                                                        <td class="text-muted">Bank of Baroda</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Bank Branch :</th>
                                                                        <td class="text-muted">vaishali nagar jaipur</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Bank Account Numbers :</th>
                                                                        <td class="text-muted">45600085600
                                                                        </td>
                                                                    </tr>
<tr>
                                                                        <th class="ps-0" scope="row">IFSE Code :</th>
                                                                        <td class="text-muted">BOB456vaishli
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>





                                                    </div>
                                                </div>                                               
 <!--<div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3">
                                                    <div class="card-body p-5">
                                                        <h5 class="card-title mb-4">About Firms</h5>
<p>Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,
Ruler
</p>
<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.</p>
<div class="row">
                                                            <div class="col-6 col-md-4">
                                                                <div class="d-flex mt-4">
                                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary shadow">
                                                                            <i class="bi bi-person"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <p class="mb-1">Anna Adame :</p>
                                                                        <h6 class="text-truncate mb-0">Owner & Founder</h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                           
                                                            <div class="col-6 col-md-4">
                                                                <div class="d-flex mt-4">
                                                                    <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                                        <div class="avatar-title bg-light rounded-circle fs-16 text-primary shadow">
                                                                            <i class="bi bi-globe"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 overflow-hidden">
                                                                        <p class="mb-1">Website :</p>
                                                                        <a href="#" class="fw-semibold">www.codespur.com</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                          
                                                        </div>


                                                    </div>
                                                </div>-->
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