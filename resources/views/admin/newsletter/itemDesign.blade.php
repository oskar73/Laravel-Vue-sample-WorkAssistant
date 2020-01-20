@extends('layouts.master')

@section('title', 'Newsletter Item Design')
@section('style')
    <link rel="stylesheet" href="{{asset('vendor/mosaico/assets/mosaico-libs-and-tinymce.min.css')}}" />
    <link rel="stylesheet" href="{{asset('vendor/mosaico/assets/mosaico-material.min.css')}}" />
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['newsletter', 'item', 'design']"
                             :menuLinks="['', route('admin.newsletter.item.index'), '']" />
    </div>
@endsection

@section('content')
    <div id="mosaico" class="tw-relative tw-h-[calc(100vh-140px)] tw-w-full tw-border tw-bg-[#555]"></div>
    <div id="image-selector"></div>

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
      window.user = @json(auth()->user());
      window.user.roles = @json(auth()->user()->roles);
      @if($item->has_active_ads && !str_contains($item->html, '-ad-'))
      $('#notification_modal').modal()
        @endif
    </script>
    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}
    @vite(['resources/js/component/image-selector.js'])
    <script src="{{asset('vendor/mosaico/assets/mosaico-libs-and-tinymce.min.js')}}"></script>
    <script src="{{asset('vendor/mosaico/assets/mosaico.min.js')}}"></script>
    <script>
      var isEmpty = {{ $item->html ? 'false' : 'true' }};
      $(function() {
        if (!Mosaico.isCompatible()) {
          alert('Update your browser!')
          return
        }
        // var basePath = window.location.href.substr(0, window.location.href.lastIndexOf('/')).substr(window.location.href.indexOf('/','https://'.length));
        var basePath = "{{route('admin.newsletter.item.index')}}"
        var plugins
        // A basic plugin that expose the "viewModel" object as a global variable.
        // plugins = [function(vm) {window.viewModel = vm;}];
        // An example of a retina plugin (2x images), using custom manipulation function (resizex and coverx instead of resize and cover)
        /*
        plugins = [function(vm) {
          // overrides placeholder text
          ko.bindingHandlers.wysiwygSrc.placeholderText = function(w, h) {
            return w && h ? w+'x'+h : w ? '<'+w+'>' : 'h'+h;
          };
          // double width and height for placeholder call and call method=placeholder2 so to have larger stripes
          var origPlaceholderUrl = ko.bindingHandlers.wysiwygSrc.placeholderUrl;
          ko.bindingHandlers.wysiwygSrc.placeholderUrl = function(w, h, text, method) {
            return origPlaceholderUrl(w*2, h*2, text, 'placeholder2');
          };
          // double width and height and call coverx/resizex instead of cover/resize so to avoid enlarging images
          var origConvertedUrl = ko.bindingHandlers.wysiwygSrc.convertedUrl;
          ko.bindingHandlers.wysiwygSrc.convertedUrl = function(src, method, width, height) {
            return origConvertedUrl(src, method+'x', width ? width*2 : width, height ? height*2 : height);
          };
        }];
        */
        var ok = Mosaico.init({
          imgProcessorBackend: "{{route('newsletter.image')}}",
          emailProcessorBackend: basePath + '/dl/',
          titleToken: 'MOSAICO Responsive Email Designer',
          fileuploadConfig: {
            url: "{{route('newsletter.uploadImage')}}"
            // messages??
          },
          template: "{{route('newsletter.template')}}",
          target: '#mosaico',
          model: @json($item->modelData),
          /* until mosaico 0.18.7 this was the default functionCaller logging loading times. Now you can pass this as an option if you need the old behaviour
          functionCaller: function(name, whatToCall) {
            var res;
            var start = new Date().getTime();
            if (typeof console == 'object' && console.time) console.time(name);
            res = whatToCall();
            if (typeof console == 'object' && console.time) console.timeEnd(name);
            var diff = new Date().getTime() - start;
            if (typeof console == 'object' && !console.time) if (typeof console.debug == 'function') console.debug(name, "took", diff, "ms");
            return res;
          }
          */
        }, plugins)
        if (!ok) {
          console.log('Missing initialization hash, redirecting to main entrypoint')
          // document.location = '.'
        }
      })

      window.addEventListener('saveEvent', function(e) {
        $.ajax({
          url: "{{route('admin.newsletter.item.update', ['slug' => $item->slug, 'type' => 'design'])}}",
          type: 'post',
          data: { _token: $('meta[name="csrf-token"]').attr('content'), ...e.detail },
          success: function(result) {
            if (result.status === 1) {
              itoastr('success', 'Your item was saved successfully!')
              window.location.reload()
            } else {
              dispErrors(result.data)
            }
          },
          error: function(e) {
            console.log(e)
          }
        })
      })

      window.addEventListener('sendEvent', function(e) {
        $.ajax({
          url: "{{route('admin.newsletter.item.update', ['slug' => $item->slug, 'type' => 'design'])}}",
          type: 'post',
          data: { _token: $('meta[name="csrf-token"]').attr('content'), ...e.detail },
          success: function(result) {
            if (result.status === 1) {
              itoastr('success', 'Your item was saved successfully!')
              window.location.href = "{{route('admin.newsletter.item.review', ['slug' => $item->slug])}}"
            } else {
              dispErrors(result.data)
            }
          },
          error: function(e) {
            console.log(e)
          }
        })
      })

      window.addEventListener('closeEvent', function(e) {
        $.ajax({
          url: "{{route('admin.newsletter.item.update', ['slug' => $item->slug, 'type' => 'design'])}}",
          type: 'post',
          data: { _token: $('meta[name="csrf-token"]').attr('content'), ...e.detail },
          success: function(result) {
            if (result.status === 1) {
              itoastr('success', 'Your item was saved successfully!')
              window.location.href = "{{route('admin.newsletter.item.index')}}"
            } else {
              dispErrors(result.data)
            }
          },
          error: function(e) {
            console.log(e)
          }
        })
      })

      window.addEventListener('leaveEvent', function() {
        if (isEmpty) {
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{route('admin.newsletter.item.delete', ['slug' => $item->slug])}}",
            method: 'delete',
            success: function(result) {
              if (result.status === 0) {
                dispValidErrors(result.data)
              } else {
                window.location.href = "{{route('admin.newsletter.item.index')}}"
              }
            },
            error: function(e) {
              console.log(e)
            }
          })
        } else {
          window.location.href = "{{route('admin.newsletter.item.index')}}"
        }
      })
    </script>
@endsection
