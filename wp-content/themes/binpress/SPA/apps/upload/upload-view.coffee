#FIXME: check the progress bar
#include the files for the app
define [  'plupload', 'marionette' ], ( plupload, Marionette )->

    # view class to show the photo upload
    class UploadView extends Marionette.ItemView

        template : '<div class="col-md-3 text-center">
                    <div class="profile-wrapper pull-right alert alert-info">
                        <img class="m-b-10" width="90" height="90"
                         alt="" src="{{user_photo}}"id="user-photo">

                        <div class="clearfix"></div>
                        <a id="add-photo" class="btn btn-primary btn-block" href="#" data-color-format="hex">Edit Profile Photo</a>
                    </div>
                    <div class="upload-progress">
                        <div id="progress" style="width: 30%; margin: 0px auto; display: none;"
                        class="progress progress-striped active">
                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" class="progress-bar"></div>
                            <span class="sr-only">0% Complete </span>
                        </div>
                    </div>
                    <input type="text" style="display: none" id="user-photo-id" name="user_photo_id"/>
                 </div>'


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
                flash_swf_url : "../../bower_components/plupload/js/Moxie.swf"
                silverlight_xap_url : "../../bower_components/plupload/js/Moxie.xap"
                filters : [
                    title : "Image files"
                    extensions : "jpg,gif,png"
                ]
                multipart_params :
                    action : "upload-attachment"
                    _wpnonce : _WPNONCE


            @uploader.init()

            @uploader.bind "FilesAdded", ( up, files )=>
                @uploader.start()
                @$el.find( ".upload-progress" ).css "display", "inline"

            @uploader.bind "UploadProgress", ( up, file )=>
                @$el.find( ".progress-bar" ).css "width", file.percent + "%"

            @uploader.bind "Error", ( up, err )->
                up.refresh() # Reposition Flash/Silverlight

            @uploader.bind "FileUploaded", ( up, file, response )=>
                @$el.find( ".progress-bar" ).css "width", "0%"
                @$el.find( ".upload-progress" ).css "display", "none"
                response = JSON.parse response.response
                if response.success
                    @$el.find( '#user-photo-id' ).val( response.data.id )
                    @$el.find( '#user-photo' ).attr "src", response.data.url

        # destroyt the plupload instance on close to release memory
        onClose : ->
            @uploader.destroy()

    #return an instance of the upload view class
    UploadView

