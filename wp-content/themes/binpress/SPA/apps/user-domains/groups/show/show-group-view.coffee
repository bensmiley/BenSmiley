#include the files for the app
define [ 'marionette', 'text!apps/user-domains/templates/groupsTemplate.html' ], ( Marionette, groupsTpl )->

    #View for listing each group
    class SingleGroupView extends Marionette.ItemView

        template : '<td class="v-align-middle"><span class="muted">{{group_name}}</span></td>
                    <td><span class="muted">{{group_description}}</span></td>
                    <td class="v-align-middle">
                    <span class="glyphicon glyphicon-pencil edit-group"></span>  &nbsp;
                    <span class="glyphicon glyphicon-trash"></span>
                    </td>'

        tagName : 'tr'

        events :
            'click .edit-group' : ->
                @trigger "edit:group:clicked", @model

        modelEvents:
            'change': 'render'


    #View for empty groups
    class EmptyGroupView extends Marionette.ItemView

        template : '<td class="v-align-middle">
                        <span class="muted">No Groups added</span></td>
                    </td>'

        tagName : 'tr'


    # Main view  for listing the groups for domain
    class ShowGroupView extends Marionette.CompositeView

        template : groupsTpl

        itemViewContainer : 'tbody'

        itemView : SingleGroupView

        emptyView : EmptyGroupView

        events :
            'click #btn-save-domain-group' : ->
                #check if the form is valid
                if @$el.find( '#add-group-form' ).valid()
                    #get all serialized data from the form
                    groupdata = Backbone.Syphon.serialize @
                    groupdata.domain_id = Marionette.getOption @, 'domainId'

                    #check if update or save action
                    buttonText = @$el.find( '#btn-save-domain-group' ).text()
                    formAction = buttonText.trim()

                    if formAction == "Save"
                        @trigger "save:domain:group:clicked", groupdata
                    else
                        @trigger "update:domain:group:clicked", groupdata

        onShow : ->
            #validate the group form with the validation rules
            @$el.find( '#add-group-form' ).validate @validationOptions()

        validationOptions : ->
            rules :
                group_name :
                    required : true

                group_description :
                    required : true

            messages :
                group_name : 'Enter valid group name'

        onDomainGroupAdded : ->
            @$el.find( '#success-msg' ).empty()
            msg = "<div class='alert alert-success'>
                               <button class='close' data-dismiss='alert'>&times;</button>
                               Group added for domain sucessfully<div>"
            @$el.find( '#success-msg' ).append msg
            @$el.find( '#btn-reset-group' ).click()

        onEditGroup : ( group_name, group_description )->
            @$el.find( '#btn-save-domain-group' ).text 'Update'

            @$el.find('#btn-new-ticket').click()

            @$el.find( '#group_name' ).val group_name
            @$el.find( '#group_description' ).val group_description

        onGroupUpdated : ->
            @$el.find( '#success-msg' ).empty()
            msg = "<div class='alert alert-success'>
                   <button class='close' data-dismiss='alert'>&times;</button>
                   Group updated sucessfully<div>"
            @$el.find( '#success-msg' ).append msg

    #return the view instance
    ShowGroupView















