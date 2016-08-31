jQuery(function($) {
    $.fn.extend({
        insertAtCaret: function(v) {
            var o = this.get(0);
            o.focus();
            if (jQuery.browser.msie) {
//            if (jQuery.support.noCloneEvent) {
                var r = document.selection.createRange();
                r.text = v;
                r.select();
            } else {
                var s = o.value;
                var p = o.selectionStart;
                var np = p + v.length;
                o.value = s.substr(0, p) + v + s.substr(p);
                o.setSelectionRange(np, np);
            }
        }
    });
    
    $('.js-insert').click(function() {
//        var word = $(this).attr('data');
        var word = $('.js-insert_data').val();
        if (!word) {
            alert('タイトルを選んでください');
            return false;
        }
        $('.js-insert_area').insertAtCaret(word);
    });
});