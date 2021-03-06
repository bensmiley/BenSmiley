define(['msgbus', 'configs/backbone.config'], function(msgbus) {
  return describe("Msgbus test", function() {
    it("must be defined", function() {
      expect(msgbus).toBeDefined();
      expect(msgbus.reqres).toBeDefined();
      expect(msgbus.commands).toBeDefined();
      return expect(msgbus.vent).toBeDefined();
    });
    return it("must have all ajax handlers defined", function() {
      expect(msgbus.commands.hasHandler("when:created")).toBe(true);
      expect(msgbus.commands.hasHandler("when:updated")).toBe(true);
      expect(msgbus.commands.hasHandler("when:deleted")).toBe(true);
      return expect(msgbus.commands.hasHandler("when:read")).toBe(true);
    });
  });
});
