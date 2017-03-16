$(document).ready(function()
{
    /*            $('.datepicker').datetimepicker({
     timepicker: false,
     format: 'Y/m/d',
     });*/

    $(function(){
        $('#startDateBox').datetimepicker({
            format:'Y/m/d',
            onShow:function( ct ){
                this.setOptions({
                    maxDate:$('#endDateBox').val()?$('#endDateBox').val():false
                })
            },
            timepicker:false
        });

        $('#endDateBox').datetimepicker({
            format:'Y/m/d',
            onShow:function( ct ){
                this.setOptions({
                    minDate:$('#startDateBox').val()?$('#startDateBox').val():false
                })
            },
            timepicker:false
        });
    });


    $('.group-select-text').click(function(e)
    {
        $('#search_form_2').show();
        $('#search_form_1').hide();
    });

    $('.event-select-text').click(function(e)
    {
        $('#search_form_2').hide();
        $('#search_form_1').show();
    });

    $('#location-select li').click(function(e)
    {
        var m2 = $('#menu2');
        $( "input[name='location']").val($(this).val());
        m2.text($(this).text());
        m2.append("<span class=\"caret\"></span>")
    });

    $('#type-select li').click(function(e)
    {
        var m5 = $('#menu5');
        $( "input[name='type']").val($(this).val());
        m5.text($(this).text());
        m5.append("<span class=\"caret\"></span>")
    });

    $('#group-location-select li').click(function(e)
    {
        var m4 = $('#menu4');
        $( "input[name='group-location']").val($(this).val());
        m4.text($(this).text());
        m4.append("<span class=\"caret\"></span>")
    });

    $("#search_form_1").submit(function(){
        var isFormValid = true;
        $(".check-value").each(function(){
            if ($.trim($(this).val()).length == 0){
                isFormValid = false;
            }else {
                isFormValid = true;
                return false;
            }
        });

        if (isFormValid == false){
            alert('至少選擇一項');
            return isFormValid;
        }
    });


});
