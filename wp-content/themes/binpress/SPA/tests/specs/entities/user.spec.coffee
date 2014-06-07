define ['entities/user', 'msgbus'], (User, msgbus) ->

    describe "User Entity", ->

        it "must be defined", ->
            expect(User).toBeDefined()

        it "must have a handler to get current user model", ->
            expect(msgbus.reqres.hasHandler 'get:current:user:model').toBe true