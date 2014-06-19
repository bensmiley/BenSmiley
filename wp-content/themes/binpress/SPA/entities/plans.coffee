define [ 'backbone', 'msgbus' ], ( Backbone, msgbus ) ->
    # model
    class PlanModel extends Backbone.Model

        name : 'plan'

        idAttribute : 'plan_id'

    # collection
    class PlanCollection extends Backbone.Collection

        model : PlanModel

        url : ->
            "#{AJAXURL}?action=fetch-all-plans"

    #PUBLIC API
    API =
        getAllPlans : ->
            planCollection = new PlanCollection
            planCollection

        getPlanByPlanId : ( planId ) ->
            planModel = new PlanModel 'plan_id' : planId
            planModel

    #Handlers
    msgbus.reqres.setHandler "get:all:plans", ->
        API.getAllPlans()

    msgbus.reqres.setHandler "get:plan:by:planid",( planId ) ->
        API.getPlanByPlanId planId


    PlanModel : PlanModel
    PlanCollection : PlanCollection

