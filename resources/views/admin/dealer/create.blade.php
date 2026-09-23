<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
  <h4 class="modal-title" id="user_delete_confirm_title">Dealer Data</h4>
</div>

                <div class="modal-body clearfix">
                    <form method="post" class="ajaxformclass" @if (isset($data)) action="{{route('store.dealer',['id'=>$data->id])}}"@else action="{{route('store.dealer')}}" @endif  enctype="multipart/form-data">
                        <div class="col-sm-12">
                            <div class="alert" style="margin-top:10px;display:none;">
                                <a href="javascript:void()" class="close" data-dismiss=""
                                    aria-label="close">&times;</a><strong class="ajax_message"></strong>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <h2 class="text-center mb-5">Basic Information</h2>

                        <div class="form-group col-sm-12">
                            <label for="validate-text">Dealer Name (Firm Name)*</label>
                            <input type="text" class="form-control input-sm" name="dealer_name"
                                value="@if (!empty(old('dealer_name') != '')) {{ old('dealer_name') }}@elseif(isset($data->dealer_name)){!! $data->dealer_name !!} @endif"
                                id="validate-text" placeholder="Enter Dealer Name">
                            @if (!empty($errors->first('dealer_name')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('dealer_name') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="validate-text">Assigned Sales Officer *</label>
                            <select name="sales_officer_id" id="sales_officer_id" class="form-control">
                                <option value="">Select Sales Officer</option>
                                @foreach ($salesofficer as $item)
                                <option value="{{$item->id}}" @if (isset($data))
                                    {{ $data->sales_officer_id == $item->id ? 'selected' : '' }} @endif>
                                    {{$item->full_name}} | {{$item->employee_id}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 me-1">
                            <label for="validate-text">Registered Mobile Number *</label>
                            <input type="text" class="form-control input-sm" name="mobile_no"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                maxlength="10"
                                value="@if (!empty(old('mobile_no') != '')) {{ old('mobile_no') }}@elseif(isset($data->mobile_no)){!! $data->mobile_no !!} @endif"
                                id="validate-text" placeholder="Enter Mobile Number">
                            @if (!empty($errors->first('mobile_no')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('mobile_no') }}</div>
                            @endif
                        </div>
                        @if (!isset($data))
                        <div class="form-group col-sm-6 me-1">
                            <label for="validate-text">Register Email Address *</label>
                            <input type="email" class="form-control input-sm" name="email"
                                value="@if (!empty(old('email') != '')) {{ old('email') }}@elseif(isset($data->email)){!! $data->email !!} @endif"
                                id="validate-text" placeholder="Enter Email Address">
                            @if (!empty($errors->first('email')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        @endif

                        <div class="form-group col-sm-12 me-1">
                            <label for="validate-text">Dealership Location *</label>
                            <input type="text" class="form-control input-sm" name="dealership_location"
                                value="@if (!empty(old('dealership_location') != '')) {{ old('dealership_location') }}@elseif(isset($data->dealership_location)){!! $data->dealership_location !!} @endif"
                                id="validate-text" placeholder="Enter Dealership Location">
                            @if (!empty($errors->first('dealership_location')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('dealership_location') }}</div>
                            @endif
                        </div>
                        <div class="form-group col-sm-12 me-1">
                            <label for="validate-text">Address</label>
                            <textarea name="address" id="address" cols="30" rows="5" class="form-control">
@if (!empty(old('address') != ''))
{{ old('address') }}
@elseif(isset($data->address))
{!! $data->address !!}
@endif
</textarea>
                            @if (!empty($errors->first('address')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('address') }}</div>
                            @endif
                            </textarea>
                        </div>

                        <div class="form-group col-sm-6 me-1">
                            <label for="validate-text">State</label>
                            <select name="state_id" id="state_id" class="form-control">
                                <option value="">Select State</option>
                                @foreach ($states as $item)
                                <option value="{{ $item->id }}" @if (isset($data))
                                    {{ $data->state_id == $item->id ? 'selected' : '' }} @endif>
                                    {{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="form-group col-sm-6 me-1">
                            <label for="validate-text">District</label>
                            <select name="district_id" id="district_id" class="form-control">
                                <option value="">Select District</option>
                                @if (!empty($data))
                                @if (!empty($district_data))
                                @foreach ($district_data as $cityKey => $cityVal)
                                <option value="{{ $cityVal->id }}" @if (isset($data))
                                    {{ $data->district_id == $cityVal->id ? 'selected' : '' }} @endif>
                                    {{ $cityVal->name }}</option>
                                @endforeach
                                @endif
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-sm-6 me-1">
                            <label for="validate-text">City</label>
                            <input type="text" class="form-control input-sm" name="city"
                                value="@if (!empty(old('city') != '')) {{ old('city') }}@elseif(isset($data->city)){!! $data->city !!} @endif"
                                id="validate-text" placeholder="Enter City">
                            @if (!empty($errors->first('city')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('city') }}</div>
                            @endif
                        </div>

                        <div class="form-group col-sm-6 me-1">
                            <label for="validate-text">Pincode</label>
                            <input type="text" class="form-control input-sm" name="pincode"
                                value="@if (!empty(old('pincode') != '')) {{ old('pincode') }}@elseif(isset($data->pincode)){!! $data->pincode !!} @endif"
                                id="validate-text" placeholder="Pincode">
                            @if (!empty($errors->first('pincode')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('pincode') }}</div>
                            @endif
                        </div>

                        <h2 class="text-center col-sm-12">Contact Person Information</h2>

                        <div class="form-group col-sm-12 me-1">
                            <label for="validate-text">Contact Person Name</label>
                            <input type="text" class="form-control input-sm" name="contact_person_name"
                                value="@if (!empty(old('contact_person_name') != '')) {{ old('contact_person_name') }}@elseif(isset($data->contact_person_name)){!! $data->contact_person_name !!} @endif"
                                id="validate-text" placeholder="Contact Person Name">
                            @if (!empty($errors->first('contact_person_name')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('contact_person_name') }}</div>
                            @endif
                        </div>

                        <div class="form-group col-sm-6 me-1">
                            <label for="validate-text">Contact Person Designation</label>
                            <input type="text" class="form-control input-sm" name="contact_person_designation"
                                value="@if (!empty(old('contact_person_designation') != '')) {{ old('contact_person_designation') }}@elseif(isset($data->contact_person_designation)){!! $data->contact_person_designation !!} @endif"
                                id="validate-text" placeholder="Contact Person Designation">
                            @if (!empty($errors->first('contact_person_designation')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('contact_person_designation') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group col-sm-6 me-1">
                            <label for="validate-text">Contact Person Mobile Number *</label>
                            <input type="text" class="form-control input-sm" name="contact_person_mobile_no"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                maxlength="10"
                                value="@if (!empty(old('contact_person_mobile_no') != '')) {{ old('contact_person_mobile_no') }}@elseif(isset($data->contact_person_mobile_no)){!! $data->contact_person_mobile_no !!} @endif"
                                id="validate-text" placeholder="Contact Person Mobile Number">
                            @if (!empty($errors->first('contact_person_mobile_no')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('contact_person_mobile_no') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group col-sm-12 me-1">
                            <label for="validate-text">Other Details *</label>
                            <textarea name="other_details" id="other_details" cols="30" rows="5" class="form-control">
@if (!empty(old('other_details') != ''))
{{ old('other_details') }}
@elseif(isset($data->other_details))
{!! $data->other_details !!}
@endif
</textarea>
                            @if (!empty($errors->first('other_details')))
                            <div class="btn btn-sm btn-danger">{{ $errors->first('other_details') }}</div>
                            @endif
                            </textarea>
                        </div>

                        <p class="text-right col-sm-12">
                            <button type="submit" class="btn btn-space btn-primary">Submit</button>
                            <button  class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
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
