<form method="post" id="basic_info" class="ajaxformclass" action="{{ $navi['route'] }}" enctype="multipart/form-data">
    <div class="col-sm-12">
        <div class="alert" style="margin-top:10px;display:none;">
            <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
        </div>
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="form-group col-sm-4">
        <label for="validate-text"> Full Name *</label>
        <input type="text"  class="form-control input-sm" name="full_name"
        value="@if(!empty(old('full_name')!='')){{old('full_name')}}@elseif(isset($data->full_name)){!!$data->full_name!!}@endif"  id="validate-text"placeholder="Enter Full Name" >
        @if(!empty($errors->first('full_name')))<div class="btn btn-sm btn-danger">{{ $errors->first('full_name') }}</div>@endif
    </div>
<div class="form-group col-sm-4">
    <label for="validate-text"> Email *</label>
    <input type="text"  class="form-control input-sm" name="email" @if(isset($data))disabled @endif
    value="@if(!empty(old('email')!='')){{old('email')}}@elseif(isset($data->email)){!!$data->email!!}@endif"  id="validate-text"placeholder="Enter Email" >
    @if(!empty($errors->first('email')))<div class="btn btn-sm btn-danger">{{ $errors->first('email') }}</div>@endif
</div>

<div class="form-group col-sm-4">
    <label for="validate-text"> Mobile No *</label>
    <input type="text"  class="form-control input-sm" name="mobileno" @if(isset($data))disabled @endif
    value="@if(!empty(old('mobileno')!='')){{old('mobileno')}}@elseif(isset($data->mobileno)){!!$data->mobileno!!}@endif"  id="validate-text"placeholder="Enter Mobile Number" >
</div>
@if(!isset($data))
<div class="form-group col-sm-4">
    <label for="validate-text"> Password *</label>
    <input type="text"  class="form-control input-sm" name="password"
    value=""  id="validate-text"placeholder="Enter Password" >
    @if(!empty($errors->first('password')))<div class="btn btn-sm btn-danger">{{ $errors->first('password') }}</div>@endif
</div>
@endif
<div class="form-group col-sm-4">
    <label for="validate-text"> Date of Birth  </label>
    <input type="text" id="datepicker" class="form-control input-sm" name="dob"
    value="@if(!empty(old('dob')!='')){{old('dob')}}@elseif(isset($data->dob)){!!$data->dob !!}@endif"  id="validate-text"placeholder="Enter DOB" >
    @if(!empty($errors->first('dob')))<div class="btn btn-sm btn-danger">{{ $errors->first('dob') }}</div>@endif
</div>
<div class="form-group col-sm-4">
    <label for="validate-text"> Gender </label>
    <select name="gender" class="form-control input-sm">
        <option value="">---Select Gender---</option>
        <option value="Male" @if(isset($data->gender) && $data->gender == "Male") selected="" @endif> Male</option>
        <option value="Female" @if(isset($data->gender) && $data->gender == "Female") selected="" @endif>Female</option>
    </select>
</div>
<div class="form-group col-sm-4">
    <label for="validate-text">User Role </label>
    <select name="user_type" class="form-control input-sm">
        <option value="">---Select Role---</option>
        @if(!empty($all_roles))
        @foreach($all_roles as $key => $value)
        @if($value->name=='User')
        <option value="{{ $value->name }}" @if(isset($data->user_type) && $data->user_type == $value->name) selected="" @endif>{{ $value->name=='User'?'User':$value->name }}</option>
        @endif
        @endforeach
        @endif
    </select>
</div>
<!--
<div class="form-group col-sm-4 ">
    <label for="validate-text">Dealer </label>
    <select name="dealer_id" class="form-control input-sm dealer_id">
        <option value="">---Select Dealer---</option>
        @if(!empty($dealers))
        @foreach($dealers as $key => $value)
        <option value="{{ $value->id }}" @if(isset($data->dealer_id) && $data->dealer_id == $value->id) selected="" @endif>{{ $value->full_name }} ({{ $value->mobileno }})</option>
        @endforeach
        @endif
    </select>
</div>
-->
<div class="form-group col-sm-12">
    <label for="address">Address *</label>
    <textarea rows="3" class="form-control" placeholder="Enter Full Address" id="address" name="address">@if(!empty(old('address')!='')){{old('address')}}@elseif(isset($data->address)){!!$data->address!!}@endif</textarea>
    @if(!empty($errors->first('address')))<div class="btn btn-sm btn-danger">{{ $errors->first('address') }}</div>@endif
    <span id="hinttext"> </span>
</div>							

<div class="form-group col-sm-4">
    <label for="validate-text"> State *</label>
    <input type="text"  class="form-control input-sm" name="state"  value="@if(!empty(old('state')!='')){{old('state')}}@elseif(isset($data->state)){!!$data->state!!}@endif"  id="validate-text"placeholder="Enter State" >
    @if(!empty($errors->first('city')))<div class="btn btn-sm btn-danger">{{ $errors->first('city') }}</div>@endif
</div>
<div class="form-group col-sm-4">
    <label for="validate-text"> City *</label>
    <input type="text"  class="form-control input-sm" name="city"  value="@if(!empty(old('city')!='')){{old('city')}}@elseif(isset($data->city)){!!$data->city!!}@endif"  id="validate-text"placeholder="Enter City" >
    @if(!empty($errors->first('city')))<div class="btn btn-sm btn-danger">{{ $errors->first('city') }}</div>@endif
</div>

<div class="form-group col-sm-4">
    <label for="validate-text"> Pincode *</label>
    <input type="text"  class="form-control input-sm" name="pincode"  value="@if(!empty(old('pincode')!='')){{old('pincode')}}@elseif(isset($data->pincode)){!!$data->pincode!!}@endif"  id="validate-text"placeholder="Enter pincode" >
    @if(!empty($errors->first('pincode')))<div class="btn btn-sm btn-danger">{{ $errors->first('pincode') }}</div>@endif
</div>


<div class="form-group col-sm-4">
    <label for="validate-text"> Customer Note * (For your own notes)</label>
    <input type="text"  class="form-control input-sm" name="referral_code"  value="@if(!empty(old('referral_code')!='')){{old('referral_code')}}@elseif(isset($data->referral_code)){!!$data->referral_code!!}@endif"  id="validate-text"placeholder="Enter Custome Notes" >
    @if(!empty($errors->first('referral_code')))<div class="btn btn-sm btn-danger">{{ $errors->first('referral_code') }}</div>@endif
</div>

<div class="form-group col-sm-12 fileinput fileinput-new" data-provides="fileinput">
    
    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">

        @if(isset($data->profile_photo))
        <img src="{{ URL::to(App\Helpers\Thumbnail::image("user/$data->profile_photo","200","150","ff=ffffff"))}}" />
        @endif
        <br>
    </div>
    <div>
        <span class="btn btn-default btn-file">
            <span class="fileinput-new">Select Profile Photo</span>
            <span class="fileinput-exists">Change</span>
            <input accept="image/*"  type="file" name="profile_photo">
        </span>
    </div>
</div>

<hr>
<div class="clr"></div>
<p class="text-right">
    <button type="submit" class="submit btn btn-space btn-primary">Submit</button>
    <a href="{{ $navi['back_url'] }}"class="btn btn-default">Cancel</a>
</p>
</form>