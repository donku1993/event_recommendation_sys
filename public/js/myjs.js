function uploadImage() {
    $(document).on('click', '#close-preview', function(){
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
                $('.image-preview').popover('show');
            },
            function () {
                $('.image-preview').popover('hide');
            }
        );
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("Browse");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200
            });
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("Change");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            }
            reader.readAsDataURL(file);
        });
    });
}
function uploadFile() {

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.file').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.file-clear').click(function(){
            $('.file').attr("data-content","").popover('hide');
            $('.file-filename').val("");
            $('.file-clear').hide();
            $('.file-input input:file').val("");
            $(".file-input-title").text("Browse");
        });


        $(".file-input input:file").change(function (){

            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".file-input-title").text("Change");
                $(".file-clear").show();
                $(".file-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });

    });
}
function activeClass() {
    $(document).ready(function() {
        var last_str = window.location.pathname;
        var segment_array = last_str.split( '/' );
        var last_segment = segment_array[segment_array.length - 1];

        switch ( last_segment ){
            case 'edit':
                var user_info = $('.user-info');
                user_info.parent().addClass('active');
                break;
            case 'groups':
                var user_groups = $('.user-groups');
                user_groups.parent().addClass('active');
                break;
            case 'events':
                var user_events = $('.user-events');
                user_events.parent().addClass('active');
                break;
            default:
                break;
        }

    });
}
function openUploadIcon() {
    $(document).ready(function() {
        $('#icon-modal').modal('show');
    });
}