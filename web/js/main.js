/**
 * Created by bdionis on 31.03.17.
 */
function onClickMonth(changes) {
    var currentDate = document.getElementById('currentDate').getAttribute('monthToShow');
    // alert (currentDate+' '+changes);

    $.ajax({
        url: 'calendar',
        type: 'post',
        data: {
            currentDate: currentDate,
            changes: changes,
            _csrf : '<?=Yii::$app->request->getCsrfToken()?>'
        },
        success: function (data) {
            $('#mainCalendar').html(data);
            console.log(data);
        },
        error: function () {
            alert('Error!!!');
        }
    });
}