<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="sample_1">
            <thead>
                <tr>
                    <th>Sr no.</th>
                    <th>Payment Type</th>
                    <th>Bank Name</th>
                    <th>Branch Name</th>
                    <th>Account Number</th>
                    <th>IFSC Code</th>
                    <th>UPI ID</th>
                    <th>Gpay Number</th>
                    <th>Status</th>
                    @if(isset($show_action))
                        @if($show_action == 0)
                            <th>Action</th>
                        @endif
                    @endif
                </tr>
            </thead>
            <tbody>
                @if (count($complete_bank_details) > 0)
                    
      
                @if(isset($complete_bank_details))
                    @foreach($complete_bank_details as $key => $item)
                        @php
                    $value = $key + 1;
                        @endphp
                        <tr>
                            <td> {{$value}}</td>
                        <td> @if(isset($item->payment_type)) {{$item->payment_type}} @else -NA- @endif </td>
                        <td> @if(isset($item->bank_name)) {{$item->bank_name}} @else -NA- @endif </td>
                        <td> @if(isset($item->branch_name)) {{$item->branch_name}} @else -NA- @endif </td>
                        <td> @if(isset($item->account_number)) {{$item->account_number}} @else -NA- @endif </td>
                        <td> @if(isset($item->ifsc_code)) {{$item->ifsc_code}} @else -NA- @endif </td>
                        <td> @if(isset($item->upi_id)) {{$item->upi_id}} @else -NA- @endif </td>
                        <td> @if(isset($item->gpay_number)) {{$item->gpay_number}} @else -NA- @endif </td>
                        <td> @if(isset($item->status)) {{$item->status}} @else -NA- @endif </td>
                            @if(isset($show_action))
                                @if($show_action == 0)
                                    <td><a data-original-title="Delete Record" class="btn btn-danger enable-tooltip" href="{{route("delete-bank_details/admin_user",$item->id)}}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-trash"></i></a></td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                @endif
                @else
                <tr>
                    <td colspan="10" class="text-center">No Record Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>