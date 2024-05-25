
      $(function() {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.

          $('.textarea').summernote({
              minHeight: 300,
              placeholder: 'Votre contenu ici...',

              fontNames: ['Open Sans','Arial','Courier New'],
              height:300,
	popover: {
    air: [
      ['color', ['color']],
      ['font', ['bold', 'underline', 'clear']]
    ]
  }
          });
		  $('.textarea:first').summernote('focus');

    });
     