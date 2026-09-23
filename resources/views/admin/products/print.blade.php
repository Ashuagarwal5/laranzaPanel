@php
    $size = request()->segment(5);
    if ($size != null) {
        $values = (int)$size;
    }
    else{
        $values = 45;
    }
@endphp
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            @foreach ($codes as $item)
            <span style="padding:5px;float:left;">
                <img src="{{ route('products.view.images', ['name' => $item['qr_value']]) }}" height="{{$values}}" alt="" ><br/><span style="font-size:10px;">{{{ $item['reward_points'] }}} Points</span>
            </span>
            @endforeach
    </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        window.print();
    });
</script>
