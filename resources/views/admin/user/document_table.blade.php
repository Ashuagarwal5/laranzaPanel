<div class="row">
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" id="sample_1">
            <thead>
                <tr>
                    <th>Sr no.</th>
                    <th>Document Type</th>
                    <th>Image 1</th>
                    <th>Image 2</th>
                    <th>Status</th>
                    @if (isset($show_action))
                        @if ($show_action == 0)
                            <th>Action</th>
                        @endif
                    @endif
                </tr>
            </thead>
            <tbody>
                @if (count($document_details) > 0)
                    @if (isset($document_details))
                        @foreach ($document_details as $key => $item)
                            @php
                                $value = $key + 1;
                            @endphp
                            <tr>
                                <td>{{ $value }}</td>
                                <td>
                                    @if (isset($item->document_type))
                                        {{ $item->document_type }}
                                    @else
                                        -NA-
                                    @endif
                                </td>
                                <td>
                                    @if (isset($item->document_image_1))
                                        <a target="_blank"
                                            href="{{ URL::to(App\Helpers\Thumbnail::image('documents/' . $item->document_image_1, '500', '500', 'ff=ffffff')) }}"><img
                                                src="{{ URL::to(App\Helpers\Thumbnail::image('documents/' . $item->document_image_1, '200', '150', 'ff=ffffff')) }}" /></a>
                                    @else
                                        -NA-
                                    @endif
                                </td>
                                <td>
                                    @if (isset($item->document_image_2))
                                        <a target="_blank"
                                            href="{{ URL::to(App\Helpers\Thumbnail::image('documents/' . $item->document_image_2, '500', '500', 'ff=ffffff')) }}"><img
                                                src="{{ URL::to(App\Helpers\Thumbnail::image('documents/' . $item->document_image_2, '200', '150', 'ff=ffffff')) }}" /></a>
                                    @else
                                        -NA-
                                    @endif
                                </td>
                                <td>
                                    @if (isset($item->status))
                                        {{ $item->status }}
                                    @else
                                        -NA-
                                    @endif
                                </td>
                                @if (isset($show_action))
                                    @if ($show_action == 0)
                                        <td><a data-original-title="Delete Record" class="btn btn-danger enable-tooltip"
                                                href="{{ route('confirm-delete-document/admin_user', $item->id) }}"
                                                data-toggle="modal" data-target="#modal-regular"><i
                                                    class="fa fa-trash"></i></a></td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    @endif
                @else
                <tr>
                    <td colspan="5" class="text-center">No Record Fornd</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
