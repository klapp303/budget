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
        var word_lists = word.split(':');
        var value = word_lists[0];
        var amount = word_lists[1];
        var genre = word_lists[2];
        if (!value) {
            alert('収支名を選んでください');
            return false;
        }
        //タイトル
        $('.js-insert_value').insertAtCaret(value);
        //金額
        if (amount) {
          $('.js-insert_amount').insertAtCaret(amount);
        }
        //種類
        if (genre) {
          $('.js-insert_genre').val(genre);
        }
    });
});