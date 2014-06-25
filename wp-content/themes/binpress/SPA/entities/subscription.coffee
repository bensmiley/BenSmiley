define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->
    # model
    class SubscriptionModel extends Backbone.Model

        name : 'subscription'
        idAttribute : 'domain_id'


    #PUBLIC API
    API =
        getSubscriptionByDomainId : ( domainId ) ->
            subscriptionModel = new SubscriptionModel 'domain_id' : parseInt domainId
            subscriptionModel

    #Handlers
    msgbus.reqres.setHandler "get:subscription:for:domain",( domainId ) ->
        API.getSubscriptionByDomainId domainId

    SubscriptionModel