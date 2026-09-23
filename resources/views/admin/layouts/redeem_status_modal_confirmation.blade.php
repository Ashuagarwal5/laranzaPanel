<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="user_delete_confirm_title">{{ $model }}</h4>
</div>
<div class="modal-body">

  @if ($error)
      <div>{!! $error !!}</div>
  @else
      @if (isset($data->payment_type))
          <table class="table table-striped table-bordered table-hover" id="sample_1">
              <thead>
                  <tr>
                  <tr>
                      <th>Field Name</th>
                      <th>Data</th>
                  </tr>
                  </tr>
              </thead>
              <tbody>

                  @if ($data->payment_type == 'Bank Details')
                      <tr>
                          <td>Bank Name</td>
                          <td>
                              @if (isset($data->bank_name))
                                  {{ $data->bank_name }}
                              @else
                                  Not Available
                              @endif
                          </td>
                      </tr>
                      <tr>
                          <td>Branch Name</td>
                          <td>
                              @if (isset($data->branch_name))
                                  {{ $data->branch_name }}
                              @else
                                  Not Available
                              @endif
                          </td>
                      </tr>
                      <tr>
                          <td>Account Number</td>
                          <td>
                              @if (isset($data->account_number))
                                  {{ $data->account_number }}
                              @else
                                  Not Available
                              @endif
                          </td>
                      </tr>
                      <tr>
                          <td>IFSC Code</td>
                          <td>
                              @if (isset($data->ifsc_code))
                                  {{ $data->ifsc_code }}
                              @else
                                  Not Available
                              @endif
                          </td>
                      </tr>
                  @elseif($data->payment_type == 'GPAY')
                      <tr>
                          <td>Gpay Number</td>
                          <td>
                              @if (isset($data->gpay_number))
                                  {{ $data->gpay_number }}
                              @else
                                  Not Available
                              @endif
                          </td>
                      </tr>
                  @else
                      <tr>
                          <td>UPI ID</td>
                          <td>
                              @if (isset($data->upi_id))
                                  {{ $data->upi_id }}
                              @else
                                  Not Available
                              @endif
                          </td>
                      </tr>
                  @endif

              </tbody>
          </table>
      @else
          Bank Detail, UPI Detail and Gpay Number Not Found
      @endif
      <br>
      <br>
      Are you sure want to {{ $type }} this Record?
  @endif
  <br>
  <br>
  <form id="form-id" action="{{ $confirm_route }}" method="post" enctype="multipart/form-data"
      class="ajaxformclass">
      <input type="hidden" name="_token" value="{{ csrf_token() }}" />
      <div class="alert p-1" style="margin-top:10px;display:none;">
          <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a>&nbsp;<strong
              class="ajax_message"></strong>
      </div>
      <label for="label">Enter Payment Details</label>
      <textarea name="given_data" id="given_data" class="form-control" cols="30" rows="5"></textarea>
      <br>
      <label for="label">Upload Payment Screenshot</label>
      <input type="file" name="payment_screenshot" id="payment_screenshot" class="form-control">
</div>
</form>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  <button type="submit" id="conform_submit" class="btn btn-danger">Confirm</button>
</div>

<script>
  $(document).on('click', '#conform_submit', function(e) {
      $("#form-id").submit()
  })
</script>
