<html>
   <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>Ajax Example</title>

      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
      </script>

      <script>
        let csrf_token = $('meta[name="csrf-token"]').attr('content');

        function getMessage() {
            $.ajax({
                type   : 'POST',
                url    : '/getmsg ',
                data   : { '_token'  : csrf_token },
                success: function(data) {
                    $("#msg").html(data.msg);
                }
            });
        }
      </script>
   </head>

   <body>
      <div id = 'msg'>This message will be replaced using Ajax.
         Click the button to replace the message.</div>

        <button onclick="getMessage()">Click Me</button>
   </body>

</html>
