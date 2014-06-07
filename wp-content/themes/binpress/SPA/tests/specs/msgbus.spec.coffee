define ['msgbus', 'configs/backbone.config' ], (msgbus)->

    describe "Msgbus test", ->

        it "must be defined", ->
            expect(msgbus).toBeDefined()
            expect(msgbus.reqres).toBeDefined()
            expect(msgbus.commands).toBeDefined()

        it "must have all ajax handlers defined", ->
            expect(msgbus.commands.hasHandler "when:created").toBe true
            expect(msgbus.commands.hasHandler "when:updated").toBe true
            expect(msgbus.commands.hasHandler "when:deleted").toBe true
            expect(msgbus.commands.hasHandler "when:read").toBe true
