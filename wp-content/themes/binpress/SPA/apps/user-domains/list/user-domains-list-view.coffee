#include the files for the app
define [ 'marionette'
         'text!apps/user-domains/templates/listUserDomain.html' ], ( Marionette, listUserDomainTpl )->

    # Item view  for displaying the user domains: shows each domain
    class DomainItemView extends Marionette.ItemView

        tagName : 'tr'

        template : '<td>{{post_title}}</td>
                    <td>{{plan_name}}</td>
                    <td>{{post_date}}</td>'

        events :
            'click' : ->
                domainId = @model.get 'ID'
                #redirect the page to edit of domain
                mainUrl = window.location.href.replace Backbone.history.getFragment(), ''
                redirect_url = "#{mainUrl}domains/edit/#{domainId}"
                window.location.href = redirect_url

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

    # return the view instance
    DomainListView : DomainListView











