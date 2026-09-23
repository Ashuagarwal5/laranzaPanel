<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title" id="user_delete_confirm_title">All Dealers</h4>
  </div>
  <div class="modal-body">
    <div class="card-body p-5">
        @if (!$data->isEmpty())
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 70px;">Id</th>
                            <th scope="col" style="width: 30%;">Dealer Name</th>
                            <th scope="col">Location</th>
                            <th scope="col"> Email </th>
                            <th scope="col">Mobile Number </th>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)                          
                            <tr>
                                <th scope="row">{{ $item->user_id }}</th>
                                <td>
                                    <div> <strong>{{ $item->dealer_name }}</strong></div>
                                </td>
                                <td>{{ $item->dealership_location }} </td>
                                <td>{{ $item->email }} </td>
                                <td><strong>{{ $item->mobile_no }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <h4>You don’t have any dealer</h4>
        @endif
    </div>
  </div>

