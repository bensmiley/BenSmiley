define ['backbone','marionette', 'jquery', 'underscore'], (Backbone, Marionette, $, _)->

    # create a channel
    msgbus = Backbone.Wreqr.radio.channel "global"

    # TODO: Apply DRY principle
    msgbus.commands.setHandler "when:read", (entities, callback) ->
        xhrs = _.chain([entities]).flatten().pluck("_read").value()
        $.when(xhrs...).done ->
            callback()

    msgbus.commands.setHandler "when:created", (entities, callback) ->
        xhrs = _.chain([entities]).flatten().pluck("_create").value()
        $.when(xhrs...).done ->
            callback()

    msgbus.commands.setHandler "when:deleted", (entities, callback) ->
        xhrs = _.chain([entities]).flatten().pluck("_delete").value()
        $.when(xhrs...).done ->
            callback()

    msgbus.commands.setHandler "when:updated", (entities, callback) ->
        xhrs = _.chain([entities]).flatten().pluck("_update").value()
        $.when(xhrs...).done ->
            callback()

    msgbus