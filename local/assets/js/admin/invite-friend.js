$(".addMore").click(function() {
    var counter = $("#counter").val();
    counter++;
    var htmlData = '<tr id="emailRow'+counter+'">'
    +'<td>#'+counter+'</td>'
    +'<td><input required type="text" name="frnName[]" placeholder="Jon Doe" class="form-control" name=""></td>'
    +'<td><input required type="email" name="frnEmail[]" placeholder="jondoe@example.com" class="form-control" name=""></td>'
    +'<td><span style="margin-top: 5px !important;display: block;"><a href="javascript:void(0)" class="btn btn-icon btn-sm red removeTr" data-id="'+counter+'" title="Remove Row"><i class="fa fa-remove"></i></a></span></td>'
    +'</tr>';
    $("#EmailTable tbody").append(htmlData);
    
    $("#counter").val(counter);
});

$(document).on( "click", ".removeTr", function() {
    var id = $(this).attr('data-id');
    $("#emailRow"+id).remove();
});