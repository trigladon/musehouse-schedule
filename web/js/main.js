/**
 * Created by bdionis on 31.03.17.
 */
function onClickMonth(currentDate, changes, whtsh) {
    // var currentDate = document.getElementById('currentDate').getAttribute('monthToShow');
    // alert (currentDate+' '+changes);

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
        var that = $(this);
        var id = that.data('id');
        var name = that.data('name');
        modal.find('.modal-name').text(name);

        $('#delete-confirm').click(function(e) {
            e.preventDefault();
            window.location = '?deleteId='+id;
        });
    });
});

$(function() {
    $('.popup-update').on('click', function(e) {
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
        var modal = $('#modal-addLesson').modal('show');
        modal.find('.modal-body').load($('.modal-dialog'));
        e.preventDefault();
        var currentDate = document.getElementById('infoDiv').getAttribute('currentDate');
        var whtsh = document.getElementById('infoDiv').getAttribute('whtsh');
        var changes = '';


        var that = $(this);
        var month = that.data('month');
        var day = that.data('day');
        var year = that.data('year');
        var week = that.data('week');

        $('.actionDateInput').text(day+'-'+month+'-'+year);

        $('.action_date_form').val(day+'_'+month+'_'+year+'_'+week);

        onClickMonth(currentDate, changes, whtsh);
    });
});

$(document).on('beforeSubmit', '#addLesson-form', function () {
    $('#modal-addLesson').modal('hide');
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
                        $('#addLesson-form').yiiActiveForm('AddLessonForm', key, '');
                        $('#addLesson-form').yiiActiveForm('AddLessonForm', key, [val]);
                    });
                }else {
                    if (data.success == 1){
                        // alert('success');
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