<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink codesample emoticons image link lists searchreplace visualblocks wordcount linkchecker accordian',
        toolbar: 'undo redo | blocks fontsize forecolor backcolor | bold italic underline strikethrough | link image media accordion | spellcheckdialog typography | align lineheight | numlist bullist indent outdent | emoticons | removeformat',
        menubar: false,
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        skin: window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "oxide-dark"
            : "oxide",
        content_css: window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "default",
    });
</script>
