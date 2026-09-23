
$(function()
  {
      //on country change get all sttaes
      $('.select_country').change(function()
      {
        let country_id = $(this).val();
        //if selected india is country then make satet dropdown
        if(country_id == 88)
        {
          $(".state_input").hide();
          $(".state_select").show();
          var url = "{{route('get-country-state')}}";
          $.ajax(
          {
               type: "POST",
               url: url,
               data : {
                        '_token' : "{{ csrf_token() }}",
                    'country_id': country_id
                  },
                success: function(response)
                {
                  if(response != false)
                  {
                    let data = (jQuery.parseJSON(response));
                    $(".set_states").html('');
                    $(".set_states").append('<option value="">Select Province / State </option>');
                    for(let state of data)
                    {
                      $(".set_states").append('<option value="'+state.region_id+'">'+ state.region_name +' </option>');
                    }
                  }
                  else
                    $(".set_states").append('<option value="">State Not Found  </option>');
                }
          });
        }
        else
        {
          $(".state_select").hide();
          $(".state_input").show();
        }
        
      });
  });
