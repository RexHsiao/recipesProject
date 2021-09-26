    </div>
    <footer>
        <div class="container">
            <div class="row vcenter">
                <div class="col-xs-6">
                    <p>&copy; 2021-<?php echo date("Y");?></p>
                </div>
            </div>
        </div>
    </footer>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
</body>
</html>