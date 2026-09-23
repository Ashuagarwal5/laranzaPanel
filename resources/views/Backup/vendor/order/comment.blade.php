<link href="{{ asset('assets/css/pages/timeline.css') }}" rel="stylesheet" />
                                    <ul class="timeline">
	                                    @if($comments!="")
                                        @foreach($comments as $key=> $item)
                                         <li class="{{$key%2==0 ?'timeline-inverted':''}}">
                                            <div class="timeline-badge">
                                                <i class="livicon" data-name="users" data-c="#fff" data-hc="#fff" data-size="18" data-loop="true"></i>
                                            </div>
                                            <div class="timeline-panel" style="display:inline-block;">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">{{ucfirst($item->state)}}-{{config('constants.Orderstate.'.$item->status)}}</h4>
                                                    <p>
                                                        <small class="text-muted">
                                                            <i class="livicon" data-name="bell" data-c="#F89A14" data-hc="#F89A14" data-size="18" data-loop="true"></i>
                        {{date("d M Y h :i:s a",strtotime($item->created_at))}}
                                                        </small>
                                                    </p>
                                                </div>
                                                <div class="timeline-body">
                                                    <p>
                                                       {{$item->comment}}
                                                       
                                                    </p>
                                                </div>
                                            </div>
                                        </li>

                                        
                                         @endforeach
                                         @endif
                                                                            </ul>
                               
