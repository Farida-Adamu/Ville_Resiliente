( function () {
	var toggle = document.querySelector( '.menu-toggle' );
	var header = document.querySelector( 'header.site' );
	if ( ! toggle || ! header ) {
		return;
	}

	function setOpen( open ) {
		header.classList.toggle( 'menu-open', open );
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	}

	toggle.addEventListener( 'click', function () {
		setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && header.classList.contains( 'menu-open' ) ) {
			setOpen( false );
			toggle.focus();
		}
	} );

	document.addEventListener( 'click', function ( e ) {
		if ( header.classList.contains( 'menu-open' ) && ! header.contains( e.target ) ) {
			setOpen( false );
		}
	} );
} )();
