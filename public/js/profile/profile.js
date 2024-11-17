
var values = new Array()
var checkVals = new Array()
var dob = $('input[type=date]').val();

// Store Original Values
$('input[type=text]').map(function(idx, elem){
    values.push($(elem).val());
});
// Store original values and initialize hidden inputs
$(':checkbox').map(function(idx, elem) {
    var hidden = $(elem).closest('tr').find('input[type=hidden]')

    if ($(elem).is(':checked')){
        checkVals.push(true);
        hidden.prop('disabled', true)
    } else{
        checkVals.push(false);
        hidden.prop('disabled', false)
    }
    
})

// Make submit/cancel appear when edit in profile info clicked.
$(".infoDiv ion-icon:not(.helpIcon)").click(
    function(){ 

        $(this).find("+ input").prop('readonly', false); 
        $(this).find("+ input").focus();

        $(".submit").prop('disabled', false);
        $(".cancel").prop('disabled', false);  
        
    }
);

// Make submit/cancel appear when edit in vehicle info clicked.
$(".vehiInfo ion-icon").click(
    function(){ 

        $(this).find("+ input").prop('readonly', false); 
        $(this).find("+ input").focus();

        $(".submit2").prop('disabled', false);
        $(".cancel2").prop('disabled', false);  
        
    }
);

$(':checkbox').click( function() {
    $(".submit2").prop('disabled', false);
    $(".cancel2").prop('disabled', false); 
})

// Disable/Enable hidden inputs if checkbox is checked or not
$(':checkbox').change(function() {
    var hidden = $(this).closest('tr').find('input[type=hidden]');

    if ($(this).is(':checked')){
        hidden.prop('disabled', true)
    } else {
        hidden.prop('disabled', false)
    }
})


// If cancel is clicked, reset values
$('.cancel').click( function(){
    
    $('input[type=text]').each(function(idx, elem){
       $(this).val(values[idx])
       $(this).prop('readonly', true)
    });

    $('input[type=date]').val(dob).prop('readonly', true);

    $(".submit").prop('disabled', true);
    $(".cancel").prop('disabled', true);
});

$('.cancel2').click( function(){
    
    $('input[type=text]').each(function(idx, elem){
       $(this).val(values[idx])
       $(this).prop('readonly', true)
    });
    $(':checkbox').each(function(idx, elem) {
        if (checkVals[idx]){
            $(this).prop('checked', true)
        } else {
            $(this).prop('checked', false)
        }
    })

    $(".submit2").prop('disabled', true);
    $(".cancel2").prop('disabled', true);
});
