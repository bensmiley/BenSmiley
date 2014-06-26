#include the files for the app
define [ 'marionette'
         'text!apps/user-domains/templates/listUserDomain.html' ], ( Marionette, listUserDomainTpl )->

    # Item view  for displaying the user domains: shows each domain
    class DomainItemView extends Marionette.ItemView

        tagName : 'tr'

        template : '<td>{{post_title}}</td>
                            <td>{{plan_name}}</td>
                            <td>None</td>'
        events :
            'click .btn-delete-domain' : ->
                if confirm( 'Are you sure?' )
                    @trigger "delete:domain:clicked", @model


    #Empty item view, when no domains are added
    class EmptyView extends Marionette.ItemView

        tagName : 'tr'

        template : '<td colspan="4" align="center">You have not added any domains yet</td>'


    # Main view  for displaying the user domains list
    class DomainListView extends Marionette.CompositeView

        template : listUserDomainTpl

        itemView : DomainItemView

        emptyView : EmptyView

        itemViewContainer : 'tbody'

        #show the loader on click of delete
        events :
            'click .btn-delete-domain' : ->
                @$el.find( '.ajax-loader-login' ).show()

        #hide the loader on succesful delete of domain
        onDomainDeleted : ->
            @$el.find( '.ajax-loader-login' ).hide()


    # return the view instance
    DomainListView : DomainListView











