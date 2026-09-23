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
  <title>Edit Profile</title>
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
                                    <div class="col-lg-4 col-4">
                                        <div class="p-2">
                                            <h4 class="text-white mb-1">2500</h4>
                                            <p class="fs-14 mb-0 text-white-75"> Sale</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <div class="p-2">
                                            <h4 class="text-white mb-1">2564</h4>
                                            <p class="fs-14 mb-0 text-white-75">Referral</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <div class="p-2">
                                            <h4 class="text-white mb-1">250</h4>
                                            <p class="fs-14 mb-0 text-white-75"> Referral</p>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>-->
                           

                        </div>
    </div>
      
<div class="row position-relative  px-3" style="margin-top:-80px;">
<!--<div class="col-sm-12" >
<div class="d-flex mb-3">
                                    
                                    <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                                <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
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
                                        </li>
                                    </ul>
                                    <div class="flex-shrink-0">
                                        <a href="pages-profile-settings.html" class="btn btn-outline-light rounded-pill px-3"><i class="bi bi-pencil-square"></i> Edit Profile</a>
                                    </div>
                                </div>
</div>-->
<div class="col-lg-4 col-xxl-3">
<div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3 mt-n5">
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                            <img src="images/avatar-1.jpg" class="rounded-circle avatar-xl img-thumbnail user-profile-image  shadow" alt="user-profile-image">
                                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                <input id="profile-img-file-input" type="file" class="profile-img-file-input">
                                                <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-light text-body shadow">
                                                        <i class="bi bi-camera"></i>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <h4 class="fs-16 mb-1 fw-bold">Anna Adame</h4>
                                        <p class="text-muted mb-0">Owner &amp; Founder</p>
                                    </div>
                                </div>
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
<div class="col-lg-8 col-xxl-9">

 <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">
                                <div class="card-header bg-white px-4 pt-4">
                                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                    <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#firmDetails" role="tab">
                                             Firm Details
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " data-bs-toggle="tab" href="#personalDetails" role="tab">
                                            Contact Person Detail
                                            </a>
                                        </li>
                                          <li class="nav-item">
                                            <a class="nav-link " data-bs-toggle="tab" href="#bankDetails" role="tab">
                                            Bank Details
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                            Change Password
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </div>
                                <div class="card-body p-4">
                                    <div class="tab-content">
                                    <div class="tab-pane active" id="firmDetails" role="tabpanel">
                                            <form action="javascript:void(0);">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Enter Name">
  <label for="floatingInput">Firm Name</label>
</div>
                                                    </div>
                                                 
                                                    
                                                   
                                                    <div class="col-lg-6">
                                                     
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Firm Phone Number">
  <label for="floatingInput">Firm Phone Number</label>
</div>
                                                    </div>
                                                 
                                                    <div class="col-lg-6">
                                                        <div class="form-floating mb-3">
  <input type="email" class="form-control" id="floatingInput" placeholder="Email Address">
  <label for="floatingInput">Firm Email Address</label>
</div>
                                                    </div>
                                                  
                                                    
                                                   
                                                    
                                                    
                                                    
                                                   
                                                    <div class="col-lg-6">
                                                       
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Country">
  <label for="floatingInput">Country</label>
</div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                      
                                                         <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Zip Code">
  <label for="floatingInput">State</label>
</div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                      <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="www.example.com">
  <label for="floatingInput">City</label>
</div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-floating mb-3">
  <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" rows="4" style="height:100px;resize:none;"></textarea>
  <label for="floatingTextarea">Address</label>
</div>
                                                    </div>
                                                    
                                                    <div class="col-lg-12">
                                                    <div class="uploadimg">
                                                    <div class="d-flex align-items-center justify-content-center h-100 w-100 flex-column">
                                                    <input class="fileinput" type="file"/>
                                                    <i class="bi bi-box-arrow-in-down fa-2x text-muted"></i>
                                                   <small class="text-muted"> UPLAD PHOTO</small>
                                                    </div>
                                                    </div>
                                                    <div class="img-thumb small-thumb">
                                                    <a href="#" class="text-danger deletebtn"><i class="bi bi-x-circle-fill fa-2x"></i></a>
                                                                        <img src="images/profile-bg.jpg" class="img-thumbnail" alt="...">
                                                                        </div>
                                                                        <div class="img-thumb small-thumb">
                                                    <a href="#" class="text-danger deletebtn"><i class="bi bi-x-circle-fill fa-2x"></i></a>
                                                                        <img src="images/offic-staff.jpg" class="img-thumbnail" alt="...">
                                                                        </div>
                                                                        <div class="img-thumb small-thumb">
                                                    <a href="#" class="text-danger deletebtn"><i class="bi bi-x-circle-fill fa-2x"></i></a>
                                                                        <img src="images/mar.jpg" class="img-thumbnail" alt="...">
                                                                        </div>
                                                    </div>
                                            
                                                    <div class="col-lg-12 mt-3">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="submit" class="btn btn-primary rounded-pill px-3">Updates</button>
                                                            <button type="button" class="btn btn-soft-success">Cancel</button>
                                                        </div>
                                                    </div>
                                                 
                                                </div>
                                              
                                            </form>
                                        </div>
                                        
                                        <div class="tab-pane" id="personalDetails" role="tabpanel">
                                            <form action="javascript:void(0);">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Enter Name">
  <label for="floatingInput">Person Name</label>
</div>
                                                    </div>
                                                   
                                                    <div class="col-lg-6">
                                                     
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Phone Number">
  <label for="floatingInput">Person Phone Number</label>
</div>
                                                    </div>
                                                 
                                                    <div class="col-lg-6">
                                                        <div class="form-floating mb-3">
  <input type="email" class="form-control" id="floatingInput" placeholder="Email Address">
  <label for="floatingInput">Person Email Address</label>
</div>
                                                    </div>
                                                  
                                                    <div class="col-lg-6">
                                                       
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Designation">
  <label for="floatingInput">Person Designation</label>
</div>
                                                    </div>
                                                   
                                                    
                                                    
                                                    
                                            
                                                    <div class="col-lg-12">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="submit" class="btn btn-primary rounded-pill px-3">Updates</button>
                                                            <button type="button" class="btn btn-soft-success">Cancel</button>
                                                        </div>
                                                    </div>
                                                 
                                                </div>
                                              
                                            </form>
                                        </div>
                                        <div class="tab-pane " id="bankDetails" role="tabpanel">
                                            <form>
<div class="row">
<div class="col-lg-6">
<div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Beneficiary Name">
  <label for="floatingInput">Beneficiary Name</label>
</div>
</div>
<div class="col-lg-6">
<div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Bank Name">
  <label for="floatingInput">Bank Name</label>
</div>
</div>
<div class="col-lg-6">
<div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Bank Branch">
  <label for="floatingInput">Bank Branch</label>
</div>
</div>
<div class="col-lg-6">
<div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Bank Account Number">
  <label for="floatingInput">Bank Account Number</label>
</div>
</div>
<div class="col-lg-6">
<div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="IFSC Code">
  <label for="floatingInput">IFSC Code</label>
</div>
</div>
<div class="col-lg-12 mt-3">
 <div class="hstack gap-2 justify-content-end">
 <button type="submit" class="btn btn-primary rounded-pill px-3">Submit Detail</button>
   <button type="button" class="btn btn-soft-success">Cancel</button>
   </div>
  </div>
</div>




</form>
                                        </div>
                                        <div class="tab-pane" id="changePassword" role="tabpanel">
                                            <form action="javascript:void(0);">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-floating mb-3">
  <input type="password" class="form-control" id="floatingInput" placeholder="Password">
  <label for="floatingInput">Old Password*</label>
</div>
                                                    </div>
                                                 
                                                    <div class="col-lg-4">
                                                        
                                                         <div class="form-floating mb-3">
  <input type="password" class="form-control" id="floatingInput" placeholder="Password">
  <label for="floatingInput">New Password*</label>
</div>
                                                    </div>
                                                  
                                                    <div class="col-lg-4">
                                                        
                                                         <div class="form-floating mb-3">
  <input type="password" class="form-control" id="floatingInput" placeholder="Password">
  <label for="floatingInput">Confirm Password*</label>
</div>
                                                    </div>
                                                  
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <a href="javascript:void(0);" class="link-primary text-decoration-underline">Forgot Password ?</a>
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-lg-12">
                                                        <div class="text-end">
                                                            <button type="submit" class="btn btn-primary rounded-pill px-3">Update Password</button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            
                                            </form>
                                            
                                        </div>
                                      
                                        
                                      
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