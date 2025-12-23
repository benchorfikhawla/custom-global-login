jQuery(document).ready(function ($) {
    function uploader(button, input, preview) {
        $(button).on('click', function (e) {
            e.preventDefault();
            const frame = wp.media({ title: 'Select image', button: { text: 'Use this image' }, multiple: false });
            frame.on('select', function () {
                const attachment = frame.state().get('selection').first().toJSON();
                $(input).val(attachment.url);
                $(preview).attr('src', attachment.url);
            });
            frame.open();
        });
    }
    uploader('.cgl-upload-logo', '#cgl_login_logo', '#cgl_logo_preview');
    uploader('.cgl-upload-hero', '#cgl_login_hero', '#cgl_hero_preview');
    uploader('.cgl-upload-maintenance-logo', '#cgl_maintenance_logo', '#cgl_maintenance_logo_preview');
});
