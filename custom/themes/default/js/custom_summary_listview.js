function ajaxSummary() {

    sugarListView.get_checks();
    var uid = document.MassUpdate.uid.value;
    var module = document.MassUpdate.module.value;
    var fields = listViewDataRow();
    var td_count = $('.list tr.pagination:first').next().next().children().length;

    $.ajax({
        data: {'fields': fields},
        url: "index.php?entryPoint=sum&module=" + module + "&uid=" + uid + "&td_count=" + td_count,
        type: 'post',
        dataType: 'json',
        success: function (result) {
            if (result.lenght != 0) {
                var html;
                if ($('#summaryRows').length == 0) {
                    html = '<tr style="text-align:left;" class="evenListRowS1" id="summaryRows">' + result + '</tr>';
                    $('.list tr.pagination:last').before(html);
                } else {
                    $('#summaryRows').html(result);
                }
            }
        }


    })
}

function listViewDataRow() {
    var fields = Array();
    var field = new Object();
    $('.list tr.pagination:first').next().next().children().each(function (index) {

        if ($(this).attr('data-sugar-field-name')) {
            var type = $(this).attr('data-sugar-field-type');
            var name = $(this).attr('data-sugar-field-name');
            var align = $(this).attr('align');

            if (type == 'int' || type == 'float' || type == 'currency') {
                field = {
                    'type': type,
                    'name': name,
                    'index': index,
                    'align': align
                }
                fields.push(field);
            }
        }

    });
    return fields
}