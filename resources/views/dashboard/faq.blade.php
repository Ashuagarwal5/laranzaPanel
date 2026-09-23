@extends('dashboard.layout.default')
@section('title','FAQ\'s')
@section('content')
<div class="profile-box d-flex align-items-center justify-content-center animate__animated animate__zoomInDown">
<div class="row g-4 position-relative">
                            
                            
                            <div class="col">
                                <div class="p-2 text-center">
                                    <h3 class="text-white mb-1 pt-4">Frequently asked questions</h3>
                                    <p class="text-white-75 mb-1">If you can not find answer to your question in our FAQ, you can always contact us or email us. We will answer you shortly!</p>
                                    
                                </div>
                            </div>
                           
                            
                           

                        </div>
    </div>
 
 
 
 <div class="row position-relative  px-3" style="margin-top:-80px;">
 <div class="col-lg-4 col-xxl-3">
 <div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
 <div class="card-body">
 <h5 class="card-title mb-4">FAQ's Category</h5>
 <hr>

<div class="d-flex align-items-start">
  <div class="nav flex-column nav-pills me-3 qnav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true"><i class="bi bi-question-circle me-1"></i> General Questions</button>
    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"><i class="bi bi-person-plus me-1"></i> Manage Account</button>
    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false"><i class="bi bi-shield-lock me-1"></i> Privacy & Security</button>
 
  </div>
  
</div>

 </div>
 </div>
 </div>
 <div class="col-lg-8 col-xxl-9">
  <div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
  <div class="card-body p-4">
  <div class="tab-content" id="v-pills-tabContent">
    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
    <div class="mt-0">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="flex-shrink-0 me-1">
                                                <i class="bi bi-question-circle text-success me-1"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="fs-16 mb-0 fw-bold">General Questions</h4>
                                            </div>
                                        </div>

                                        <div class="accordion accordion-border-box" id="genques-accordion">
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="genques-headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseOne" aria-expanded="true" aria-controls="genques-collapseOne">
                                                        What is Lorem Ipsum ?
                                                    </button>
                                                </h2>
                                                <div id="genques-collapseOne" class="accordion-collapse collapse show" aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion">
                                                    <div class="accordion-body">
                                                        If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple their most common words.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="genques-headingTwo">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseTwo" aria-expanded="false" aria-controls="genques-collapseTwo">
                                                        Why do we use it ?
                                                    </button>
                                                </h2>
                                                <div id="genques-collapseTwo" class="accordion-collapse collapse" aria-labelledby="genques-headingTwo" data-bs-parent="#genques-accordion">
                                                    <div class="accordion-body">
                                                        The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="genques-headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseThree" aria-expanded="false" aria-controls="genques-collapseThree">
                                                        Where does it come from ?
                                                    </button>
                                                </h2>
                                                <div id="genques-collapseThree" class="accordion-collapse collapse" aria-labelledby="genques-headingThree" data-bs-parent="#genques-accordion">
                                                    <div class="accordion-body">
                                                        he wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="genques-headingFour">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseFour" aria-expanded="false" aria-controls="genques-collapseFour">
                                                        Where can I get some ?
                                                    </button>
                                                </h2>
                                                <div id="genques-collapseFour" class="accordion-collapse collapse" aria-labelledby="genques-headingFour" data-bs-parent="#genques-accordion">
                                                    <div class="accordion-body">
                                                        Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia. Nam pretium turpis et arcu arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis aliquam ultrices mauris.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                               
                                    </div>
    </div>
    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
    <div class="mt-3">
                                        <div class="d-flex align-items-center my-4">
                                            <div class="flex-shrink-0 me-1">
                                                <i class="bi bi-person-plus me-1 text-success"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="fs-16 mb-0 fw-bold">Manage Account</h4>
                                            </div>
                                        </div>

                                        <div class="accordion accordion-border-box" id="manageaccount-accordion">
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="manageaccount-headingOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseOne" aria-expanded="false" aria-controls="manageaccount-collapseOne">
                                                        Where can I get some ?
                                                    </button>
                                                </h2>
                                                <div id="manageaccount-collapseOne" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingOne" data-bs-parent="#manageaccount-accordion">
                                                    <div class="accordion-body">
                                                        If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple their most common words.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="manageaccount-headingTwo">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseTwo" aria-expanded="true" aria-controls="manageaccount-collapseTwo">
                                                        Where does it come from ?
                                                    </button>
                                                </h2>
                                                <div id="manageaccount-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="manageaccount-headingTwo" data-bs-parent="#manageaccount-accordion">
                                                    <div class="accordion-body">
                                                        The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="manageaccount-headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseThree" aria-expanded="false" aria-controls="manageaccount-collapseThree">
                                                        Why do we use it ?
                                                    </button>
                                                </h2>
                                                <div id="manageaccount-collapseThree" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingThree" data-bs-parent="#manageaccount-accordion">
                                                    <div class="accordion-body">
                                                        he wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="manageaccount-headingFour">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseFour" aria-expanded="false" aria-controls="manageaccount-collapseFour">
                                                        What is Lorem Ipsum ?
                                                    </button>
                                                </h2>
                                                <div id="manageaccount-collapseFour" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingFour" data-bs-parent="#manageaccount-accordion">
                                                    <div class="accordion-body">
                                                        Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia. Nam pretium turpis et arcu arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis aliquam ultrices mauris.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end accordion-->
                                    </div>
    </div>
    <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
    
     <div class="mt-3">
                                        <div class="d-flex align-items-center my-4">
                                            <div class="flex-shrink-0 me-1">
                                             <i class="bi bi-shield-lock text-success me-1"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h4 class="fs-16 mb-0 fw-bold">Privacy &amp; Security</h4>
                                            </div>
                                        </div>

                                        <div class="accordion accordion-border-box" id="privacy-accordion">
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="privacy-headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseOne" aria-expanded="true" aria-controls="privacy-collapseOne">
                                                        Why do we use it ?
                                                    </button>
                                                </h2>
                                                <div id="privacy-collapseOne" class="accordion-collapse collapse show" aria-labelledby="privacy-headingOne" data-bs-parent="#privacy-accordion">
                                                    <div class="accordion-body">
                                                        If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages. The new common language will be more simple and regular than the existing European languages. It will be as simple their most common words.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="privacy-headingTwo">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseTwo" aria-expanded="false" aria-controls="privacy-collapseTwo">
                                                        Where can I get some ?
                                                    </button>
                                                </h2>
                                                <div id="privacy-collapseTwo" class="accordion-collapse collapse" aria-labelledby="privacy-headingTwo" data-bs-parent="#privacy-accordion">
                                                    <div class="accordion-body">
                                                        The new common language will be more simple and regular than the existing European languages. It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="privacy-headingThree">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseThree" aria-expanded="false" aria-controls="privacy-collapseThree">
                                                        What is Lorem Ipsum ?
                                                    </button>
                                                </h2>
                                                <div id="privacy-collapseThree" class="accordion-collapse collapse" aria-labelledby="privacy-headingThree" data-bs-parent="#privacy-accordion">
                                                    <div class="accordion-body">
                                                        he wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border">
                                                <h2 class="accordion-header" id="privacy-headingFour">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseFour" aria-expanded="false" aria-controls="privacy-collapseFour">
                                                        Where does it come from ?
                                                    </button>
                                                </h2>
                                                <div id="privacy-collapseFour" class="accordion-collapse collapse" aria-labelledby="privacy-headingFour" data-bs-parent="#privacy-accordion">
                                                    <div class="accordion-body">
                                                        Cras ultricies mi eu turpis hendrerit fringilla. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia. Nam pretium turpis et arcu arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis aliquam ultrices mauris.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                   
                                    </div>                               
    </div>

  </div>
  </div>
  </div>
 
 
 </div>
 
 
 </div>     



 
@endsection