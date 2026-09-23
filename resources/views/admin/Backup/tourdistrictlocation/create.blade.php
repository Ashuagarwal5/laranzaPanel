@extends('admin/layouts/default')
@section('title')
{{ $manager_name }} Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop
@section('content')
<section class="content-header">
    <h1>
    {{ $manager_name }} Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>{{ $manager_name }} Manager</li>
        <li class="active">
          @if(isset($data))
          Edit 
          @else
          Create
          @endif
      </li>
  </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title">
                        <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff"
                        data-hc="white"></i>
                        @if(isset($data))
                        Edit 
                        @else
                        Create
                        @endif {{ $manager_name }}
                    </h3>

                </div>
                <div class="panel-body">
                   <form method="post" id="basic_info" class="ajaxformclass" action="{{ $route_url }}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                      @if(Session::has('message'))
                        <div class="alert {{Session::get('class')}}" style="margin-top:10px;display:none;">
                           {{Session::get('message')}}
                       </div>
                      @endif
                       <div class="alert" style="margin-top:10px;display:none;">
                           <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
                       </div>
                   </div>
                   <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                   <div class="form-group col-sm-12">
                     <label for="validate-text">State *</label>
                     <select name="state_id" class="form-control input-sm">
                      <option value="">Select State</option>
                       @if(!empty($states))
                         @foreach($states as $state)
                            @if(isset($data->state_id) && $data->state_id==$state->id)
                              <option value="{{$state->id}}" selected>{{$state->name}}</option>
                            @else
                              <option value="{{$state->id}}">{{$state->name}}</option>
                            @endif()
                            
                         @endforeach
                         @else
                         <option value="">No State Found</option>
                       @endif()
                     </select>
                     @if(!empty($errors->first('name')))
                       <div class="btn btn-sm btn-danger">{{ $errors->first('name') }}
                       </div>
                      @endif
                   </div>

                   <div class="form-group col-sm-12">
                     <label for="validate-text">District *</label>
                     <input type="text"  class="form-control input-sm" name="name"
                     value="@if(!empty(old('name')!='')){{old('name')}}@elseif(isset($data->name)){!!$data->name!!}@endif"  id="validate-text"placeholder="Enter name" >
                     @if(!empty($errors->first('name')))
                       <div class="btn btn-sm btn-danger">{{ $errors->first('name') }}
                       </div>
                      @endif
                   </div>
                 
                <p class="text-right">
                 <button type="submit" class="btn btn-space btn-primary">Submit</button>
                 <a href="{{route('store.tourlocationdistrict') }}"class="btn btn-default">Cancel</a>
             </p>
         </form>



         <div class="panel-body">

           @include('admin.notifications')

           <div class="table-responsive">
            <table class="table table-bordered " id="table1">
                <thead>
                    <tr class="filters">
                       <th >Id</th>
                        <th >District </th>
                       <th >State Name</th>
                       <th>Status</th>
                       <th>Create Date</th>
                       <th >Action</th>
                   </tr>
               </thead>
               <tbody>
               </tbody>
           </table>
       </div>
   </div>
</div>
</div>
</div>
</div>
<!-- row-->
</section>
@stop
@section('footer_scripts')

<script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

<script type="text/javascript">
    $(function () {
               // $('#datetimepicker4').datepicker();
           });
       </script>

       <script>

        var model_data="{{ route('admin.tourlocationdistrict.data') }}";
        var addmodel_route="{{ route('create.tourlocationdistrict') }}";
        // var delete_model="{{ URL::to('admin/brand/deletemodel')}}"
        
        $('#model_name').on('change',function(){
            var arr = [];
            $siblings = $(this).siblings();
            $.each($siblings, function (i, key) {
             arr.push($(key).val()); 
         });
            if ($.inArray($(this).val(), arr) !== -1)
            {
                alert("duplicate has been found");
            }
        });
        
        function deletemodelfromlist(id){
            $('.deletepage').click(function(){
                $.ajax({
                    url: '{{ URL::to('admin/model/deletedmodel') }}/' +id,
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: 'json',
                    data: {
                        '_token': $('input[name=_token]').val(),
                    },

                    success: function(response) {
                        toastr[response.status]("Sucessfully Deleted", "Notifications");

                        $('#delete_confirm').modal('hide');
                        var table = $('#table1').DataTable({
                            processing: true,
                            serverSide: true,
                            bDestroy: true,
                            aaSorting : [[0, 'desc']],
                            ajax: model_data,
                            columns: [
                            { data: 'id', name: 'id' },
                            { data: 'name', name: 'name' },
                            { data: 'state_id', name: 'state_id' },
                    { data: 'add_date', name: 'created_at' },
                    
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                    ],

                });
                        table.on( 'draw', function () {
                            $('.livicon').each(function(){
                                $(this).updateLivicon();
                            });
                        });

                    },
                });
            });
        }


        function displayimage(data,type,row,meta)
        {
            if(data){
                var imageurl='{{ URL::to(App\Helpers\Thumbnail::image("/tour_amenities/","200","65","ff=ffffff")) }}/'+data;
                var str='<img src="'+imageurl+'" />';
            }else{
                var str='No image found';
            }
            return str;
        }

        

        var table = $('#table1').DataTable({
            processing: true,
            serverSide: true,
            aaSorting : [[0, 'desc']],
            ajax: model_data,

            columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'disrtict' },
            { data: 'state_name', name: 'state_name' },
            { data: 'status', name: 'status' },
                // { data: 'image', name: 'image', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                { data: 'add_date', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ],

            });
        table.on( 'draw', function () {
            $('.livicon').each(function(){
                $(this).updateLivicon();
            });


        } );



       //  $('#basic_info').submit(function( event ) {
       //     var formid = '#' + $(this).attr('id');

       //     event.preventDefault();
       //     $.ajax({
       //      url: addmodel_route,
       //      type: "POST",
       //      data: $(this).serialize(),
       //      dataType: 'json',
       //      beforeSend:function(){

       //          $('.formmessage').remove();

       //      }
       //      ,   
       //      success: function(response) {
       //         if (response.status == "success") {

       //                  //$(formid).find('.alert').fadeIn();
       //                  $(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
       //                  $(formid).find('.alert').fadeOut();
       //                  $('#basic_info')[0].reset(); 

       //              } else { 
       //              // toastr[response.status]("Sucessfully Add", "Notifications");
       //              $(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);

       //              $.each(response.errorArray, function(key, value) {

       //                  if (!/\brequired\b/.test(value)){
       //                      $(formid).find('.ajax_message').append('<br>'+value);
       //                  } 
                        
       //                  console.log(key + " => " + value);

       //                  var placeH  =value;                     
       //                  $(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').attr("placeholder", placeH);
       //                  $(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').closest('.form-group').addClass('has-error');
       //                  if ( $(this).is('select') )
       //                      $(formid).find('.selectOption').addClass('state-error');

       //              });
       //          }
       //          var table = $('#table1').DataTable({
       //              processing: true,
       //              serverSide: true,
       //              bDestroy: true,
       //              aaSorting : [[0, 'desc']],
       //              ajax: model_data,
       //              columns: [
       //              { data: 'id', name: 'id' },
       //              { data: 'name', name: 'name' },
       //              { data: 'code', name: 'code' },
       //              { data: 'add_date', name: 'created_at' },
                    
       //              { data: 'actions', name: 'actions', orderable: true, searchable: true }
       //              ],

       //          });
       //          table.on( 'draw', function () {
       //              $('.livicon').each(function(){
       //                  $(this).updateLivicon();
       //              });
       //          } );


       //      },
       //  });
       // });

   </script>

   @stop
