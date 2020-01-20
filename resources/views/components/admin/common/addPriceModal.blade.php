
<div class="modal fade" id="priceModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <form id="addPriceModalForm" action="{{$action}}" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="edit_price" name="edit_price">
                    <div class="row" x-data="{recurrent:true}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_type" class="form-control-label">Payment Type:</label>
                                <select name="payment_type" id="payment_type" class="payment_type selectpicker disable_item" data-width="100%" x-on:change="recurrent=!recurrent">
                                    <option value="1" @if($item->recurrent===1) selected @endif>Recurrent</option>
                                    <option value="0" @if($item->recurrent===0) selected @endif>Onetime</option>
                                </select>
                                <div class="form-control-feedback error-payment_type"></div>
                            </div>
                        </div>
                        <div class="col-md-6" x-show="recurrent">
                            <label for="period" class="form-control-label">Recurring Billing Cycle:</label>
                            <div class="input-group">
                                <input type="number" class="form-control text-right m-input--square disable_item" value="1" name="period" id="period">
                                <div class="input-group-append" style="width:150px;">
                                    <select class="form-control m-bootstrap-select selectpicker disable_item" name="period_unit" id="period_unit">
                                        <option value="day">Day</option>
                                        <option value="week">Week</option>
                                        <option value="month" selected>Month</option>
                                        <option value="year">Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-control-feedback error-period"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-control-label">Price:</label>
                                <input type="text" class="form-control price disable_item" name="price" id="price">
                                <div class="form-control-feedback error-price"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slashed_price" class="form-control-label">Slashed Price: <i class="fa fa-info-circle tooltip_1" title="Slashed Price: Nullable"></i></label>
                                <input type="text" class="form-control price" name="slashed_price" id="slashed_price">
                                <div class="form-control-feedback error-slashed_price"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="standard" class="form-control-label">Set As Standard? <i class="fa fa-info-circle tooltip_1" title="Set as standard price"></i></label>
                            <div>
                                <span class="m-switch m-switch--icon m-switch--info">
                                    <label>
                                        <input type="checkbox" name="standard" id="price_standard">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-control-label">Status <i class="fa fa-info-circle tooltip_1" title="Set as standard price"></i></label>
                            <div>
                                <span class="m-switch m-switch--icon m-switch--info">
                                    <label>
                                        <input type="checkbox" name="status" checked id="price_status">
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
