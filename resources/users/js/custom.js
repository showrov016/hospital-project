$(document).ready(function () {
    $('.select2').each(function (i, obj) {
        let placeholder = $(obj).data('placeholder');
        $(obj).select2({
            'placeholder': placeholder,
            maximumSelectionLength: 3
        }).on('select2:unselect', function (e) {
            //            console.log($(this).attr('id') + ' unselecting ' + e.params.data.id);
            removeUnselectedData($(this).attr('id'), e.params.data.id);
        }).on("select2:select", function (evt) {
            var element = evt.params.data.element;
            var $element = $(element);

            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });
        ;
    });

    $('#profile_upload').click(function () {
        $('#my_propic').trigger('click');
    });
    $('#my_propic').change(function () {
        tempFile = this;
        $('#preview_image').modal('show');
        if (tempFile.files && tempFile.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#preview_image .preview').attr('src', e.target.result);
            };

            reader.readAsDataURL(tempFile.files[0]);
        }
        return true;
    });
    $('#module1').on('change', function () {
        $.ajax({
            url: base_url + 'ajax-call/Features/getSecondModule',
            type: 'POST',
            data: { module1: $(this).val() },
            success: function (response) {
                $('#module2').empty();
                $('#module2').append("<option value='' disabled selected>Select Second Module</option>");
                for (i = 0; i < response.data.length; i++) {
                    $('#module2').append("<option value=" + response.data[i].name + ">" + response.data[i].name + "</option>");
                }
            }
        })
    });

    $('#module2').on('change', function () {
        $.ajax({
            url: base_url + 'ajax-call/Features/getThirdModule',
            type: 'POST',
            data: { module2: $(this).val(), module1: $('#module1').val() },
            success: function (response) {
                $('#module3').empty();
                $('#module3').append("<option value='' disabled selected>Select Third Module</option>");
                for (i = 0; i < response.data.length; i++) {
                    $('#module3').append("<option value=" + response.data[i].name + ">" + response.data[i].name + "</option>");
                }
            }
        });
    });

    $('#facility_list').on('change', function () {
        var fac_id = $(this).val();
        $.ajax({
            url: base_url + 'ajax-call/Features/getManagersOfFacility',
            type: 'POST',
            data: { fac_id: fac_id },
            success: function (response) {
                $('#project_manager').empty();
                $('#project_manager').append("<option value='' disabled selected>Select Facility Location Manager</option>");
                for (i = 0; i < response.data.length; i++) {
                    $('#project_manager').append("<option value=" + response.data[i].user_id + ">" + response.data[i].first_name + " " + response.data[i].last_name + "</option>");
                }
            }
        });
    });

    $('#fac_id').on('change', function () {
        var fac_id = $(this).val();
        $.ajax({
            url: base_url + 'ajax-call/Features/getLocationOfFacility',
            type: 'POST',
            data: { fac_id: fac_id },
            success: function (response) {
                $('#locations').empty();
                $('#locations').append("<option value='' disabled selected>Select Location</option>");
                for (i = 0; i < response.data.length; i++) {
                    $('#locations').append("<option value=" + response.data[i].location_id + ">" + response.data[i].name + "</option>");
                }
            }
        });
    });
    $('.consultant_dependent').css("display", "none");
    $('#consultant_num').on('keyup', function () {
        if ($(this).val() == 0) {
            $('.consultant_dependent').attr('disabled', true);
            $('.consultant_dependent').css("display", "none");
        } else {
            $('.consultant_dependent').attr('disabled', false);
            $('.consultant_dependent').css("display", "block");
        }
    });
    var calendar = $('#calendar').fullCalendar({
        themeSystem: 'bootstrap4',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay',
        },

        height: 800,
        width: 500,
        titleRangeSeparator: '-',
        defaultView: 'month',
        displayEventTime: false,
        eventSources: [
            {
                url: base_url + 'ajax-call/Features/loadShifts/' + $('#calendar').data('location'),
                color: '#4545bc'
            }
        ],
        selectable: true,
        selectHelper: true,
        allDaySlot: false,
        timezone: 'local',
        selectAllow: function (select) {
            //disbale passed dates
            return moment().subtract(1, 'days').diff(select.start) <= 0;
        },

        eventClick: function (event) {
            $('#myModal').modal();
            $.ajax({
                url: base_url + 'ajax-call/Features/getEventDetails',
                type: "POST",
                data: { id: event.id },
                success: function (response) {
                    console.log(response.data);
                    $('#consultants').empty();
                    $('#super_users').empty();
                    for (i = 0; i < response.data.consultants.length; i++) {
                        $('#consultants').append("<option value='" + response.data.consultants[i].user_id + "'>" + response.data.consultants[i].first_name + " " + response.data.consultants[i].last_name + "</option>");
                    }
                    for (i = 0; i < response.data.super_users.length; i++) {
                        $('#super_users').append("<option value='" + response.data.super_users[i].user_id + "'>" + response.data.super_users[i].first_name + " " + response.data.super_users[i].last_name + "</option>");
                    }



                    for (i = 0; i < response.data.shift.length; i++) {
                        if (response.data.shift[i].type == 'consultant') {
                            $('#consultants option[value="' + response.data.shift[i].user_id + '"]').prop('selected', true);
                            //$('#consultants').append("<option selected value='"+response.data[i].user_id+"'>"+response.data[i].first_name+" "+response.data[i].last_name+"</option>");
                        }
                        if (response.data.shift[i].type == 'super_user') {
                            $('#super_users option[value="' + response.data.shift[i].user_id + '"]').prop('selected', true);
                            //$('#super_users').append("<option selected value='"+response.data[i].user_id+"'>"+response.data[i].first_name+" "+response.data[i].last_name+"</option>");
                        }
                    }
                    $('#consultants').select2({
                        maximumSelectionLength: response.data.shift[0].number_const + response.data.shift[0].number_su - $('#super_users option:selected').length

                    });
                    $('#super_users').select2({
                        maximumSelectionLength: response.data.shift[0].number_const + response.data.shift[0].number_su - $('#consultants option:selected').length
                    });
                    $('#event_id').val(response.data.shift[0].event_id);

                }
            });
        },

        editable: false
    });

    $('.updateFacLocMangr').on('click', function () {

        console.log($(this).closest('tr').find('.location').val());
        console.log($(this).closest('tr').find('.facility').val());
        console.log($(this).closest('tr').find('td .phone').val());
        console.log($(this).data('id'));

        $.ajax({
            url: base_url + 'ajax-call/Features/updateFacLocMng',
            type: 'POST',
            data: {
                'user_id': $(this).data('id'),
                'phone': $(this).closest('tr').find('td .phone').val(),
                'location_id': $(this).closest('tr').find('.location').val(),
                'facility_id': $(this).closest('tr').find('.facility').val()
            },
            success: function () {
                window.location.reload();
            }
        });
    });

    $('#s_lname').on('keyup', function () {
        $('#s_uname').val($('#s_lname').val() + "." + $('#s_fname').val());
    });

    $('.update_su').on('click', function () {
        $.ajax({
            url: base_url + 'ajax-call/Features/updateSuser',
            type: 'POST',
            data: { 'user_id': $(this).data('id'), 'location_id': $(this).closest('tr').find('select').val(), 'shift': $(this).closest('tr').find('.shift').val() },
            success: function () {
                //window.location.reload();
            }
        });
    });
    $('#shift_delete').on('click', function () {
        var shifts = $("input[name='shifts[]']:checked").map(function () { return $(this).val(); }).get();
        $.ajax({
            url: base_url + 'ajax-call/Features/deleteShift',
            type: 'POST',
            data: { 'shifts': shifts },
            success: function () {
                window.location.reload();
            }
        });
    });

    $('#location_name').on('keyup', function () {
        console.log($(this).val());
        $.ajax({
            url: base_url + 'ajax-call/Features/checkLocation',
            type: 'POST',
            data: { 'location_name': $(this).val() },
            success: function (resp) {
                console.log(resp);
                if (resp.result == true) {
                    console.log(resp);
                    $('#add_location').prop('disabled', true);
                    $('#err').text("Location Name exist");
                } else {
                    $('#add_location').prop('disabled', false);
                    $('#err').text("");
                }
            }
        });
    });

});

/**
 * Trigger image upload button on click
 */


function saveDemeanor(user_id) {
    var rating = $('select[data-user_id=' + user_id + ']').val();
    $.ajax({
        url: base_url + 'ajax-call/Features/saveDemeanor',
        type: 'POST',
        data: { rating: rating, user_id: user_id },
        success: function () {
            Swal.fire({
                title: 'Success',
                text: 'Demeanor Rating save',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }
    })
}

function upload_image() {
    let files = new FormData(), // you can consider this as 'data bag'
        url = base_url + 'ajax-call/Features/user_profile_update';
    $('#siteLoader').fadeIn();

    files.append('user_image', $('#my_propic')[0].files[0]); // append selected file to the bag named 'file'
    files.append('type', 'teacher_profile');

    $.ajax({
        type: 'post',
        url: url,
        processData: false,
        contentType: false,
        data: files,
        success: function (response) {
            $('#siteLoader').fadeOut();
            if (response.success) {
                if (tempFile.files && tempFile.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.profile-info .image img').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(tempFile.files[0]);
                }
                tempFile = null;
            } else {

            }
            console.log(response.msg);
        },
        error: function (err) {
            $('#siteLoader').fadeOut();
            console.log(err);
        }
    });

}

function updateRating(rating_num, user_id, rating_val) {
    $.ajax({
        url: base_url + 'ajax-call/Features/saveRating',
        type: 'POST',
        data: { rating_num: rating_num, user_id: user_id, rating_val: rating_val },
        success: function () {

        }
    });
}

function updateTeamLead(consultant_uid, team_lead_uid){
    $.ajax({
        url: base_url + 'ajax-call/Features/saveTeamLead',
        type: 'POST',
        data: { consultant_uid: consultant_uid, team_lead_uid: team_lead_uid},
        success: function () {

        }
    });
}   