#include the files for the app
define [ 'marionette' ], ( Marionette )->

    # Layout for add-edit user domains
    class AddGroupView extends Marionette.ItemView

        tagName : 'form'

        template : ' <div class="row">

                            <div class="col-md-3">
                                <img src="../wp-content/themes/binpress/images/default.png" class=" image-responsive-width"/>
                                <br><br>
                                <button type="submit" class="btn btn-primary btn-block"><i class="icon-ok"></i> Edit Group Image</button>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label class="form-label">Group Name</label>
                                    <span class="help">e.g. "xyz"</span>
                                    <div class="input-with-icon  right">
                                        <i class=""></i>
                                        <input type="text" name="group_name"
                                        id="group_name" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <div class="input-with-icon  right">
                                        <i class=""></i>
                                        <textarea type="text" name="group_description"
                                        id="group_description" class="form-control">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-success btn-cons"
                                    id="btn-save-domain-group">
                                    <i class="icon-ok"></i> Save</button>
                                    <input type="reset" style="display: none" id="btn-reset-group"/>
                                        </div>
                            </div>

                        </div>'

        events :
            'click #btn-save-domain-group' : ->
                #check if the form is valid
                if @$el.valid()
                    #get all serialized data from the form
                    groupdata = Backbone.Syphon.serialize @
                    groupdata.domain_id = Marionette.getOption @, 'domain_id'
                    @trigger "save:domain:group:clicked", groupdata

        onShow : ->
            #validate the user profile form with the validation rules
            @$el.validate @validationOptions()

        onDomainGroupAdded : ->
            @$el.find( '#btn-reset-group' ).click()


        validationOptions : ->
            rules :
                group_name :
                    required : true

                group_description :
                    required : true

            messages :
                group_name : 'Enter valid group name'


    #return the view instance
    AddGroupView















