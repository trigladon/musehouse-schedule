/**
 * Created by bdionis on 31.03.17.
 */
function onClickMonth(currentDate, changes, whtsh) {

    $.ajax({
        url: 'calendar',
        type: 'post',
        data: {
            currentDate: currentDate,
            changes: changes,
            whtsh: whtsh,
            _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
        },
        success: function (data) {
            $('#mainCalendar').html(data);
        },
        error: function () {
            alert('Error!!!');
        }
    });
}

$(function() {
    $('.popup-delete').click(function(e) {
        e.preventDefault();
        var modal = $('#modal-delete').modal('show');
        modal.find('.modal-body').load($('.modal-dialog'));
        $('#delete-confirm').prop('disabled', false);
        var that = $(this);
        var id = that.data('id');
        var name = that.data('name');
        modal.find('.modal-name').text(name);

        $('#delete-confirm').click(function(e) {
            e.preventDefault();
            $('#delete-confirm').prop('disabled', true);
            window.location = '?deleteId='+id;
        });
    });
});

$(function() {
    $('.popup-update').on('click', function(e) {
        $('#upinstr-form')[0].reset();
        var modal = $('#modal-update').modal('show');
        modal.find('.modal-body').load($('.modal-dialog'));
        e.preventDefault();
        var that = $(this);
        var id = that.data('id');
        var name = that.data('name');

        $('.upfield').val(name);
        $('.upfieldId').val(id);

    });
});

$(function () {
    $('#datetimepicker5').datetimepicker({
        format: 'DD-MM-YYYY'
    });
});

$(function () {
    $('#filterDate').datetimepicker({
        format: 'MMM-YYYY',
        viewMode: 'months'
    });
});

$(function () {
    $('#datetimepicker6').datetimepicker({
        format: 'HH:mm',
        stepping: 5,
        showClose: true
    });
    $('#datetimepicker7').datetimepicker({
        format: 'HH:mm',
        stepping: 5,
        useCurrent: false, //Important! See issue #1075
        showClose: true
    });
    // $("#datetimepicker6").on("dp.change", function (e) {
    //     $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    // });
    // $("#datetimepicker7").on("dp.change", function (e) {
    //     $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    // });
});

$(document).ready(function () {
    $('body').on('click touchstart', '.popup-addLesson', function(e) {

        $('#addLesson-form')[0].reset();
        $('#lessonIdToUpdate').val('');

        $("#addlessonform-instricon_id, #addlessonform-statusschedule_id, #addlessonform-student_id, #addlessonform-lesson_length").select2().val(null).trigger('change.select2');

        $('#addlessonform-student_id').select2({
            escapeMarkup: function (text) { return text; },
            placeholder: 'Student',
            theme: 'bootstrap',
            allowClear: true,
            minimumResultsForSearch: Infinity,
            width: '100%'
        });

        $('#addlessonform-lesson_length').select2({
            escapeMarkup: function (text) { return text; },
            placeholder: 'Choose...',
            theme: 'bootstrap',
            allowClear: true,
            minimumResultsForSearch: Infinity,
            width: '100%'
        });

        $('#addlessonform-instricon_id').select2({
            escapeMarkup: function (text) { return text; },
            placeholder: 'Type of the Lesson',
            theme: 'bootstrap',
            allowClear: true,
            minimumResultsForSearch: Infinity,
            width: '100%'
        });

        $('#addlessonform-statusschedule_id').select2({
            escapeMarkup: function (text) { return text; },
            placeholder: 'Status',
            theme: 'bootstrap',
            allowClear: true,
            minimumResultsForSearch: Infinity,
            width: '100%'
        });

        var modal = $('#modal-addLesson').modal('show');
        modal.find('.modal-body').load($('.modal-dialog'));
        $('.headerLessonCalendarForm').text('Add a new Lesson!');
        $('#addLesson-confirm').removeClass('btn-warning');
        $('#addLesson-confirm').addClass('btn-success');
        $('#addLesson-confirm').text('Add Lesson');

        e.preventDefault();
        var currentDate = document.getElementById('infoDiv').getAttribute('currentDate');
        var whtsh = document.getElementById('infoDiv').getAttribute('whtsh');
        var changes = '';


        var that = $(this);
        var month = that.data('month');
        var day = that.data('day');
        var year = that.data('year');
        var week = that.data('week');

        $('#datetimepicker5').val(day+'-'+month+'-'+year);

        onClickMonth(currentDate, changes, whtsh);
    });
});

$(document).on('beforeSubmit', '#addLesson-form', function () {
    $('#addLesson-confirm').prop('disabled', true);
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function (data) {
            $('#addLesson-confirm').prop('disabled', false);
            //checking if the Object, because validation errors return as Objects
            if (data === Object(data)){
                //if success, then validation wasn't pass
                if(data.success == 0){
                    //showing validation errors
                    $.each(data.validate, function (key, value) {
                        $('#addLesson-form').yiiActiveForm('updateAttribute', key, '');
                        $('#addLesson-form').yiiActiveForm('updateAttribute', key, [value]);
                    });
                }else {
                    if (data.success == 1){
                        $('#modal-addLesson').modal('hide');
                        $('#addLesson-form')[0].reset();

                        var currentDate = document.getElementById('infoDiv').getAttribute('currentDate');
                        var whtsh = document.getElementById('infoDiv').getAttribute('whtsh');
                        var changes = '';
                        onClickMonth(currentDate, changes, whtsh);
                    }else{
                        alert('Something is wrong, please try again');
                    }
                }
            }else{
                alert('Something is wrong, please try again');
            }
        },
        error: function (data) {
            $('#addLesson-confirm').prop('disabled', false);

        }
    });
    return false; //reject the usual form submit
});

$(document).on('beforeSubmit', '#filter-form', function () {
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function (data) {
            //checking if the Object, because validation errors return as Objects
            if (data === Object(data)){
                //if success, then validation wasn't pass
                if(data.success == 0){
                    //showing validation errors
                    $.each(data.validate, function (key, value) {
                        $('#filter-form').yiiActiveForm('updateAttribute', key, '');
                        $('#filter-form').yiiActiveForm('updateAttribute', key, [value]);
                    });
                }else {
                    if (data.success == 1){
                        $('#filter-form')[0].reset();
                        var currentDate = document.getElementById('infoDiv').getAttribute('currentDate');
                        var whtsh = document.getElementById('infoDiv').getAttribute('whtsh');
                        var changes = '';
                        onClickMonth(currentDate, changes, whtsh);
                    }else{
                        alert('Something is wrong, please try again');
                    }
                }
            }else{
                alert('Something is wrong, please try again');
            }
        }
    });
    return false; //reject the usual form submit
});

function showLayer(layerName, qntlessons, week)
{
    if (document.getElementById) // Netscape 6 and IE 5+
    {
        var targetElement = document.getElementById(layerName);
        targetElement.style.display = 'block';
        $('#iconChange'+layerName).replaceWith('<i id="iconChange'+layerName+'" class="fa fa-caret-up iconShowHide" aria-hidden="true"></i>');
        $('#showMoreActions'+layerName).attr('onclick',  'hideLayer('+layerName+', '+qntlessons+', '+week+')');
        var weeks = document.getElementsByTagName("li");

        for (var i = 0; i < weeks.length; i++) {
            var cell = weeks[i].getAttribute("week");
            if ( cell == week ) {
                weeks[i].style.height = qntlessons+'px';
            }
        }
    }
}

function hideLayer(layerName, qntlessons, week)
{
    if (document.getElementById)
    {
        var targetElement = document.getElementById(layerName);
        targetElement.style.display = 'none';
        $('#iconChange'+layerName).replaceWith('<i id="iconChange'+layerName+'" class="fa fa-caret-down iconShowHide" aria-hidden="true"></i>');
        $('#showMoreActions'+layerName).attr('onclick',  'showLayer('+layerName+', '+qntlessons+', '+week+')');
        var weeks = document.getElementsByTagName("li");

        for (var i = 0; i < weeks.length; i++) {
            var cell = weeks[i].getAttribute("week");
            if ( cell == week ) {
                weeks[i].style.height = '151px';
            }
        }
    }
}

$(document).on('click', '#filter-clear', function (event) {
    event.preventDefault();
    $.ajax({
        url: 'calendar',
        type: 'post',
        data: {clearFilter: 'delete'},
        success: function (data) {
            if (data.success == 1){
                var currentDate = document.getElementById('infoDiv').getAttribute('currentDate');
                var whtsh = document.getElementById('infoDiv').getAttribute('whtsh');
                var changes = '';
                onClickMonth(currentDate, changes, whtsh);
            }else{
                alert('Something is wrong, please try again');
            }
        },
        error: function () {
            alert('error appeared');
        }
    });
});

$(document).on('click', '#lesson-delete', function (event) {
    event.preventDefault();
    var modal = $('#modal-deleteLesson').modal('show');
    modal.find('.modal-body').load($('.modal-dialog'));
    delete window.lessonId;

    $('#lessonDate').text($(this).attr('lessonDate'));
    $('#lessonTime').text($(this).attr('lessonTime'));
    $('#lessonTeacher').text($(this).attr('lessonTeacher'));
    $('#lessonType').replaceWith($(this).attr('lessonType')+'<img src="/images/icons/'+$(this).attr('lessonIcon')+'" class="icon_reg" style="margin: 0 5px">');

    window.lessonId = $(this).attr('lessonId');

    $('#delete-confirm').click(function(e) {
        $("#delete-confirm").prop("disabled", true);
        e.preventDefault();

        $.ajax({
            url: 'calendar',
            type: 'post',
            data: {deleteLesson: window.lessonId},
            success: function (data) {
                if (data.success == 1){
                    $('#modal-deleteLesson').modal('hide');
                    $("#delete-confirm").prop("disabled", false);
                    var currentDate = document.getElementById('infoDiv').getAttribute('currentDate');
                    var whtsh = document.getElementById('infoDiv').getAttribute('whtsh');
                    var changes = '';
                    onClickMonth(currentDate, changes, whtsh);
                }else{
                    alert('Something is wrong, please try again');
                }
            },
            error: function () {
                alert('error appeared')
            }
        });
    });

});

$(document).on('click', '#lesson-edit', function (event) {
    event.preventDefault();

    $('#addLesson-form')[0].reset();
    var lessonId = $(this).attr('lessonId');
    var user_id = $(this).attr('user_id');


    $.ajax({
        url: 'calendar',
        type: 'post',
        data: {updateLesson: lessonId, user_id: user_id},
        success: function (data) {

            var modal = $('#modal-addLesson').modal('show');
            modal.find('.modal-body').load($('.modal-dialog'));
            $('.headerLessonCalendarForm').text('Update Lesson!');

            $('#addLesson-confirm').removeClass('btn-success');
            $('#addLesson-confirm').addClass('btn-warning');
            $('#addLesson-confirm').text('Update Lesson');

            $('#datetimepicker5').val(data.action_date);
            $('#datetimepicker6').val(data.lesson_start);
            $('#comment').val(data.comment);
            $('#lessonIdToUpdate').val(data.id);
            $('#lessonUserId').val(data.user_id);

            $('#addlessonform-lesson_length').empty().trigger("change");
            $("#addlessonform-lesson_length").select2({
                data: data.lessonLength
            }).trigger('change.select2');

            $("#addlessonform-lesson_length").select2({ width: '100%' }).val(data.lesson_length_type).trigger('change.select2');

            $('#addlessonform-student_id').empty().trigger("change");
            $("#addlessonform-student_id").select2({
                data: data.studentsList
            }).trigger('change.select2');

            $("#addlessonform-student_id").select2({ width: '100%' }).val(data.student_id).trigger('change.select2');

            $('#addlessonform-instricon_id').empty().trigger("change");
            $("#addlessonform-instricon_id").select2({
                data: data.lessonList
            }).trigger('change.select2');

            $("#addlessonform-instricon_id").select2({ width: '100%' }).val(data.instricon_id).trigger('change.select2');

            $("#addlessonform-statusschedule_id").select2().val(data.statusschedule_id).trigger('change.select2');

            $('#addlessonform-student_id').select2({
                escapeMarkup: function (text) { return text; },
                placeholder: 'Student',
                theme: 'bootstrap',
                allowClear: true,
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            $('#addlessonform-lesson_length').select2({
                escapeMarkup: function (text) { return text; },
                placeholder: 'Choose...',
                theme: 'bootstrap',
                allowClear: true,
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            $('#addlessonform-instricon_id').select2({
                escapeMarkup: function (text) { return text; },
                placeholder: 'Type of the Lesson',
                theme: 'bootstrap',
                allowClear: true,
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            $('#addlessonform-statusschedule_id').select2({
                escapeMarkup: function (text) { return text; },
                placeholder: 'Status',
                theme: 'bootstrap',
                allowClear: true,
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

        },
        error: function (data) {

        }
    });

});

function onClickChangeMonth(currentDate, changes) {

    $.ajax({
        url: 'statistics',
        type: 'post',
        data: {
            currentDate: currentDate,
            changes: changes,
            _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
        },
        success: function (data) {
            $('#mainStatistics').html(data);
        },
        error: function () {
            alert('Error!!!');
        }
    });
}

$(document).on('click', '#editUser', function (event) {
    event.preventDefault();

    $('#userUpdateForm_Management')[0].reset();

    var modal = $('#modalUserEdit').modal('show');
    modal.find('.modal-body').load($('.modal-dialog'));
    var that = $(this);
    var first_name = that.data('first_name');
    var last_name = that.data('last_name');
    var lessons = that.data('lessons');
    var teachers = that.data('teachers');
    var user_id = that.data('user_id');
    var role = that.data('role');
    var phone = that.data('phone');

    $('#first_name').val(first_name);
    $('#last_name').val(last_name);
    $('#user_idInput').val(user_id);
    $('#user_role').val(role);
    $('#phone').val(phone);

    // $("#addlessonform-instricon_id, #addlessonform-statusschedule_id").select2().val(null).trigger('change.select2');

    if (role == 'Student'){
        var hideElement = document.getElementById('upFormLessons');
        hideElement.style.display = 'none';
        var showElement = document.getElementById('upFormTeachers');
        showElement.style.display = 'block';
        $("#userupdateform-lessons").select2({ width: '100%' }).val('').trigger('change.select2');
        $("#userupdateform-teachers").select2({ width: '100%' }).val(teachers).trigger('change.select2');

        $('#userupdateform-teachers').select2({
            escapeMarkup: function (text) { return text; },
            placeholder: 'Type of the Lesson',
            theme: 'bootstrap',
            allowClear: true,
            minimumResultsForSearch: Infinity,
            width: '100%',
            closeOnSelect: false
        });
    }else{
        var hideElement = document.getElementById('upFormTeachers');
        hideElement.style.display = 'none';
        var showElement = document.getElementById('upFormLessons');
        showElement.style.display = 'block';
        $("#userupdateform-teachers").select2({ width: '100%' }).val('').trigger('change.select2');
        $("#userupdateform-lessons").select2({ width: '100%' }).val(lessons).trigger('change.select2');

        $('#userupdateform-lessons').select2({
            escapeMarkup: function (text) { return text; },
            placeholder: 'Type of the Lesson',
            theme: 'bootstrap',
            allowClear: true,
            minimumResultsForSearch: Infinity,
            width: '100%',
            closeOnSelect: false
        });
    }


});

$(document).ready(function(){
    $(".site-inviteUser #w0").on("change", function() {
        var role = $(this).val();
        if(role == 'Teacher' || 'Master'){
            var hideElement = document.getElementById('addStudentForm');
            hideElement.style.display = 'none';
            var showElement = document.getElementById('inviteForm');
            showElement.style.display = 'block';
            $("#userform-role").select2().val(role).trigger('change.select2');
            $('#userform-role').select2({
                value: role,
                theme: 'bootstrap',
                allowClear: true,
                width: '100%',
                options: {'class': 'hide'}
            });
        }

        if (role == 'Student'){
            var hideElement = document.getElementById('inviteForm');
            hideElement.style.display = 'none';
            var showElement = document.getElementById('addStudentForm');
            showElement.style.display = 'block';
        }

        if (role == ''){
            var hideElement = document.getElementById('inviteForm');
            hideElement.style.display = 'none';
            var showElement = document.getElementById('addStudentForm');
            showElement.style.display = 'none';
        }

    });
});

$('#reg-button').click(function() {
    $('#reg-button').prop('disabled', true);
});
$('#editUserButton').click(function() {
    $('#editUserButton').prop('disabled', true);
});

$('#addStudent').click(function() {
    $('#addStudent').prop('disabled', true);
});

$('#inviteTeacher').click(function() {
    $('#inviteTeacher').prop('disabled', true);
});

$('#editProfile').click(function() {
    $('#editProfile').prop('disabled', true);
});

$('#changePass').click(function() {
    $('#changePass').prop('disabled', true);
});

function showBT(userId, action){

    if (action === 'open') {
        $('.'+userId+'bt').css('display', 'table-cell');
        $('.'+userId+'arrow').attr('onClick','showBT('+userId+', \'close\')').html('(hide <i class="fa fa-caret-up" aria-hidden="true"></i>)');
    }
    if (action === 'close') {
        $('.'+userId+'bt').css('display', 'none');
        $('.'+userId+'arrow').attr('onClick','showBT('+userId+', \'open\')').html('(show <i class="fa fa-caret-down" aria-hidden="true"></i>)');
    }
}

function setBusinessType(teacherId, btId = null) {
    var modal = $('#modalTeacherBT');
    $('#modalTeacherBTForm')[0].reset();

    if (btId) {
        $.ajax({
            url: '/master/users/edit-bt',
            type: 'post',
            data: {
                btId: btId
            },
            success: function (data) {
                var bt = data.result;
                $('#teacherbusinesstypeform-business_type').val(bt.type);
                $('#datetimepicker5').val(data.date_from);
                $('#teacherbusinesstypeform-teacher_id').val(bt.user_id);
                $('#teacherbusinesstypeform-btrow').val(bt.id);
            },
            error: function () {
                alert('Error!!!');
            }
        });
    }else{
        $('#teacherbusinesstypeform-teacher_id').val(teacherId);
    }
    modal.modal('show');
}

function delBusinessType(btId) {
    $.ajax({
        url: '/master/users/del-bt',
        type: 'post',
        data: {
            btId: btId
        },
        success: function (data) {
            window.location.reload();
        },
        error: function () {
            alert('Error!!!');
        }
    });
}

function showPricePolicy() {
    var modal = $('#modalPriceManagement');
    $('#pricing_form')[0].reset();
    $("#pricingform-teacherid").select2().val('').trigger('change.select2');
    $('#pricingform-teacherid').select2({
        escapeMarkup: function (text) { return text; },
        placeholder: 'Choose the Teacher',
        theme: 'bootstrap',
        allowClear: true,
        width: '100%',
    });

    $("#pricingform-studentid").select2().val('').trigger('change.select2');
    $('#pricingform-studentid').select2({
        escapeMarkup: function (text) { return text; },
        placeholder: 'Choose the Student',
        theme: 'bootstrap',
        allowClear: true,
        width: '100%',
    });

    modal.modal('show');
}

function setPricePolicy(teacherId, studentId, lessonId, date) {
    $('#pricing_form')[0].reset();

    $('#pricingform-instrumentid').val(lessonId);
    $('#datetimepicker5').val(date);

    $("#pricingform-teacherid").select2().val(teacherId).trigger('change.select2');
    $('#pricingform-teacherid').select2({
        escapeMarkup: function (text) { return text; },
        placeholder: 'Choose the Teacher',
        theme: 'bootstrap',
        allowClear: true,
        width: '100%',
    });

    $("#pricingform-studentid").select2().val(studentId).trigger('change.select2');
    $('#pricingform-studentid').select2({
        escapeMarkup: function (text) { return text; },
        placeholder: 'Choose the Student',
        theme: 'bootstrap',
        allowClear: true,
        width: '100%',
    });

    var modal = $('#modalPriceManagement');
    modal.modal('show');
}

function deletePrice(priceId) {
    $.ajax({
        url: '/master/price/del-price',
        type: 'post',
        data: {
            id: priceId
        },
        success: function (data) {
            window.location.reload();
        },
        error: function () {
            alert('Error!!!');
        }
    });
}

function editPrice(priceId) {
    $.ajax({
        url: '/master/price/edit-price',
        type: 'post',
        data: {
            id: priceId
        },
        success: function (data) {
            var priceData = data.result;

            $('#pricing_form')[0].reset();

            $('#pricingform-id').val(priceData.id);
            $('#pricingform-instrumentid').val(priceData.instrument_id);
            $('#pricingform-target').val(priceData.target_qnt_lessons);
            $('#pricingform-s_clean').val(priceData.short_clean_money);
            $('#pricingform-s_tax').val(priceData.short_tax_money);
            $('#pricingform-m_clean').val(priceData.middle_clean_money);
            $('#pricingform-m_tax').val(priceData.middle_tax_money);
            $('#pricingform-l_clean').val(priceData.long_clean_money);
            $('#pricingform-l_tax').val(priceData.long_tax_money);

            $('#datetimepicker5').val(data.date_from);

            $("#pricingform-teacherid").select2().val(priceData.teacher_id).trigger('change.select2');
            $('#pricingform-teacherid').select2({
                escapeMarkup: function (text) { return text; },
                placeholder: 'Choose the Teacher',
                theme: 'bootstrap',
                allowClear: true,
                width: '100%',
            });

            $("#pricingform-studentid").select2().val(priceData.student_id).trigger('change.select2');
            $('#pricingform-studentid').select2({
                escapeMarkup: function (text) { return text; },
                placeholder: 'Choose the Student',
                theme: 'bootstrap',
                allowClear: true,
                width: '100%',
            });

            var modal = $('#modalPriceManagement');
            modal.modal('show');
        },
        error: function () {
            alert('Error!!!');
        }
    });
}

$(function() {
    $('.popup-delete-price').click(function(e) {
        e.preventDefault();
        var modal = $('#modal-delete').modal('show');
        modal.find('.modal-body').load($('.modal-dialog'));
        $('#delete-confirm').prop('disabled', false);
        var that = $(this);
        var id = that.data('id');
        modal.find('.modal-name').text('this data');

        $('#delete-confirm').click(function(e) {
            e.preventDefault();
            $('#delete-confirm').prop('disabled', true);
            deletePrice(id);
        });
    });
});