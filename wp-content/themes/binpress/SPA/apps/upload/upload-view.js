(function() {
  var __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  define(['plupload', 'marionette'], function(plupload, Marionette) {
    var UploadView;
    UploadView = (function(_super) {
      __extends(UploadView, _super);

      function UploadView() {
        return UploadView.__super__.constructor.apply(this, arguments);
      }

      UploadView.prototype.template = '<div class="col-md-3 text-center"> <div class="profile-wrapper pull-right alert alert-info"> <img class="m-b-10" width="90" height="90" alt="" src="{{user_photo}}"id="user-photo"> <div class="clearfix"></div> <a id="add-photo" class="btn btn-primary btn-block" href="#" data-color-format="hex">Edit Profile Photo</a> </div> <div class="upload-progress"> <div id="progress" style="width: 30%; margin: 0px auto; display: none;" class="progress progress-striped active"> <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="progress-bar"></div> <span class="sr-only">0% Complete </span> </div> </div> <input type="text" style="display: none" id="user-photo-id" name="user_photo_id"/> </div>';

      UploadView.prototype.onShow = function() {
        this.uploader = new plupload.Uploader({
          runtimes: "gears,html5,flash,silverlight,browserplus",
          file_data_name: "async-upload",
          multiple_queues: true,
          browse_button: "add-photo",
          multipart: true,
          urlstream_upload: true,
          max_file_size: "10mb",
          url: UPLOADURL,
          flash_swf_url: "../../bower_components/plupload/js/Moxie.swf",
          silverlight_xap_url: "../../bower_components/plupload/js/Moxie.xap",
          filters: [
            {
              title: "Image files",
              extensions: "jpg,gif,png"
            }
          ],
          multipart_params: {
            action: "upload-attachment",
            _wpnonce: _WPNONCE
          }
        });
        this.uploader.init();
        this.uploader.bind("FilesAdded", (function(_this) {
          return function(up, files) {
            _this.uploader.start();
            return _this.$el.find(".upload-progress").css("display", "inline");
          };
        })(this));
        this.uploader.bind("UploadProgress", (function(_this) {
          return function(up, file) {
            return _this.$el.find(".progress-bar").css("width", file.percent + "%");
          };
        })(this));
        this.uploader.bind("Error", function(up, err) {
          return up.refresh();
        });
        return this.uploader.bind("FileUploaded", (function(_this) {
          return function(up, file, response) {
            _this.$el.find(".progress-bar").css("width", "0%");
            _this.$el.find(".upload-progress").css("display", "none");
            response = JSON.parse(response.response);
            if (response.success) {
              _this.$el.find('#user-photo-id').val(response.data.id);
              return _this.$el.find('#user-photo').attr("src", response.data.url);
            }
          };
        })(this));
      };

      UploadView.prototype.onClose = function() {
        return this.uploader.destroy();
      };

      return UploadView;

    })(Marionette.ItemView);
    return UploadView;
  });

}).call(this);
