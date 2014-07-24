jQuery(function($){
    $('.isr-rate').raty({
        path: '/bundles/ideatostarrating/images/',
        score: function() {
            return $(this).data('score');
        },
        click: function(score, evt) {
            var t = $(this);
            var url = t.data('route');
            var data = {
                contentId: t.data('contentid'),
                score: score
            };
            t.raty('readOnly', true);

            $.post( url, data)
                .done(function(result){
                    t.raty('score', result);
                })
                .fail(function(){
                    alert('An error occurred. Please try again');
                    t.raty('readOnly', false);
                });
        }
    });
});