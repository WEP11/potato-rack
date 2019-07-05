function writeFormData(table)
{ 
    form = $('#form-' + table).serialize();
    
    $.ajax({
        type: 'POST',
        url: 'config_submit.php',
        data: form,
        success: function(data){
            alert("Data Entered!");
        }
    });

}




