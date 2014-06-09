#include the files for the app
#TODO: remove the backbonesyphon from the file
define [ 'app'
         'text!apps/user-domains/templates/ListUserDomain.html' ], ( App, listUserDomainTpl )->

    # Layout for displaying the user domains
    class UserDomainView extends Marionette.Layout

        className : 'user-domain-container'

        template : '<!-- TABS -->
                                <ul class="nav nav-tabs" id="tab-01">
                                    <li class="active"><a href="#domain-details">Domain Details</a></li>
                                    <li><a href="#tab1FollowUs">Domain Plan</a></li>
                                    <li><a href="#tab1Inspire">Statistics</a></li>
                                </ul>

                                <div class="tab-content">
                                    <!-- Show user domain and add new user domain region -->
                                    <div class="tab-pane active" id="domain-details"></div>
                                    <hr>
                                </div>'

        regions :
            domainListRegion : '#domain-details'

        events :
            'click #btn-add-domain' : ->
                @trigger "add:user:domain:clicked"


    # Item view  for displaying the user domains: shows each domain
    class DomainItemView extends Marionette.ItemView

        tagName : 'tr'

        template : '<td>Minyawns</td>
                                <td>Silver</td>
                                <td>09/21/2014</td>
                                <td class="center">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                    <span class="glyphicon glyphicon-trash"></span>
                                </td>'


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

    DomainListView : DomainListView
    UserDomainView : UserDomainView











