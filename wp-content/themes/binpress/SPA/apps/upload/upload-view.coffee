define ['app'
        'plupload'], (App, plupload)->
    App.module 'UploadAppView', (View, App)->
        class View.UploadView extends Marionette.ItemView

            template : '<div class="col-md-3 text-center">
                            <div class="profile-wrapper pull-right">
                                <img class="m-b-10" width="90" height="90"
                                    data-src-retina="assets/img/profiles/avatar2x.jpg"
                                    data-src="assets/img/profiles/avatar.jpg" alt=""
                                    src="assets/img/profiles/avatar.jpg">

                                <div class="clearfix"></div>
                                <a id="add-photo" class="m-t-10" href="#" data-color-format="hex">Click
                                    to
                                    add/edit Profile Photo</a>
                            </div>
                        </div>
                       <div id="progress" style="width: 30%; margin: 0px auto; display: none;" class="progress progress-striped active">
                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="progress-bar"></div>
                            <span class="sr-only">0% Complete </span>
                        </div>
                       <div><input type="text" style="display: none" id="image_id" name="image_id"/></div>'

            # setup plupload on show
            # the url for plupload will be async-upload.php(wordpress default)
            # this plupload configuration is copied over from wordpress core
            # Note: do not change these settings
            onShow : ->
                #bind plupload script
                @uploader = new plupload.Uploader
                    runtimes : "gears,html5,flash,silverlight,browserplus"
                    file_data_name : "async-upload" # key passed to $_FILE.
                    multiple_queues : true
                    browse_button : "add-photo"
                    multipart : true
                    urlstream_upload : true
                    max_file_size : "10mb"
                    url : UPLOADURL
                    flash_swf_url : "../../bower_components/plupload/wp-includes/js/Moxie.swf"
                    silverlight_xap_url : "../../bower_components/plupload/wp-includes/js/Moxie.xap"
                    filters : [
                        title : "Image files"
                        extensions : "jpg,gif,png"
                    ]
                    multipart_params :
                        action : "upload-attachment"
                        _wpnonce : _WPNONCE


                @uploader.init()

                @uploader.bind "FilesAdded", (up, files)=>
                    @uploader.start()
                    @$el.find("#progress").show()

                @uploader.bind "UploadProgress", (up, file)=>
                    @$el.find(".progress-bar").css "width", file.percent + "%"

                @uploader.bind "Error", (up, err)->
                    up.refresh() # Reposition Flash/Silverlight

                @uploader.bind "FileUploaded", (up, file, response)=>
                    @$el.find(".progress-bar").css "width", "0%"
                    @$el.find("#progress").hide()
                    response = JSON.parse(response.response)
                    console.log response
                    if response.success
                        @$el.find('#image_id').val(response.data.id)
            #console.log response.data.id


            # destroyt the plupload instance on close to release memory
            onClose : ->
                @uploader.destroy()

