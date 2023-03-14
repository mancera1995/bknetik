<button
        data-location="<?php echo (isset($parameters['location'])     && is_numeric($parameters['location']) )   ? htmlspecialchars($parameters['location']) : ''; ?>"
        data-theme="<?php echo (isset($parameters['theme'])           && is_numeric($parameters['theme']) )      ? htmlspecialchars($parameters['theme']) : ''; ?>"
        data-category="<?php echo (isset($parameters['category'])     && is_numeric($parameters['category']) )   ? htmlspecialchars($parameters['category']) : ''; ?>"
        data-staff="<?php echo (isset($parameters['staff'])           && is_numeric($parameters['staff']) )      ? htmlspecialchars($parameters['staff']) : ''; ?>"
        data-service="<?php echo (isset($parameters['service'])       && is_numeric($parameters['service']) )    ? htmlspecialchars($parameters['service']) : ''; ?>"
        class='bnktc_booking_popup_btn <?php echo isset($parameters['class']) ? htmlspecialchars($parameters['class']) : "" ?>'
        <?php echo isset($parameters['style']) ? 'style="'. htmlspecialchars($parameters['style']) .'"' : '' ?>>
    <?php echo isset($parameters['caption']) ? htmlspecialchars($parameters['caption']) : bkntc__( 'Book now' ) ;?>
</button>
<script>
    ( function ( $ ) {
        $( document ).ready( function () {
            if ( typeof BookneticData == "object" ) {
                return;
            }

            let ajax_url     = '<?php echo admin_url("admin-ajax.php"); ?>';
            let theme        = $( this ).data( 'theme' );
            let addFileToDOM = ( file ) => {
                if ( file.type === 'js' ) {
                    if( document.querySelector( `script[id='${file.id}']` ) ) {
                        return;
                    }

                    let script = document.createElement( 'script' );

                    script.src = file.src;
                    script.id  = file.id;

                    document.body.appendChild( script );
                } else if ( file.type === 'css' ) {
                    if( document.querySelector( `link[id='${file.id}']` ) ) {
                        return;
                    }

                    let link = document.createElement( 'link' );

                    link.href = file.src;
                    link.id   = file.id;
                    link.type = 'text/css';
                    link.rel  = 'stylesheet';

                    document.getElementsByTagName( 'head' )[ 0 ].appendChild( link );
                }
            }

            $.ajax( {
                type: 'POST',
                url: ajax_url,
                data: { action: "bkntc_get_booking_panel_necessary_files", theme },
                success: function( response ) {
                    response = JSON.parse( response );

                    if ( response.status !== 'ok' ) {
                        return;
                    }

                    let results = response.results;

                    if ( ! results ) {
                        return;
                    }

                    let scripts = results.scripts;
                    let files = results.files;

                    if ( !! scripts && scripts.length > 0 ) {
                        for ( let i = 0; i < scripts.length; i++ ) {
                            eval( scripts[ i ] );
                        }
                    }

                    for ( let key in files ) {
                        addFileToDOM( files[ key ] );
                    }
                }
            } );
        } );
    } )( jQuery );
</script>