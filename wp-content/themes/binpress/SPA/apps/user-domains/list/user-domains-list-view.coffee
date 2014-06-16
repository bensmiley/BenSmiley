#include the files for the app
define [ 'marionette'
         'text!apps/user-domains/templates/listUserDomain.html' ], ( Marionette, listUserDomainTpl )->

    # Item view  for displaying the user domains: shows each domain
    class DomainItemView extends Marionette.ItemView

        tagName : 'tr'

        template : '<td>{{post_title}}</td>
                    <td>{{plan_name}}</td>
                    <td>None</td>
                    <td class="center">
                        <a href="#domains/edit/{{ID}}" class="glyphicon glyphicon-pencil btn-edit-domain"></a>
                        <span class="glyphicon glyphicon-trash" id="btn-delete-domain"></span>
                    </td>'
        events :
            'click #btn-delete-domain' : ->
                if confirm( 'Are you sure?' )
                    @trigger "delete:domain:clicked", @model

    #Empty item view, when no domains are added
    class EmptyView extends Marionette.ItemView

        tagName : 'tr'

        template : '<td>You have not added any domains yet</td>'


    # Main view  for displaying the user domains list
    class DomainListView extends Marionette.CompositeView

        template : listUserDomainTpl

        itemView : DomainItemView

        emptyView : EmptyView

        itemViewContainer : 'tbody'

    # return the view instance
    DomainListView : DomainListView











