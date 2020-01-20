@extends('layouts.master')

@section('title', 'Newsletter Template Edit')
@section('style')
    <link rel="stylesheet" href="{{asset('vendor/mosaico/assets/mosaico-libs-and-tinymce.min.css')}}" />
    <link rel="stylesheet" href="{{asset('vendor/mosaico/assets/mosaico-material.min.css')}}" />
@endsection
@section('breadcrumb')
    <div class="col-md-6 text-left">
        <x-layout.breadcrumb :menus="['newsletter', 'template', 'Edit']"
                             :menuLinks="['', route('admin.newsletter.template.index'), '']" />
    </div>
@endsection

@section('content')
    <div id="mosaico" class="tw-relative tw-h-[calc(100vh-140px)] tw-w-full tw-border tw-bg-[#555]"></div>
    <div id="image-selector"></div>
@endsection
@section('script')
    <script>
      window.user = @json(auth()->user());
      window.user.roles = @json(auth()->user()->roles);
    </script>
    {{
        Vite::useBuildDirectory('assets/resources/vite')
    }}
    @vite(['resources/js/component/image-selector.js'])
    <script src="{{asset('vendor/mosaico/assets/mosaico-libs-and-tinymce.min.js')}}"></script>
    <script src="{{asset('vendor/mosaico/assets/mosaico.min.js')}}"></script>
    <script>
      window.disableSend = true
      $(function() {
        if (!Mosaico.isCompatible()) {
          alert('Update your browser!')
          return
        }
        // var basePath = window.location.href.substr(0, window.location.href.lastIndexOf('/')).substr(window.location.href.indexOf('/','https://'.length));
        var basePath = "{{route('admin.newsletter.template.create')}}"
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
          model: @json($template->modelData),
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
          url: "{{route('admin.newsletter.template.update', ['slug' => $template->slug])}}",
          type: 'post',
          data: { _token: $('meta[name="csrf-token"]').attr('content'), slug: "{{$template->slug}}", ...e.detail },
          success: function(result) {
            if (result.status === 1) {
              itoastr('success', 'Your template was saved successfully!')
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

      window.addEventListener('closeEvent', function(e) {
        $.ajax({
          url: "{{route('admin.newsletter.template.update', ['slug' => $template->slug])}}",
          type: 'post',
          data: { _token: $('meta[name="csrf-token"]').attr('content'), slug: "{{$template->slug}}", ...e.detail },
          success: function(result) {
            if (result.status === 1) {
              itoastr('success', 'Your template was saved successfully!')
              window.location.href = "{{route('admin.newsletter.template.index')}}"
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
        window.location.href = "{{route('admin.newsletter.template.index')}}"
      })
    </script>
@endsection
