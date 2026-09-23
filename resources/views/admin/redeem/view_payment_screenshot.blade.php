<div class="container">
    @foreach ($data as $item)    
      
        <img src="{{ url('uploads/800/800/fff/PaymentScreenshot/' . $item->payment_screenshot) }}">
    
    @endforeach
</div>
