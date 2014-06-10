define [ 'entities/user', 'msgbus' ], ( User, msgbus ) ->

    describe "User Entity", ->

        it "must be defined", ->
            expect( User ).toBeDefined()

        it "must have a handler to get current user model", ->
            expect( msgbus.reqres.hasHandler 'get:current:user:model' ).toBe true

    describe "when a users model is fetched", ->

        user = null
        spy = null
        ABC =
            render : ->
                return "hello"

        beforeEach ->
            spy = spyOn ABC, "render"
            user = new User

        afterEach ->

        it "must have a name", ->
            ABC.render()
            expect spy.callCount
                .toBe 1

#            expect user.name
#                .toBeDefined()
#
#            expect window.SITEURL
#                .toBe "http://localhost/bensmiley/"