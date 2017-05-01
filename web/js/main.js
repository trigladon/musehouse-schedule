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
            console.log(currentDate);
            console.log(changes);
            console.log(whtsh);
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
        console.log(id, name);
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
    $("#datetimepicker6").on("dp.change", function (e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    $("#datetimepicker7").on("dp.change", function (e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
});

$(document).ready(function () {
    $('body').on('click', '.popup-addLesson', function(e) {
        console.log('zbs');
        $('#addLesson-form')[0].reset();
        $('#lessonIdToUpdate').val('');

        $("#addlessonform-instricon_id, #addlessonform-statusschedule_id").select2().val(null).trigger('change.select2');

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

function showLayer(layerName)
{
    if (document.getElementById) // Netscape 6 and IE 5+
    {
        var targetElement = document.getElementById(layerName);
        targetElement.style.display = 'block';
        $('#iconChange'+layerName).replaceWith('<i id="iconChange'+layerName+'" class="fa fa-caret-up iconShowHide" aria-hidden="true"></i>');
        $('#showMoreActions'+layerName).attr('onclick',  'hideLayer('+layerName+')');
    }
}

function hideLayer(layerName)
{
    if (document.getElementById)
    {
        var targetElement = document.getElementById(layerName);
        targetElement.style.display = 'none';
        $('#iconChange'+layerName).replaceWith('<i id="iconChange'+layerName+'" class="fa fa-caret-down iconShowHide" aria-hidden="true"></i>');
        $('#showMoreActions'+layerName).attr('onclick',  'showLayer('+layerName+')');
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
            alert('error appeared')
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
    console.log(window.lessonId);

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


    var lessonId = $(this).attr('lessonId');
    console.log(lessonId);

    $.ajax({
        url: 'calendar',
        type: 'post',
        data: {updateLesson: lessonId},
        success: function (data) {
            console.log(data);
            var modal = $('#modal-addLesson').modal('show');
            modal.find('.modal-body').load($('.modal-dialog'));
            $('.headerLessonCalendarForm').text('Update Lesson!');

            $('#addLesson-confirm').removeClass('btn-success');
            $('#addLesson-confirm').addClass('btn-warning');
            $('#addLesson-confirm').text('Update Lesson');

            $('#datetimepicker5').val(data.action_date);
            $('#datetimepicker6').val(data.lesson_start);
            $('#datetimepicker7').val(data.lesson_finish);
            $('#comment').val(data.comment);
            $('#lessonIdToUpdate').val(data.id);

            $("#addlessonform-instricon_id").select2({ width: '100%' }).val(data.instricon_id).trigger('change.select2');
            $("#addlessonform-statusschedule_id").select2().val(data.statusschedule_id).trigger('change.select2');

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
        error: function () {
            alert('error appeared')
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
            console.log(currentDate);
            // console.log(changes);
        },
        error: function () {
            alert('Error!!!');
        }
    });
}