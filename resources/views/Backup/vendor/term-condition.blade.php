@php ($siteSettingList=App\WebsiteSetting::getWebsiteSettingAdmin())
<!DOCTYPE html>
<html><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dolovery Seller Landing - Start Selling</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>


    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!--global css starts-->






	 <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/lib/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/lib/font-awesome/css/font-awesome.min.css') }}">
  <!--  <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/lib/select2/css/select2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/style.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/responsive.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/jquery-ui.css') }}">-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/sellerlanding/css/landing/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/sellerlanding/css/template.css') }}">
		   <link rel="stylesheet" type="text/css" href="{{asset('assets/default/css/smart-forms.css')}}" />


	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">



     <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
<style>
.terms-div p {
	font-size:13px;
	line-height:24px;
	margin-bottom:10px;
	color:#666;
	}
.terms-div ul,.terms-div ol {
	list-style:inside circle !important;
	padding-left:3px;
	}

.terms-div ul li,.terms-div ol li {
	font-size:13px;
	color:#666;
	line-height:24px;

	}
.terms-div h4 {
	color:#555;
	padding:15px 0;
	font-weight:700;
	font-size:14px;
	}	
.terms-div {
    background: #f5f5f5 none repeat scroll 0 0;
    height: 700px;
    overflow: auto;
    padding: 10px;
	border:solid 1px #ddd;
}
.formmessage {
	 color: red;
    position: absolute;
    top: -15px;
	}
.m-div {
	line-height:16px;
	}					
</style> 

    <!--end of global css-->
    <!--page level css-->

    @yield('header_styles')
    <!--end of page level css-->
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

<div class="lp-element lp-pom-root" id="lp-pom-root">
<div xmlns="" id="lp-pom-root-color-overlay1"></div>

<div class="lp-positioned-content1">

	     <!-- page wapper-->
<div class="columns-container account-bg">
  <div class="container" id="columns">
  
    <div class="ac-menu">
      <div class="column col-xs-12 col-sm-12" id="left_column" style="background:#f2f2f2;">
     
        <div class="">
        <h3 class="page-heading"><i class="fa fa-user"></i>Seller Account Registration </h3>

<ul id="wizardStatus">
   <li><span class="stap">1</span> Basic Information</li>
  <li  class="current"> <span class="stap">2</span> Terms & Condition</li>
  <li><span class="stap">3</span> Success</li>

</ul>
@include('notifications')
 
	   
<div class="basic">
<div class="panel">
 <div class="panel-heading"><h3>TERMS OF USE FOR SELLERS-AT Dolovery</h3></div>

  <div class="col-sm-12">  <div class="form-group">
  
    <div class="col-sm-12">
<div class="terms-div">
<p>This policy is a part of our Terms of Use. By opening a Dolovery shop, you’re agreeing to this policy and our Terms of Use.</p>
<p><strong>Representing Yourself, Your Shop and Your Listings Honestly:-</strong></p>
<p>At Dolovery, we value transparency. It means that you honestly and accurately represent yourself, your items and your business.</p>
<p><strong>By selling on Dolovery, you agree that you will:</strong></p>
<ol>
<li> Provide honest, accurate information on your ‘About’ page.</li>
<li> Honor your Shop Policies.</li>
<li>Accurately represent your items in listings and listing photos. If you are selling craft supplies, accurately disclose whether they are handmade, vintage or commercial (not handmade or vintage) and whether they are organic or recycled, as well as the location of manufacture (if known).</li>
<li> Respect the intellectual property of others. If you feel someone has violated your intellectual property rights, you can report it to Dolovery.</li>
<li> Not engage in fee avoidance.</li>
<li> Don’t create duplicate shops. Please see Multiple Shops on Dolovery for more details.</li>

</ol>
<h4>Communicating with Other Dolovery Members:-</h4>
<p><strong>Conversations</strong></p>
<p>You can use Dolovery Conversations (“Convos”) to communicate directly with your buyers or other Dolovery members. Conversations are a great way for buyers to ask you questions about an item or an order.</p>
<p><strong>Conversations may not be used for the following activities:</strong></p>
<ul>
<li>Sending unsolicited advertising or promotions, requests for donations or spam.</li>
<li> Harassing or abusing another member.</li>
<li>Contacting someone after they have explicitly asked you not to.</li>
<li>Interfering with a transaction or the business of another member.</li>

</ul>
<h4>Interference</h4>
<p>Interference occurs when a member intentionally interferes with another member’s shop in order to drive away their business. Interference is strictly prohibited on Dolovery. Examples of interference include:</p>
<ul>
<li>Contacting another member via Dolovery Conversations to warn them away from a particular member, shop or item;</li>
<li> Posting in public areas to demonstrate or discuss a dispute with another member;</li>
<li> Purchasing from a seller for the sole purpose of leaving a negative review;</li>
<li> Maliciously clicking on a competitor's Promoted Listings ads in order to drain that member's advertising budget, also known as 'click fraud'.</li>

</ul>

<h4>Harassment</h4>
<p>Any use of Dolovery Conversations to harass other members is strictly prohibited. Similarly, Conversations may not be used to support or glorify hatred toward, or otherwise demean people based upon race, ethnicity, religion, gender, gender identity, disability or sexual orientation. If you receive a Convo that violates this policy, please let us know right away.</p>



<h4>Emails</h4>
<p>You may receive a buyer’s email address or other information as a result of entering into a transaction with that buyer. This information may only be used for Dolovery-related communications or for Dolovery-facilitated transactions. You may not use this information for unsolicited commercial messages or unauthorized transactions. Without the buyer’s explicit consent, you may not add any Dolovery member to your email or physical mailing list or store or misuse any payment information. For more information, please see our Privacy Policy. 
</p>

<h4>Creating and Uploading Content:-</h4>
<p>As a member of Dolovery, you have the opportunity to create and upload a variety of content, like usernames, listings, Convos, text, photos, and videos. In order to keep our community safe and respectful, you agree that you will not upload content that is:</p>
<ul>
<li>Abusive, threatening, defamatory, or harassing;</li>
<li> Obscene or vulgar;</li>
<li>In violation of someone else’s privacy or intellectual property rights;</li>
<li>False, deceptive, or misleading.</li>
</ul>


<h4>Building a Positive Reputation Through our Reviews System:-</h4>
<p>Reviews are a great way for you to build a reputation on Dolovery. Buyers can leave a review, including a one to five-star rating and a photograph of their purchase, within 60 days after their item’s expected delivery date. The estimated delivery date is the purchase date + processing time + shipping time. Buyers can edit their review, including the photograph, any number of times during that 60 day period.</p>
<p>On the rare occasion you receive an unfavorable review, you can reach out to the buyer or, if the review is less than 3 stars, leave a response.</p>

<h4>Reviews and your response to reviews may not:</h4>
<ul>
<li> Contain private information;</li>
<li> Contain obscene, racist, or harassing language or imagery;</li>
<li> Contain prohibited medical drug claims;</li>
<li> Contain advertising or spam;</li>
<li> Be about things outside the seller’s control, such as a shipping carrier, Dolovery or a third party;</li>
<li> Undermine the integrity of the Reviews system.</li>
</ul>



<h4>Extortion</h4>
<p>Extortion is not allowed on Dolovery. Any attempt to manipulate reviews through threats, intimidation, or bribery is considered extortion and is strictly prohibited on Dolovery. Extortion includes when a seller offers a buyer additional goods, services, or compensation in exchange for a positive review. For more information, please see this Help article.</p>


<h4>Shilling</h4>
<p>Shilling is strictly prohibited on Dolovery. Shilling is the fraudulent inflation of a shop’s reputation by use of an alternate account. The intent of shilling is to make a seller look more desirable by increasing the shop’s number of sales and overall review score. Not only does it violate our core value of transparency, but it is considered to be a deceptive business practice by the US Federal Trade Commission. Reviews must reflect the honest, unbiased opinions, findings, beliefs or experience of the buyer.</p>

<h4>Providing Great Customer Service:-</h4>
<p>We expect our sellers to provide a high level of customer service. By selling on Dolovery, you agree to:</p>
<ul>
<li> Honor your shipping and processing times. Sellers are obligated to ship an item or otherwise complete a transaction with a buyer in a prompt manner unless there is an exceptional circumstance. Please be aware that legal requirements for shipping times vary by country. These requirements are detailed in our Shipping Policy.</li>
<li> Respond to Conversations in a timely manner.</li>
<li> Honor the commitments you make in your shop policies.</li>
<li> Resolve disagreements or disputes directly with the buyer. In the unlikely event that you can’t reach a resolution, our Trust and Safety team can help through our case system. Read about your rights and responsibilities regarding cases here.</li>
<li> If you are unable to complete an order, you must notify the buyer and cancel the order. Read about how to cancel an order in this Help article.</li>

</ul>

<p>Dolovery will help you provide great customer service and maintain trust with your buyers through our Seller Service Level Standards (“SLS.”) Read more about SLS here.</p>
<p>Dolovery also provides limited protection to sellers who meet the requirements of our Seller Protection Program. Read more about Dolovery’s Seller Protection Programme here.</p>

<h4>Responding to Requests for Cancellations, Returns and Exchanges:-</h4>
<p>Please be aware that in addition to this policy, each country has its own laws surrounding shipping, cancellations, returns and exchanges. Please familiarize yourself with the laws of your own country and those of your buyers’ countries.
</p>

<h4>Cancellations</h4>
<p>If you are unable to complete a transaction, you must notify the buyer via Dolovery Conversations and cancel the transaction. If the buyer already submitted payment, you must issue a full refund. You are encouraged to keep proof of any refunds in the event a dispute arises.</p>



<h4>You may cancel a transaction under the following circumstances:</h4>
<ul>
<li> The buyer did not pay. (The seller may flag a buyer for a payment not received, chargeback or canceled payment.)</li>
<li> Both you and the buyer agree to cancel the transaction prior to shipment, and you have issued the buyer a full refund.</li>
<li> You have decided to refuse service to the buyer, and if the buyer has already paid, you have issued a full refund, including shipping.</li>
<li> The buyer did not receive the item(s) ordered, even though you provided proof of shipping, and you have issued a refund for the item. (Refunding shipping is optional, unless the buyer paid with Dolovery Payments, in which case you'll need to refund in full.)</li>
<li> Both you and the buyer agreed that the buyer could return the item for a refund, you have received the returned item and issued a refund to the buyer for the item. (Refunding shipping is optional, unless the buyer paid with Etsy Payments, in which case you will need to refund in full.)</li>
</ul>

<h4>Dolovery’s Case System</h4>
<p>We ask buyers to contact a seller directly and attempt to resolve any outstanding issues before opening a case on Dolovery. For this reason, it is important that you fill out your shop policies and regularly respond to Conversations from your buyers.</p>

<p>Buyers may file a case for a non-delivery or a not-as-described item. You must respond to any open cases within seven days.</p>

<p>A <strong>Non-Delivery</strong> occurs when a buyer places an order and submits payment, but does not receive the item. The following are examples of Non-Delivery cases:</p>
<strong>(a).</strong> An item was never sent.<br>
<strong>(b). </strong>An item was sent to an address that is not on the Dolovery receipt.<br>
<strong>(c).</strong> There is no proof that the item was shipped to the buyer’s address.<br>
<br>
<p>An item is<strong> Not as Described</strong> if it is materially different from your listing description or your photos. The following are examples of<strong> Not as Described</strong> cases:</p>
<ul>
<li> The item received is a different color, model, version or size than is shown in the photo or described in the listing.</li>
<li> The item has a different design or material.</li>
<li>The item was advertised as authentic but is not authentic.</li>
<li> You failed to disclose the fact that an item is damaged or is missing parts.</li>
<li> The condition of the item is misrepresented. For example, the description at the time of purchase said the item was “new” and the item is used.</li>
</ul>

<br>
<p><strong>Not as Described cases can also be filed for late delivery.</strong> In order to qualify for late delivery, the buyer must provide proof that all of these conditions have been met:</p>
<ul>
<li> The item(s) were ordered for a specific date or event.</li>
<li>A deadline was agreed upon by the buyer and seller.</li>
<li>The item(s) are rendered useless after that date.</li>
</ul>
</div>
    </div>
  </div></div>
  <div class="clr"></div>
  <br>
  <br>
 <form class=" smart-forms form-horizontal c-register colum-manage ajaxform" method="post" action="{{route('seller.term-condition')}}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
		 <div class="col-sm-10">

		  <div class="form-group ">
			<div class=" col-sm-10">
            <label class=" m-div" style="font-size:14px;">
			  <input type="checkbox" name="agree_terms" value="Yes" style="vertical-align:middle; margin-right:8px;">  By clicking on checkbox <span style="color:#F36;">I agree that</span><br>
             &nbsp; &nbsp;  &nbsp; &nbsp; I have read and understood all the terms and conditions and agreeing & accept all of them
			 </label>
			</div>
           
		  </div>

		  </div>
		   <div class="col-sm-2">

		  <div class="form-group">
			<div class=" col-sm-10">
		   
			<button type="submit" >  <a class="btn btn-pink btn-sign">Next >></a></button>
			</div>
		  </div>

		  </div>
</form>


        <div class="clr"></div>
        </div>


      </div>

     <div class="clr"></div>
    </div>
    <!-- ./row-->
  </div>
  <div class="clr"></div>
</div>
</div>
<!-- ./page wapper-->


	
	
		<script src="{{ asset('assets/default/lib/jquery/jquery-1.11.2.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/default/lib/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/default/js/toastr.min.js') }}" type="text/javascript"></script>
		<script type="text/javascript" src="{{ asset('assets/default/js/formClass.js') }}"></script>
		<script type="text/javascript" src="{{ asset('assets/default/js/jquery.form.js') }}"></script>
		<script src="//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
</div>
</div>
</div>
</body>
</html>
