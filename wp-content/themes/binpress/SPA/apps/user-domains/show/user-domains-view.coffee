#include the files for the app
define ['app', 'text!apps/user-domains/templates/listUserDomain.html'], (App, userDomainTpl)->

    #start the app module
    App.module 'UserDomainAppView', (View, App)->

        # Layout for displaying the user domains
        class View.UserDomainView extends Marionette.Layout

            className : 'user-domain-container'

            template : userDomainTpl

            regions :
                domainListRegion : '#user-domain-list'

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

        # Main view  for displaying the user domains
        class View.DomainListView extends Marionette.CompositeView

            template : '<thead>
                                                    <tr>
                                                        <th>Domain Name</th>
                                                        <th>Plan</th>
                                                        <th>Billing Due Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>'

            className : 'table table-striped'

            tagName : 'table'

            itemView : DomainItemView

            emptyView : EmptyView

            itemViewContainer : 'tbody'












