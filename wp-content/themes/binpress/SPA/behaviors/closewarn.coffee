define ['marionette'], (Marionette)->

    class CloseWarn extends Marionette.Behavior

        # you can set default options
        # just like you can in your Backbone Models
        # they will be overriden if you pass in an option with the same key
        defaults:
            message: "you are closing!"

        # behaviors have events that are bound to the views DOM
        events:
            "click @ui.close": "warnBeforeClose"

        warnBeforeClose: ->
            alert @options.message

            # every Behavior has a hook into the
            # view that it is attached to
            @view.close()

    CloseWarn