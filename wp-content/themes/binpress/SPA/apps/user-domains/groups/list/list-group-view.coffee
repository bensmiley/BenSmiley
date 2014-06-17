#include the files for the app
define [ 'marionette' ], ( Marionette )->

    # Layout for add-edit user domains
    class ListGroupView extends Marionette.ItemView

        template : '<table class="table table-hover table-condensed" id="example">
                    <thead>
                    <tr>
                    <th style="width:15%" >Group Name</th>
                    <th style="width:30%" data-hide="phone,tablet">Description</th>
                    <th style="width:10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr >
                    <td class="v-align-middle"><span class="muted">Group 1</span></td>
                    <td><span class="muted">frequently involving research or design</span></td>
                    <td class="v-align-middle">
                    <span class="glyphicon glyphicon-pencil"></span>  &nbsp;
                    <span class="glyphicon glyphicon-trash"></span>
                    </td>
                    </tr>
                    <tr>
                    <td><span class="muted">Group 2</span></td>
                    <td><span class="muted">Something goes here</span></td>
                    <td>
                    <span class="glyphicon glyphicon-pencil"></span>  &nbsp;
                    <span class="glyphicon glyphicon-trash"></span>
                    </td>
                    </tr>
                    <tr>
                    <td class="v-align-middle"><span class="muted">Group 3</span></td>
                    <td><span class="muted">Redesign project template</span></td>
                    <td>
                    <span class="glyphicon glyphicon-pencil"></span>  &nbsp;
                    <span class="glyphicon glyphicon-trash"></span>
                    </td>
                    </tr>
                    <tr>
                    <td class="v-align-middle"><span class="muted">Group 4</span></td>
                    <td><span class="muted">A project in business and science is typically defined</span></td>
                    <td>
                    <span class="glyphicon glyphicon-pencil"></span>  &nbsp;
                    <span class="glyphicon glyphicon-trash"></span>
                    </td>
                    </tr>


                    </tbody>
                    </table>'

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
    ListGroupView















