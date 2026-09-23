<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title" id="user_delete_confirm_title">Sales Officer Data</h4>
  </div>

                    <div class="modal-body">
                        <form method="post" class="ajaxformclass"  @if(isset($data)) action="{{route('store.sales_officer',['id' => $data->id])}}" @else action="{{route('store.sales_officer')}}" @endif enctype="multipart/form-data">
                            <div class="col-sm-12">
                                <div class="alert" style="margin-top:10px;display:none;">
                                    <a href="javascript:void()" class="close" data-dismiss=""
                                        aria-label="close">&times;</a><strong class="ajax_message"></strong>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group col-sm-12">
                                <label for="validate-text">Name *</label>
                                <input type="text" class="form-control input-sm" name="full_name"
                                    value="@if (!empty(old('full_name') != '')) {{ old('full_name') }}@elseif(isset($data->full_name)){!! $data->full_name !!} @endif"
                                    id="validate-text" placeholder="Enter Dealer Name">
                                @if (!empty($errors->first('full_name')))
                                    <div class="btn btn-sm btn-danger">{{ $errors->first('full_name') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-sm-12 me-1">
                                <label for="validate-text">Employee ID *</label>
                                <input type="text" class="form-control input-sm" name="employee_id"
                                    value="@if (!empty(old('employee_id') != '')) {{ old('employee_id') }}@elseif(isset($data->employee_id)){!! $data->employee_id !!} @endif"
                                    id="validate-text"placeholder="Enter Employee ID">
                                @if (!empty($errors->first('employee_id')))
                                    <div class="btn btn-sm btn-danger">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-sm-12 me-1">
                                <label for="validate-text">Register Email Address *</label>
                                <input type="email" class="form-control input-sm" name="email"
                                    value="@if (!empty(old('email') != '')) {{ old('email') }}@elseif(isset($data->email)){!! $data->email !!} @endif"
                                    id="validate-text"placeholder="Enter Email Address">
                                @if (!empty($errors->first('email')))
                                    <div class="btn btn-sm btn-danger">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-sm-12 me-1">
                                <label for="validate-text">Mobile Number *</label>
                                <input type="text" class="form-control input-sm" name="mobileno" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" maxlength="10"
                                    value="@if (!empty(old('mobileno') != '')) {{ old('mobileno') }}@elseif(isset($data->mobileno)){!! $data->mobileno !!} @endif"
                                    id="validate-text"placeholder="Enter Mobile Number">
                                @if (!empty($errors->first('mobileno')))
                                    <div class="btn btn-sm btn-danger">{{ $errors->first('mobileno') }}</div>
                                @endif
                            </div>
                            
 							<div class="form-group col-sm-4">
							    <label for="validate-text"> State *</label>
							    <input type="text"  class="form-control input-sm" name="state"  value="@if(!empty(old('state')!='')){{old('state')}}@elseif(isset($data->state)){!!$data->state!!}@endif"  id="validate-text"placeholder="Enter State" >
							    @if(!empty($errors->first('state')))<div class="btn btn-sm btn-danger">{{ $errors->first('state') }}</div>@endif
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
                           
                            
                            @if(!isset($data))

                            <div class="form-group col-sm-12 me-1">
                                <label for="validate-text">Password *</label>
                                <input type="text" class="form-control input-sm" name="password"
                                    value="@if (!empty(old('password') != '')) {{ old('password') }}@elseif(isset($data->password)){!! $data->password !!} @endif"
                                    id="validate-text"placeholder="Enter Password">
                                @if (!empty($errors->first('password')))
                                    <div class="btn btn-sm btn-danger">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            @endif

                            <p class="text-right">
                                <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                <a data-dismiss="modal" aria-hidden="true" class="btn btn-default">Cancel</a>
                            </p>
                        </form>
                    </div>
<script>
        $(document).on("change", "#state_id", function() {
            var state_id = $(this).val();
            $('select[name="district_id"]').empty();
            $.ajax({
                type: "POST",
                url: "{{ route('dealer.get_district') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    state_id: state_id,
                },
                dataType: "json",
                success: function(response) {
                    $('select[name="district_id"]').append(
                        '<option value=""> Select District </option>');
                    $.each(response, function(key, value) {
                        $('select[name="district_id"]').append('<option value="' + value.id +
                            '">' + value.name + "</option>");
                    });
                },
            });
        });
    </script>
