runningAjax = null;

$(function () {
    'use strict';

    $('#sidebar, #main_content, span.navbar-brand').removeClass('expand');

    $('#sidebar').hover(
				
        function () {
            $('#sidebar, #main_content, span.navbar-brand').addClass('expand');
        }, 
         
        function () {
            $('#sidebar, #main_content, span.navbar-brand').removeClass('expand');
        }
     );

    

    $('#dataTable').DataTable({"order": [[ 0, "desc" ]]});

    $('.rich-editor').each(function () {
        var id = $(this).attr('id');
        CKEDITOR.replace(id, {
            toolbarGroups: [
                {name: 'document', groups: ['mode', 'document', 'doctools']},
                {name: 'clipboard', groups: ['clipboard', 'undo']},
                {name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
                {name: 'forms', groups: ['forms']},
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
                {name: 'links', groups: ['links']},
                {name: 'insert', groups: ['insert']},
                {name: 'styles', groups: ['styles']},
                {name: 'colors', groups: ['colors']},
                {name: 'tools', groups: ['tools']},
                {name: 'others', groups: ['others']},
                {name: 'about', groups: ['about']}
            ],

            removeButtons: 'Cut,Save,ShowBlocks,Maximize,About,Image,Flash,Smiley,SpecialChar,PageBreak,Anchor,Language,BidiRtl,BidiLtr,Indent,Outdent,CopyFormatting,Superscript,Subscript,Strike,Form,Checkbox,Radio,TextField,Textarea,Button,ImageButton,HiddenField,Scayt,SelectAll,Find,Replace,Copy,Paste,PasteText,PasteFromWord,Templates,Print,Preview'
        });
    });

    $('#addMoreParams').click(function () {
        var paramInputs = $('#paramListTbody tr:last-child').clone();
        if (paramInputs.find('td > input:first-child').val() != '') {
            paramInputs.find('td input:text, td textarea').val('');
            paramInputs.find('td select').each(function () {
                this.selectedIndex = 0;
            });
            paramInputs.find('td.bulk-param-add-remove').html('<span class="show-pointer btn btn-danger" onclick="remove_parent_row(this)"><i class="fa fa-minus-circle"></i></span>');

            $('#paramListTbody').append(paramInputs);
        } else {
            alert('Please fillup the last Parameter inputs!');
        }
    });

});

function change_user_account_status(status, account_id) {
    var error = false;
    if (runningAjax != null) {
        runningAjax.abort();
    }
    runningAjax = $.ajax({
        url: base_url + 'admin/manage-requests',
        type: 'POST',
        async: "false",
        data: {
            request_type: 2,
            status: status,
            user_id: account_id
        },
        success: function (data) {
            var obj = JSON.parse(data);
            if (typeof obj.success !== 'undefined') {
                var success = parseInt(obj.success);
                if (success) {
                    error = false;
                    $('span.msg-change-status').removeClass('text-danger').addClass('text-success').html('Account status updated successfully!');
                } else {
                    error = true;
                    $('span.msg-change-status').removeClass('text-success').addClass('text-danger').html('Account status update failed!');
                }

            } else {
                alert('ajax request failed');
            }
            runningAjax = null;
        },
        error: function (data) {
            runningAjax = null;
        }
    });
    return error;
}

function remove_parent_row(obj) {
    var ptr = obj.parentElement.parentElement;
    ptr.parentNode.removeChild(ptr);
}







