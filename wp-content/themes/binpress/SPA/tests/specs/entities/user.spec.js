define(['entities/user', 'msgbus'], function(User, msgbus) {
  describe("User Entity", function() {
    it("must be defined", function() {
      return expect(User).toBeDefined();
    });
    return it("must have a handler to get current user model", function() {
      return expect(msgbus.reqres.hasHandler('get:current:user:model')).toBe(true);
    });
  });
  return describe("when a users model is fetched", function() {
    var ABC, spy, user;
    user = null;
    spy = null;
    ABC = {
      render: function() {
        return "hello";
      }
    };
    beforeEach(function() {
      spy = spyOn(ABC, "render");
      return user = new User;
    });
    afterEach(function() {});
    return it("must have a name", function() {
      ABC.render();
      return expect(spy.callCount).toBe(1);
    });
  });
});
