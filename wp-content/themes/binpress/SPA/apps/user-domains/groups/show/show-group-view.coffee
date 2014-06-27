#include the files for the app
define [ 'marionette', 'text!apps/user-domains/templates/groupsTemplate.html' ], ( Marionette, groupsTpl )->

    #View for listing each group
    class SingleGroupView extends Marionette.ItemView

        template : '<td class="v-align-middle"><span class="muted">{{group_name}}</span></td>
                            <td><span class="muted">{{group_description}}</span></td>'

        tagName : 'tr'

        events :
            'click' : ->
                @trigger "edit:group:clicked", @model

        modelEvents :
            'change' : 'render'

    #View for empty groups
    class EmptyGroupView extends Marionette.ItemView

        template : '<td class="v-align-middle" colspan="3" align="center" style="background:#e2e8eb;">
                                <span class="muted">No Groups added</span></td>
                            </td>'

        tagName : 'tr'


    # Main view  for listing the groups for domain
    class ShowGroupView extends Marionette.CompositeView

        template : groupsTpl

        itemViewContainer : 'tbody'

        itemView : SingleGroupView

        emptyView : EmptyGroupView

        initialize : ->
            #listen to edit group click in item view
            @listenTo @, "itemview:edit:group:clicked",@editGroup

        editGroup : ( itemview, model )->
            @editModel = model
            group_name = model.get 'group_name'
            group_description = model.get 'group_description'

            @$el.find( '#btn-new-ticket' ).click()
            @$el.find( '#btn-save-domain-group' ).text 'Update'
            @$el.find( '.btn-delete-group' ).show()

            @$el.find( '#group_name' ).val group_name
            @$el.find( '#group_description' ).val group_description

        events :
            'click #btn-save-domain-group' : ->
                #check if the form is valid
                if @$el.find( '#add-group-form' ).valid()
                    #get all serialized data from the form
                    groupdata = Backbone.Syphon.serialize @
                    groupdata.domain_id = Marionette.getOption @, 'domainId'

                    #check if edit or save action
                    buttonText = @$el.find( '#btn-save-domain-group' ).text()
                    formAction = buttonText.trim()

                    if formAction == "Save"
                        @trigger "save:domain:group:clicked", groupdata
                    else
                        @trigger "update:domain:group:clicked", groupdata, @editModel

        #events on click of add groups button
            'click #btn-new-ticket' : ->
                @$el.find( '#success-msg' ).empty()
                @$el.find( '#new-ticket-wrapper' ).slideToggle "fast", "linear"
                @$el.find( '#btn-new-ticket' ).css 'display' : 'none'
                @$el.find( '#group_name' ).val ' '
                @$el.find( '#group_description' ).val ' '
                @$el.find( '#btn-save-domain-group' ).text 'Save'
                @$el.find( '.btn-delete-group' ).hide()

        #events on click of close button
            'click #btn-close-ticket' : ->
                @$el.find( '#new-ticket-wrapper' ).slideToggle "fast", "linear"
                @$el.find( '#btn-new-ticket' ).css 'display' : 'inline'

        #delete the group click event
            'click .btn-delete-group' : ->
                if confirm( 'Are you sure?' )
                    @trigger "delete:group:clicked", @editModel


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

        # on adding new group to domain
        onDomainGroupAdded : =>
            msg = "Group added for domain sucessfully"
            @showSuccessMsg msg
            @$el.find( '#btn-reset-group' ).click()

        #on success of group update
        onGroupUpdated : =>
            msg = "Group updated sucessfully"
            @showSuccessMsg msg

        #close the edit section on delete of group
        onGroupDeleted : ->
            @$el.find( '#btn-close-ticket' ).click()

        # show the success msg
        showSuccessMsg : ( msgText )->
            @$el.find( '#success-msg' ).empty()
            msg = "<div class='alert alert-success'>
                               <button class='close' data-dismiss='alert'>&times;</button>
                               #{msgText}<div>"
            @$el.find( '#success-msg' ).append msg

    #return the view instance
    ShowGroupView















