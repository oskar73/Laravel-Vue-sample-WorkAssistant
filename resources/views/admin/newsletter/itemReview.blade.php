@extends('layouts.master')

@section('title', 'Newsletter Item Design')
@section('style')
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['newsletter', 'item', 'review']"
                             :menuLinks="['', route('admin.newsletter.item.index'), '']" />
    </div>
@endsection

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__body tw-grid tw-grid-cols-[300px_1fr] tw-gap-4">
            <div class="tw-flex tw-flex-col tw-justify-between tw-gap-4">
                <div class="tw-flex tw-flex-col tw-gap-4">
                    <button class="btn btn-primary" id="send_btn">Send Newsletter</button>
                    <button class="btn btn-info" id="test_btn">Send Test Newsletter</button>
                </div>
                <a href="{{route('admin.newsletter.item.design', ['slug' => $item->slug])}}"
                   class="btn btn-outline-secondary">Back
                    to design</a>
            </div>
            <iframe src="{{route('admin.newsletter.item.preview', ['slug' => $item->slug])}}"
                    class="tw-w-full tw-h-[calc(100vh-240px)]"></iframe>
        </div>
    </div>

    <div class="modal fade" id="test_modal" tabindex="-1" role="dialog" data-backdrop="static"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Test Newsletter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <form id="test_modal_form" method="post" enctype="multipart/form-data"
                      action="{{route('admin.newsletter.item.test', ['slug' => $item->slug])}}">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="form-control-label">
                                Email:
                            </label>
                            <input type="email" class="form-control m-input--square" name="email" id="email"
                                   value="{{auth()->user()->email}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info m-btn m-btn--custom m-btn--square"
                                data-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn m-btn--square m-btn btn-outline-success smtBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($item->has_active_ads && !str_contains($item->html, '-ad-'))
        <div class="modal fade" id="notification_modal" tabindex="-1" role="dialog" data-backdrop="static"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">You have Ad listings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>Hello, please make sure to add these ad blocks this month. You have at least one advertiser
                            that purchased.</h4>
                        <div class="tw-mt-5 tw-flex tw-flex-col tw-gap-4">
                            @if(isset($item->has_active_ads[1]))
                                <div class="tw-flex tw-flex-col tw-gap-2">
                                    <span>Single Ad Block</span>
                                    <img class="tw-w-full tw-max-w-96"
                                         src="{{asset('vendor/mosaico/template/edres/singleAdBlock.png')}}"
                                         alt="">
                                </div>
                            @endif
                            @if(isset($item->has_active_ads[2]) || isset($item->has_active_ads[3]))
                                <div class="tw-flex tw-flex-col tw-gap-2">
                                    <span>Double Ad Block</span>
                                    <img class="tw-w-full tw-max-w-96"
                                         src="{{asset('vendor/mosaico/template/edres/doubleAdBlock.png')}}"
                                         alt="">
                                </div>
                            @endif
                            @if(isset($item->has_active_ads[4]) || isset($item->has_active_ads[5]) || isset($item->has_active_ads[6]))
                                <div class="tw-flex tw-flex-col tw-gap-2">
                                    <span>Triple Ad Block</span>
                                    <img class="tw-w-full tw-max-w-96"
                                         src="{{asset('vendor/mosaico/template/edres/tripleAdBlock.png')}}"
                                         alt="">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn m-btn--square btn-outline-primary" data-dismiss="modal">OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('script')
    <script>
      var sendURL = "{{route('admin.newsletter.item.send', ['slug' => $item->slug])}}"
      @if($item->has_active_ads && !str_contains($item->html, '-ad-'))
      $('#notification_modal').modal()
        @endif
    </script>
    <script src="{{asset('assets/js/admin/newsletter/itemReview.js')}}"></script>
@endsection
