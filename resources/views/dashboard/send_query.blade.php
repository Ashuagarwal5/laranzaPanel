@extends('dashboard.layout.default')
@section('title','Send Query')
@section('content')
<div class="profile-box animate__animated animate__zoomInDown">
<div class="row g-4 position-relative">
                            
                            
                            <div class="col">
                                <div class="p-2 text-center">
                                    <h3 class="text-white mb-1 mt-5">Send Query</h3>
                                    <p class="text-white-75 mb-1">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. </p>
                                    
                                </div>
                            </div>
                           
                            
                           

                        </div>
    </div>
      
<div class="row position-relative  px-3" style="margin-top:-80px;">


<div class="col-lg-10 col-xxl-8 mx-auto">

 <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">
<div class="card-body p-4">

 <h5 class="card-title mb-0">Send Query</h5>
<p class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>                                
 <form action="javascript:void(0);">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Enter Name">
  <label for="floatingInput">First Name</label>
</div>
                                                    </div>
                                                 
                                                    <div class="col-lg-6">
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Enter Name">
  <label for="floatingInput">Last Name</label>
</div>
                                                    </div>
                                                   
                                                    <div class="col-lg-6">
                                                     
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Phone Number">
  <label for="floatingInput">Phone Number</label>
</div>
                                                    </div>
                                                 
                                                    <div class="col-lg-6">
                                                        <div class="form-floating mb-3">
  <input type="email" class="form-control" id="floatingInput" placeholder="Email Address">
  <label for="floatingInput">Email Address</label>
</div>
                                                    </div>
                                                  

                                                    
                                                    <div class="col-lg-4">
                                                      <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="www.example.com">
  <label for="floatingInput">Product Link</label>
</div>
                                                    </div>
                                                   
                                                    <div class="col-lg-4">
                                                       
                                                        <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Country">
  <label for="floatingInput">Country</label>
</div>
                                                    </div>
                                                   
                                                    <div class="col-lg-4">
                                                      
                                                         <div class="form-floating mb-3">
  <input type="text" class="form-control" id="floatingInput" placeholder="Zip Code">
  <label for="floatingInput">Zip Code</label>
</div>
                                                    </div>
                                                
                                                   
                                                    <div class="col-lg-12">
                                                       
                                                        <div class="form-floating mb-3">
  <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" rows="4" style="height:100px;resize:none;"></textarea>
  <label for="floatingTextarea">Your Query</label>
</div>
                                                    </div>
                                            
                                                    <div class="col-lg-12">
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="submit" class="btn btn-primary rounded-pill px-3">Send Query</button>
                                                            <button type="button" class="btn btn-soft-success">Cancel</button>
                                                        </div>
                                                    </div>
                                                 
                                                </div>
                                              
                                            </form>

  </div>
</div>
</div>
</div>

@endsection