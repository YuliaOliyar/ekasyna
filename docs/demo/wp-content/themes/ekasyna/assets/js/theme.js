/**
 * e-kasyna — menu mobilne, wyszukiwarka, „do góry”, pasek bonusu, aktywny punkt spisu treści.
 */
( function () {
	'use strict';

	// Przełączniki (menu, wyszukiwarka).
	document.querySelectorAll( '[data-ek-toggle]' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var target = document.getElementById( btn.getAttribute( 'aria-controls' ) );
			if ( ! target ) {
				return;
			}
			var open = btn.getAttribute( 'aria-expanded' ) !== 'true';
			btn.setAttribute( 'aria-expanded', String( open ) );
			if ( btn.dataset.ekToggle === 'search' ) {
				target.hidden = ! open;
				if ( open ) {
					var field = target.querySelector( 'input[type="search"]' );
					if ( field ) {
						field.focus();
					}
				}
			} else {
				target.classList.toggle( 'is-open', open );
			}
		} );
	} );

	// Escape zamyka menu i wyszukiwarkę.
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key !== 'Escape' ) {
			return;
		}
		document.querySelectorAll( '[data-ek-toggle][aria-expanded="true"]' ).forEach( function ( btn ) {
			btn.click();
		} );
	} );

	// Stan „przewinięto” — pokazuje „do góry” i pasek bonusu.
	var ticking = false;
	function onScroll() {
		document.body.classList.toggle( 'is-scrolled', window.scrollY > 600 );
		ticking = false;
	}
	window.addEventListener( 'scroll', function () {
		if ( ! ticking ) {
			window.requestAnimationFrame( onScroll );
			ticking = true;
		}
	}, { passive: true } );
	onScroll();

	var toTop = document.querySelector( '[data-ek-totop]' );
	if ( toTop ) {
		toTop.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );
	}

	// Podświetlenie aktywnej sekcji w spisie treści.
	var toc = document.querySelector( '[data-ek-toc]' );
	if ( toc && 'IntersectionObserver' in window ) {
		var links = {};
		toc.querySelectorAll( 'a[href^="#"]' ).forEach( function ( a ) {
			links[ decodeURIComponent( a.getAttribute( 'href' ).slice( 1 ) ) ] = a;
		} );
		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting && links[ entry.target.id ] ) {
					Object.keys( links ).forEach( function ( id ) {
						links[ id ].classList.remove( 'is-active' );
					} );
					links[ entry.target.id ].classList.add( 'is-active' );
				}
			} );
		}, { rootMargin: '0px 0px -70% 0px' } );
		Object.keys( links ).forEach( function ( id ) {
			var el = document.getElementById( id );
			if ( el ) {
				observer.observe( el );
			}
		} );
	}
	// Katalog A–Z: wyszukiwarka po nazwie.
	var az = document.querySelector( '[data-ek-az]' );
	if ( az ) {
		var input = az.querySelector( '[data-ek-az-search]' );
		var empty = az.querySelector( '[data-ek-az-empty]' );
		var norm = function ( s ) {
			return s.toLowerCase().normalize( 'NFD' ).replace( /[\u0300-\u036f]/g, '' ).replace( /ł/g, 'l' );
		};
		if ( input ) {
			input.addEventListener( 'input', function () {
				var q = norm( input.value.trim() );
				var shown = 0;
				az.querySelectorAll( '[data-ek-az-group]' ).forEach( function ( group ) {
					var any = false;
					group.querySelectorAll( '[data-ek-az-item]' ).forEach( function ( li ) {
						var match = ! q || norm( li.dataset.name ).indexOf( q ) !== -1;
						li.hidden = ! match;
						if ( match ) {
							any = true;
							shown++;
						}
					} );
					group.hidden = ! any;
				} );
				if ( empty ) {
					empty.hidden = shown > 0;
				}
			} );
		}
	}
	// Kopiowanie kodu bonusowego.
	document.querySelectorAll( '[data-ek-copy]' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var code = btn.getAttribute( 'data-ek-copy' );
			var label = btn.querySelector( '[data-ek-copy-label]' );
			var done = function () {
				btn.classList.add( 'is-copied' );
				if ( label ) {
					label.textContent = 'Skopiowano';
				}
				setTimeout( function () {
					btn.classList.remove( 'is-copied' );
					if ( label ) {
						label.textContent = 'Kopiuj';
					}
				}, 2000 );
			};
			if ( navigator.clipboard ) {
				navigator.clipboard.writeText( code ).then( done, function () {} );
			}
		} );
	} );
}() );
