define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->
    # model
    class PlanModel extends Backbone.Model

        name : 'plan'

        idAttribute : 'ID'

    # model to get the current plan id for a domain
    class PlanIdModel extends Backbone.Model

        name : 'planId'

    # collection
    class PlanCollection extends Backbone.Collection

        model : PlanModel

        url : ->
            "#{AJAXURL}?action=fetch-all-plans"

    #PUBLIC API
    API =

        getPlanById : ->
            planModel = new PlanModel
            planModel
#                data :
#                    'domain_id' : @domainId
#                    'plan_id' : @planId
#                    'action' : 'read-plan'
#                success : @showCurrentPlanView

        getAllPlans : ->
            planCollection = new PlanCollection
            planCollection

        getCurrentPlanId : ( domainID ) ->
            planIdModel = new PlanIdModel

    #Handlers
    msgbus.reqres.setHandler "get:plan:by:id", ->
        API.getPlanById()

    msgbus.reqres.setHandler "get:all:plans", ->
        API.getAllPlans()

    msgbus.reqres.setHandler "get:current:plan:id", ( domainID )->
        API.getCurrentPlanId( domainID )


    PlanModel

