<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=1024, initial-scale=1">

    <link rel="canonical" href="http://mosaico.io" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />

    <script src="{{asset('vendor/mosaico/assets/mosaico-libs-and-tinymce.min.js')}}"></script>
    <script src="{{asset('vendor/mosaico/assets/mosaico.min.js')}}"></script>
    <script>
      $(function() {
        if (!Mosaico.isCompatible()) {
          alert('Update your browser!')
          return
        }
        // var basePath = window.location.href.substr(0, window.location.href.lastIndexOf('/')).substr(window.location.href.indexOf('/','https://'.length));
        var basePath = window.location.href
        if (basePath.lastIndexOf('#') > 0) basePath = basePath.substr(0, basePath.lastIndexOf('#'))
        if (basePath.lastIndexOf('?') > 0) basePath = basePath.substr(0, basePath.lastIndexOf('?'))
        if (basePath.lastIndexOf('/') > 0) basePath = basePath.substr(0, basePath.lastIndexOf('/'))
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
          imgProcessorBackend: basePath + '/img/',
          emailProcessorBackend: basePath + '/dl/',
          titleToken: 'MOSAICO Responsive Email Designer',
          fileuploadConfig: {
            url: basePath + '/upload/'
            // messages??
          }
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
    </script>

    <link rel="stylesheet" href="{{asset('vendor/mosaico/assets/mosaico-libs-and-tinymce.min.css')}}" />
    <link rel="stylesheet" href="{{asset('vendor/mosaico/assets/mosaico-material.min.css')}}" />
</head>
<body class="mo-standalone">

</body>
</html>
