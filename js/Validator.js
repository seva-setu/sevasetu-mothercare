/* global Wilde */

var Validator = {

  init: function () {
    this.setFieldListeners();
  },

  setFieldListeners: function () {
    var fields = document.querySelectorAll( 'input, textarea, select' ),
      self = this;

    for ( var i = fields.length - 1; i >= 0; i-- ) {
      /* jshint loopfunc: true */
      fields[ i ].addEventListener( 'change', function ( evt ) {
        evt = evt || window.event;
        self.isInvalid( evt.target );
      } );
    }
  },

  hasErrors: function ( form ) {

    var fields = form.querySelectorAll( 'input, textarea, select' ),
      err = 0;

    for ( var i = fields.length - 1; i >= 0; i-- ) {
      if ( this.isInvalid( fields[ i ] ) ) err++;
    }

    return ( err > 0 );
  },

  isInvalid: function ( field ) {
    var self = this,
      rules, name, args, message, valid, err = 0;

    // if ( !field.checkValidity() ) {
    //   field.parentNode.classList.add( 'invalid' );
    //   return true;
    // }

    rules = field.getAttribute( 'data-rules' );

    if ( rules ) {
      rules = rules.split( '|' );

      for ( var i = rules.length - 1; i >= 0; i-- ) {
        bits = rules[ i ].split( ':' );
        ruleName = bits[ 0 ];
        args = [];

        if ( bits.length > 1 ) args = bits[ 1 ].split( ',' );

        args.unshift( field );

        if ( !self.ruleFunctions[ ruleName ].apply( self.ruleFunctions, args ) ) {
          message = field.getAttribute( 'data-error-message' ) || self.messageFunctions[ ruleName ].apply( self.messageFunctions, args );
          err++;
        }
      }
    }

    invalid = ( err > 0 );

    if ( invalid ) {
      self.helpers.setInvalid( field, message );
    } else {
      self.helpers.setValid( field );
    }

    return invalid;
  },

  helpers: {

    setInvalid: function ( field, message ) {
      field.parentNode.querySelector( '.error-message' ).innerHTML = message;
      field.setCustomValidity( message );
    },

    setValid: function ( field ) {
      field.parentNode.querySelector( '.error-message' ).innerHTML = '';
      field.setCustomValidity( "" );
    },

    getSize: function ( value ) {
      var type = Validator.helpers.getType( value ),
        size;

      switch ( type ) {
      case 'number':
        size = value;
        break;
      case 'string':
        size = value.length;
        break;
      case 'array':
        size = value.length;
        break;
      default:
        size = value.length;
      }

      return size;
    },

    getType: function ( value ) {
      if ( value.match( /[0-9]+/g ) ) return 'number';
      if ( typeof value === 'string' ) return 'string';
      if ( Array.prototype.isArray( value ) ) return 'array';
    }

  },

  messageFunctions: {

    after: function ( field, date ) {
      return 'The value for ' + field.name + ' must be a valid date after ' + new Date( date ).toString().substring( 4, 15 );
    },

    alpha: function ( field ) {
      return 'The value for ' + field.name + ' must only contain letters';
    },

    alpha_dash: function ( field ) {
      return 'The value for ' + field.name + ' may only contain letters, numbers or dashes';
    },

    alpha_num: function ( field ) {
      return 'The value for ' + field.name + ' may only contain letters or numbers';
    },

    alpha_space: function ( field ) {
      return 'The value for ' + field.name + ' may only contain letters, numbers, spaces or dashes';
    },

    array: function ( field ) {
      return 'The value for ' + field.name + ' must be an array';
    },

    before: function ( field, date ) {
      return 'The value for ' + field.name + ' must be a valid date before ' + new Date( date ).toString().substring( 4, 15 );
    },

    between: function ( value, min, max ) {
      var type = Validator.helpers.getType( value ),
        message = 'The value for ' + field.name + ' must ';

      switch ( Validator.helpers.getType( value ) ) {
      case 'string':
        message += 'be between ' + min + ' and ' + max + ' characters';
        break;
      case 'array':
        message += 'contain between ' + min + ' and ' + max + ' items';
        break;
      default:
        message += 'be between ' + min + ' and ' + max;
      }

      return message;
    },

    boolean: function ( field ) {
      return 'The value for ' + field.name + ' must be either true or false';
    },

    confirmed: function ( field ) {
      return 'The values for ' + field.name + ' and ' + field.name + '_confirmation must match';
    },

    date: function ( field ) {
      return 'The value for ' + field.name + ' must be a valid date';
    },

    different: function ( field, other_name ) {
      return 'The values for ' + field.name + ' and ' + other_name + ' must not match';
    },

    digits: function ( field, len ) {
      return 'The value for ' + field.name + ' must be a number with ' + len + ' digits';
    },

    digits_between: function ( field, min, max ) {
      return 'The value for ' + field.name + ' must be a number with between ' + min + ' and ' + max + ' digits';
    },

    domain: function ( field ) {
      return 'The value for ' + field.name + ' must be a valid domain name';
    },

    email: function ( field ) {
      return 'The value for ' + field.name + ' must be a valid email address';
    },

    image: function ( field ) {
      return 'The file ' + field.name + ' must be an image';
    },

    in : function ( field ) {
      var message, args = arguments;
      args.shift();
      message = 'The value for ' + field.name + ' must be one of ' + args.join( ', ' );
      return message.substring( 0, message.lastIndexOf( ',' ) ) + ' or' + message.substr( message.lastIndexOf( ',' ) + 1 );
    },

    integer: function ( field ) {
      return 'The value for ' + field.name + ' must be a valid integer';
    },

    ip: function ( field ) {
      return 'The value for ' + field.name + ' must be a valid IP Address';
    },

    max: function ( field, max ) {
      var type = Validator.helpers.getType( field.value ),
        message = 'The value for ' + field.name + ' must ';

      switch ( Validator.helpers.getType( field.value ) ) {
      case 'string':
        message += 'be less than ' + max + ' characters';
        break;
      case 'array':
        message += 'contain less than ' + max + ' items';
        break;
      default:
        message += 'be less than ' + max;
      }

      return message;
    },

    min: function ( field, min ) {
      var type = Validator.helpers.getType( field.value ),
        message = 'The value for ' + field.name + ' must ';

      switch ( Validator.helpers.getType( field.value ) ) {
      case 'string':
        message += 'be more than ' + min + ' characters';
        break;
      case 'array':
        message += 'contain more than ' + min + ' items';
        break;
      default:
        message += 'be more than ' + min;
      }

      return message;
    },

    not_in: function ( field ) {
      var message, args = arguments;
      args.shift();
      args.shift();
      message = 'The value for ' + field.name + ' must not be one of ' + args.join( ', ' );
      return message.substring( 0, message.lastIndexOf( ',' ) ) + ' or' + message.substr( message.lastIndexOf( ',' ) + 1 );
    },

    numeric: function ( field ) {
      return 'The value for ' + field.name + ' must be a valid number';
    },

    regex: function ( field, regex ) {
      return 'The value for ' + field.name + ' must match the Regular Expression: ' + regex;
    },

    required: function ( field ) {
      return 'You must enter a value for ' + field.name;
    },

    required_if: function ( field ) {
      return 'You must enter a value for ' + field.name;
    },

    required_unless: function ( field ) {
      return 'You must enter a value for ' + field.name;
    },

    same: function ( field, other_name ) {
      return 'The value for ' + field.name + ' must match the value for ' + other_name;
    },

    size: function ( field, size ) {
      var type = Validator.helpers.getType( field.value ),
        message = 'The value for ' + field.name + ' must ';

      switch ( Validator.helpers.getType( field.value ) ) {
      case 'string':
        message += 'be ' + size + ' characters in length';
        break;
      case 'array':
        message += 'contain exactly ' + size + ' items';
        break;
      default:
        message += 'be equal to ' + size;
      }

      return message;
    },

    url: function ( field ) {
      return 'The value for ' + field.name + ' must be a valid URL';
    }

  },

  ruleFunctions: {

    after: function ( field, date ) {
      return ( this.date( field.value ) && new Date( date ).getTime() < new Date( field.value ).getTime() );
    },

    alpha: function ( field ) {
      return !field.value.match( /[^a-zA-Z]+/g );
    },

    alpha_dash: function ( field ) {
      return !field.value.match( /[^a-zA-Z0-9-]+/g );
    },

    alpha_num: function ( field ) {
      return !field.value.match( /[^a-zA-Z0-9]+/g );
    },

    alpha_space: function ( field ) {
      return !field.value.match( /[^a-zA-Z0-9- ]+/g );
    },

    array: function ( field ) {
      return Array.prototype.isArray( field.value );
    },

    before: function ( field, date ) {
      return ( this.date( field.value ) && new Date( date ).getTime() > new Date( field.value ).getTime() );
    },

    between: function ( field, min, max ) {
      var size = Validator.helpers.getSize( field.value );
      return ( size >= min && size <= max );
    },

    boolean: function ( field ) {
      var accepted = ( true, false, 'true', 'false', 1, 0, '1', '0' );
      return ( accepted.indexOf( field.value ) > -1 );
    },

    confirmed: function ( field ) {
      var confirmation = document.querySelector( '[name="' + field.name + '_confirmation"]' );
      return ( confirmation && field.value === confirmation.value );
    },

    date: function ( field ) {
      var d = new Date( field.value );
      return ( d && d.getFullYear() > 0 );
    },

    different: function ( field, name ) {
      return ( document.querySelector( '[name="' + name + '"]' ).value !== field.value );
    },

    digits: function ( field, len ) {
      return ( field.value.toString().length === len );
    },

    digits_between: function ( field, min, max ) {
      var len = field.value.toString().length;
      return ( len >= min && len <= max );
    },

    domain: function ( field ) {
      return !field.value.match( /(([^a-zA-Z\-0-9]+\.)+[^a-zA-Z]{2,})$/gi );
    },

    email: function ( field ) {
      return field.value.match( /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/gi );
    },

    image: function ( field ) {
      var _validFileExtensions = [ ".jpg", ".jpeg", ".bmp", ".gif", ".png" ],
        ext = value.substring( field.value.lastIndexOf( '.' ), field.value.length );
      return ( _validFileExtensions.indexOf( ext ) > -1 );
    },

    in : function ( field ) {
      var args = arguments;
      args.shift();
      return ( args.indexOf( field.value ) > -1 );
    },

    integer: function ( field ) {
      return ( field.value == parseInt( field.value, 10 ) );
    },

    ip: function ( field ) {
      return field.value.match( /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/g );
    },

    max: function ( field, max ) {
      var size = Validator.helpers.getSize( field.value );
      return ( size <= max );
    },

    min: function ( field, min ) {
      var size = Validator.helpers.getSize( field.value );
      return ( size >= min );
    },

    not_in: function ( field ) {
      var args = arguments;
      args.shift();
      return ( args.indexOf( field.value ) === -1 );
    },

    numeric: function ( field ) {
      return ( field.value == parseFloat( field.value ) );
    },

    regex: function ( field, regex ) {
      return field.value.match( regex );
    },

    required: function ( field ) {
      return ( field.value ? true : false );
    },

    required_if: function ( field, name, test_value ) {
      var other_fields = document.querySelectorAll( '[name="' + name + '"]' ),
        other_field = other_fields[ other_fields.length - 1 ],
        test = ( test_value == 'checked' ? other_field.checked : other_field.value == test_value );

      return ( test ? this.required( field ) : true );
    },

    required_unless: function ( field, name, test_value ) {
      var other_fields = document.querySelectorAll( '[name="' + name + '"]' ),
        other_field = other_fields[ other_fields.length - 1 ],
        test = !( test_value == 'checked' ? other_field.checked : other_field.value == test_value );

      return ( test ? this.required( field ) : true );
    },

    same: function ( field, name ) {
      return ( field.value === document.querySelector( '[name="' + name + '"]' ).value );
    },

    size: function ( field, size ) {
      return ( size === Validator.helpers.getSize( field.value ) );
    },

    url: function ( field ) {
      return field.value.match( /((?:(http|https|Http|Https|rtsp|Rtsp):\/\/(?:(?:[a-zA-Z0-9\$\-\_\.\+\!\*\'\(\)\,\;\?\&\=]|(?:\%[a-fA-F0-9]{2})){1,64}(?:\:(?:[a-zA-Z0-9\$\-\_\.\+\!\*\'\(\)\,\;\?\&\=]|(?:\%[a-fA-F0-9]{2})){1,25})?\@)?)?((?:(?:[a-zA-Z0-9][a-zA-Z0-9\-]{0,64}\.)+(?:(?:aero|arpa|asia|a[cdefgilmnoqrstuwxz])|(?:biz|b[abdefghijmnorstvwyz])|(?:cat|com|coop|c[acdfghiklmnoruvxyz])|d[ejkmoz]|(?:edu|e[cegrstu])|f[ijkmor]|(?:gov|g[abdefghilmnpqrstuwy])|h[kmnrtu]|(?:info|int|i[delmnoqrst])|(?:jobs|j[emop])|k[eghimnrwyz]|l[abcikrstuvy]|(?:mil|mobi|museum|m[acdghklmnopqrstuvwxyz])|(?:name|net|n[acefgilopruz])|(?:org|om)|(?:pro|p[aefghklmnrstwy])|qa|r[eouw]|s[abcdeghijklmnortuvyz]|(?:tel|travel|t[cdfghjklmnoprtvwz])|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw]))|(?:(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9])\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9]|0)\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9]|0)\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])))(?:\:\d{1,5})?)(\/(?:(?:[a-zA-Z0-9\;\/\?\:\@\&\=\#\~\-\.\+\!\*\'\(\)\,\_])|(?:\%[a-fA-F0-9]{2}))*)?(?:\b|$)/gi );
    }

  }

};