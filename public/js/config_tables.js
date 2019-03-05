

function new_table_entry()
{
    button = document.querySelector('#cfg-add');
    table = button.dataset.form;
}


$( function() {
    $("#dialog").off("click");
    $( "#dialog" ).dialog({
        autoOpen: false,
        modal: true,
        buttons: {
        "Add Entry": new_table_entry,
        Cancel: function() {
            dialog.dialog( "close" );
        }
        },
        close: function() {
            //form[ 0 ].reset();
            //allFields.removeClass( "ui-state-error" );
            $( "#dialog" ).dialog( "close" );
        }
    });

    $( "#cfg-add" ).on( "click", function() {
      $( "#dialog" ).dialog( "open" );
      new_table_entry();
    });
} );

