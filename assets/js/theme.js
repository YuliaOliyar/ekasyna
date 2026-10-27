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
	// Pop-upy: bonus i newsletter. Nigdy dwa naraz.
	var storage = function ( key, value ) {
		try {
			return value === undefined ? window.localStorage.getItem( key ) : window.localStorage.setItem( key, value );
		} catch ( e ) {
			return null;
		}
	};
	var anyOpen = function () {
		return !! document.querySelector( '.ek-popup.is-open' );
	};
	var makePopup = function ( el, key, firstFocus ) {
		var days = parseInt( el.dataset.days || '1', 10 );
		var lastFocus = null;
		var api = {
			blocked: function () {
				if ( storage( key + 'Done' ) ) {
					return true;
				}
				var t = parseInt( storage( key ) || '0', 10 );
				return days > 0 && t && ( Date.now() - t ) < days * 864e5;
			},
			open: function () {
				if ( anyOpen() ) {
					return false;
				}
				lastFocus = document.activeElement;
				el.hidden = false;
				document.body.classList.add( 'ek-popup-open' );
				window.requestAnimationFrame( function () {
					el.classList.add( 'is-open' );
					var f = el.querySelector( firstFocus );
					if ( f ) {
						f.focus( { preventScroll: true } );
					}
				} );
				return true;
			},
			close: function () {
				storage( key, String( Date.now() ) );
				el.classList.remove( 'is-open' );
				document.body.classList.remove( 'ek-popup-open' );
				setTimeout( function () {
					el.hidden = true;
				}, 250 );
				if ( lastFocus && lastFocus.focus ) {
					lastFocus.focus( { preventScroll: true } );
				}
			},
		};
		el.querySelectorAll( '[data-ek-popup-close]' ).forEach( function ( b ) {
			b.addEventListener( 'click', api.close );
		} );
		el.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) {
				api.close();
			} else if ( e.key === 'Tab' ) {
				var f = el.querySelectorAll( 'a[href], button:not([disabled]), input:not([type="hidden"]):not(.ek-hp)' );
				var first = f[ 0 ];
				var last = f[ f.length - 1 ];
				if ( e.shiftKey && document.activeElement === first ) {
					e.preventDefault();
					last.focus();
				} else if ( ! e.shiftKey && document.activeElement === last ) {
					e.preventDefault();
					first.focus();
				}
			}
		} );
		return api;
	};
	var qs = window.location.search;

	var bonusEl = document.querySelector( '[data-ek-popup]' );
	if ( bonusEl ) {
		var bonus = makePopup( bonusEl, 'ekPopupClosed', '.ek-popup__cta' );
		var accept = bonusEl.querySelector( '[data-ek-popup-accept]' );
		if ( accept ) {
			accept.addEventListener( 'click', function () {
				storage( 'ekPopupClosed', String( Date.now() ) );
			} );
		}
		if ( /[?&]popup=1/.test( qs ) ) {
			bonus.open();
		} else if ( ! /[?&]newsletter=1/.test( qs ) && ! bonus.blocked() ) {
			setTimeout( bonus.open, Math.max( 0, parseInt( bonusEl.dataset.delay || '8', 10 ) ) * 1000 );
		}
	}

	var nlEl = document.querySelector( '[data-ek-newsletter]' );
	if ( nlEl ) {
		var nl = makePopup( nlEl, 'ekNewsletterClosed', '.ek-nl__input' );
		var tryOpen = function () {
			if ( ! nl.blocked() ) {
				nl.open();
			}
		};
		if ( /[?&]newsletter=1/.test( qs ) ) {
			nl.open();
		} else if ( ! /[?&]popup=1/.test( qs ) && ! nl.blocked() ) {
			var delay = parseInt( nlEl.dataset.delay || '0', 10 );
			if ( delay > 0 ) {
				setTimeout( tryOpen, delay * 1000 );
			}
			if ( nlEl.dataset.exit === '1' && window.matchMedia( '(hover: hover)' ).matches ) {
				document.addEventListener( 'mouseout', function onLeave( e ) {
					if ( ! e.relatedTarget && e.clientY <= 0 ) {
						document.removeEventListener( 'mouseout', onLeave );
						tryOpen();
					}
				} );
			}
		}

		var form = nlEl.querySelector( '[data-ek-nl-form]' );
		var err = nlEl.querySelector( '[data-ek-nl-error]' );
		var done = nlEl.querySelector( '[data-ek-nl-done]' );
		var showError = function ( msg ) {
			err.textContent = msg;
			err.hidden = false;
		};
		var success = function () {
			storage( 'ekNewsletterClosedDone', '1' );
			form.hidden = true;
			done.hidden = false;
		};
		if ( form ) {
			form.addEventListener( 'submit', function ( e ) {
				var email = form.querySelector( 'input[type="email"]' );
				var consent = form.querySelector( 'input[name="ek_consent"]' );
				err.hidden = true;
				if ( ! email.value || ! email.checkValidity() ) {
					e.preventDefault();
					showError( 'Podaj poprawny adres e-mail.' );
					email.focus();
					return;
				}
				if ( consent && ! consent.checked ) {
					e.preventDefault();
					showError( 'Zaznacz zgodę, aby się zapisać.' );
					consent.focus();
					return;
				}
				if ( form.dataset.external === '1' ) {
					// Formularz zewnętrzny otwiera się w nowej karcie.
					setTimeout( success, 300 );
					return;
				}
				e.preventDefault();
				var action = form.getAttribute( 'action' ) || '';
				if ( action.indexOf( '#demo' ) !== -1 ) {
					success();
					return;
				}
				var data = new window.FormData( form );
				data.append( 'ek_ajax', '1' );
				window.fetch( action, { method: 'POST', body: data, credentials: 'same-origin' } )
					.then( function ( r ) {
						return r.json();
					} )
					.then( function ( res ) {
						if ( res && res.success ) {
							success();
						} else {
							showError( ( res && res.data && res.data.message ) || 'Nie udało się zapisać. Spróbuj ponownie.' );
						}
					} )
					.catch( function () {
						showError( 'Nie udało się zapisać. Spróbuj ponownie.' );
					} );
			} );
		}
	}
}() );
