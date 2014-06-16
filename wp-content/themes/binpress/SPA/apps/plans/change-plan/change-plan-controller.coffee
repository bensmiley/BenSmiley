#include the files for the app
define [ 'app'
         'regioncontroller'
         'apps/plans/change-plan/change-plan-view'
         'msgbus' ], ( App, RegionController, ChangePlanView, msgbus )->

    #start the app module
    App.module "PlansApp.Change", ( Change, App, BackBone, Marionette, $, _ )->

        # Controller class for changing the plan
        class ChangePlanController extends RegionController

            initialize : ( opts )->
                @domainId = opts.domainID

                @layout = @getLayout()

                @show @layout

                planIdModel = msgbus.reqres.request "get:current:plan:id", @domainId
                planIdModel.fetch
                    data :
                        'domain_id' : @domainId
                        'action' : 'get-current-domain-plan-id'
                    success : @planIDModelFetched

            planIDModelFetched : ( planIdModel )=>
                #get change plan  layout
                @layout = @getLayout planIdModel


            getLayout : ->
                new ChangePlanView.ChangePlanLayout


        #handler for changing the domain plan,options to be passed to controller are:
        # region :  App.mainContentRegion
        # domainID : parseInt domainID
        # planID :  planID
        App.commands.setHandler "change:plan", ( opts ) ->
            new ChangePlanController opts



