<!DOCTYPE html>
<html>

    @include('admin.partials.header')

    <body class="media-upload">

        <form id="my-awesome-dropzone" class="dropzone" action="#">
            <div class="dropzone-previews"></div>
            <button type="submit" class="btn btn-primary pull-right">Submit this form!</button>
        </form>

        @include('admin.partials.scripts')

        <!-- DROPZONE -->
        <script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>

        <script>
            $(document).ready(function(){

                Dropzone.options.myAwesomeDropzone = {

                    autoProcessQueue: false,
                    uploadMultiple: false,
                    parallelUploads: 100,
                    maxFiles: 100,

                    // Dropzone settings
                    init: function() {
                        var myDropzone = this;

                        this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            myDropzone.processQueue();
                        });
                        this.on("sendingmultiple", function() {
                        });
                        this.on("successmultiple", function(files, response) {
                        });
                        this.on("errormultiple", function(files, response) {
                        });
                    }

                }

           });
        </script>

    </body>

</html>
