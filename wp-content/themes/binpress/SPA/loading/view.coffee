# list currency views
define [ 'marionette', 'spin', 'jqueryspin' ], ( Marionette, spin, jqueryspin ) ->
    class LoadingView extends Marionette.ItemView

        template : _.template( '<i></i>', {} )

        className : 'loading-container'

        onShow : ->
            opts = @._getOptions()
            @$el.spin opts

        onClose : ->
            @$el.spin false

        _getOptions : ->
            lines : 10
            length : 6
            width : 2.5
            radius : 7
            corners : 1
            rotate : 9
            direction : 1
            color : '#ff9e2c'
            speed : 1
            trail : 60
            shadow : false
            hwaccel : true
            className : 'spinner'
            zIndex : 1030
            top : 'auto'
            left : 'auto'
