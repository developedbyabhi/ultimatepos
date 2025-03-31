<div class="modal-dialog" role="document">
    {!! Form::open(['url' => action([\App\Http\Controllers\SalesOrderController::class, 'postEditSalesOrderStatus'], ['id' => $id]), 'method' => 'put', 'id' => 'update_so_status_form', 'enctype' => 'multipart/form-data']) !!}
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">@lang('lang_v1.edit_status')</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('so_status', __('sale.status') . ':') !!}
                        <select name="status" id="so_status" class="form-control" style="width: 100%;">
                            @foreach($statuses as $key => $so_status)
                                <option value="{{$key}}" @if($key == $status) selected @endif>
                                    {{$so_status['label']}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('workflow_status', __('lang_v1.workflow_status') . ':') !!}
                        <select name="workflow_status" id="workflow_status" class="form-control" style="width: 100%;">
                            @foreach($workflow_statuses as $key => $workflow_status)
                                <option value="{{$key}}" @if($key == $workflow_status) selected @endif>
                                    {{$workflow_status['label']}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('shipping_image', __('lang_v1.shipping_image') . ':') !!}
                        <input type="file" name="shipping_image" id="shipping_image" class="form-control">
                        <p class="help-block">@lang('lang_v1.upload_shipping_image_help')</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">
                @lang('messages.close')
            </button>
            <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white ladda-button">
                @lang('messages.update')
            </button>
        </div>
    </div><!-- /.modal-content -->
    {!! Form::close() !!}
</div><!-- /.modal-dialog -->

<script>
$(document).ready(function() {
    $('#shipping_image').change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Handle image preview if needed
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>
