require ["modules/sign-up"], (SignUp) ->

    describe "Sign up Form", ->

        it "serailize data must be proper", ->
            values = [ (name : "key", value : "val") ]
            expect(SignUp.getFromData(values)).toBe("key" : "val")