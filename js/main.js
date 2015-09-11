/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, jQuery, document, Modernizr, History */

function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

// VARS

var retina = Modernizr.highresdisplay,
  largeImageThreshold = 1100,
  largerImageThreshold = 1600,
  largestImageThreshold = 2000,

  noSpreadLayoutThreshold = 600,

  margin = 35,

  galleryAnimationLength = 500,

  windowHeight = $(window).height(),
  windowWidth = $(window).width(),

  captionHeight = $('.single-slider-text').outerHeight(),

  initSlide,
  State = History.getState(),
  hashGalleryValue = parseInt(State.hash.substring(State.hash.indexOf('#') + 1));


// FUNCTIONS

$(window).on('resize', function() {
  windowHeight = $(window).height();
  windowWidth = $(window).width();
});

  // LAZY IMAGES

function lazyLoadImages(selector) {
  var smallRetina = (largeImageThreshold / 2);

  $(selector).each(function() {
    var $this = $(this);
    var data = $this.data();

    if (retina) {
      if (windowWidth > largerImageThreshold) {
        $this.attr('src', data.largest);
      } if (windowWidth < smallRetina) {
        $this.attr('src', data.basic);
      } else {
        $this.attr('src', data.large);
      }

    } else if (windowWidth > largestImageThreshold) {
      $this.attr('src', data.largest);
    } else if (windowWidth > largerImageThreshold) {
      $this.attr('src', data.larger);
    } else if (windowWidth > largeImageThreshold) {
      $this.attr('src', data.large);
    } else {
      $this.attr('src', data.basic);
    }

    $this.imagesLoaded(function() {
      this.images[0].img.className += ' img-loaded';

      if ( $('.js-packery-container').length ) {
        $('.js-packery-container').packery();
      }
    });

  });
}


// OBJECTS

  // HOME SPREADS

var Spreads = {
  containerHeight: 0,
  containerMargin: 0,
  init: function() {
    var _this = this;

    lazyLoadImages('.spread-image');

    _this.firstSpread();
    _this.containerLayoutFix();
    _this.resizeImages();

    $('.home-spread').on('click', function(e) {
      if (e.target.tagName !== 'A') {
        _this.nextSpread();
      }
    });

    $(window).resize( $.debounce( 250, function() {
        _this.containerLayoutFix();
        _this.resizeImages();
      })
    );
  },

  containerLayoutFix: function() {
    var _this = this;
    _this.containerMargin = parseInt($('#header').css('padding-top'), 10);
    _this.containerHeight = windowHeight - $('#header').outerHeight(true) - _this.containerMargin;

    $('#spread-container').height( _this.containerHeight );
  },

  resizeImages: function () {
    var _this = this;

    if (noSpreadLayoutThreshold > windowWidth) {
      return false;
    }

    $('.home-spread.home-spread-active').children('.spread-image-wrapper').each(function() {

      $(this).css('height','');

      var $this = $(this),
        imageWrapHeight = $this.height(),
        imageMaxHeight = 0,
        position = $this.position(),
        top = position.top,
        scale = ($this.attr('data-scale')*0.01),
        imageCaptionHeight = $this.find('.spread-image-caption').outerHeight();

      if ((imageWrapHeight + top) > _this.containerHeight) {

        $this.css({
          'height' : (_this.containerHeight - top) + 'px'
        });

      }

      imageWrapHeight = $this.height();
      // why is this set again in the same way?

      if (scale > 0) {
        if (scale > 1) {
          scale = 1;
        }
        imageMaxHeight = (imageWrapHeight*scale) - imageCaptionHeight;
      } else {
        imageMaxHeight = imageWrapHeight - imageCaptionHeight;
      }

      if (imageMaxHeight < 60) {
        imageMaxHeight = 60;
      }

      $this.find('.spread-image').css( 'max-height', imageMaxHeight );

    });
  },

  firstSpread: function() {

    var length = $('.home-spread').length;
    var random = getRandomInt(0, length);

    $('.home-spread').eq(random).addClass('home-spread-active');
    var spreadColor = $('.home-spread-active').attr('data-color');
    if (typeof(spreadColor) !== 'undefined') {
      $('body').css('background-color', spreadColor);
    } else {
      $('body').css('background-color', 'rgb(253,253,253)');
    }

  },

  nextSpread: function() {
    var activeSpread = $('.home-spread.home-spread-active');
    var nextSpread = activeSpread.next();

    activeSpread.removeClass('home-spread-active');

    if (nextSpread.length) {
      nextSpread.addClass('home-spread-active');
    } else {
      $('.home-spread').first().addClass('home-spread-active');
    }

    var spreadColor = $('.home-spread-active').attr('data-color');
    if (typeof(spreadColor) !== 'undefined') {
      $('body').css('background-color', spreadColor);
    } else {
      $('body').css('background-color', 'rgb(253,253,253)');
    }

    this.resizeImages();

  }
};

  // SLICK

var Slick = {
  init: function() {
    var _this = this;

    $('.js-slick-container').on({
      init: function() {

        // set length for n of * in captions
        var slidesLength = $('.js-slick-item').length;
        if (slidesLength === 1) {
          $('.slide-nav').remove();
        }

        // lazy load images for screen resolution
        lazyLoadImages('.slider-img');

        // fade in slides when image loaded

        $('.slick-active').imagesLoaded( function() {
          $('.slick-active').find('.u-held').css( 'opacity' , 1 );
        });


        $('#single-slider').imagesLoaded( function() {
          $('.u-held').css('opacity', 1);
        });

        // fix images for window height
        _this.resizeImages();

        // fade in when ready
        $('#single-slider').css( 'opacity' , 1 );
      },
    })
    .slick({
      fade: true,
      speed: galleryAnimationLength,
      prevArrow: '.slick-prev',
      nextArrow: '.slick-next',
    });

    if (hashGalleryValue > 1) {
      initSlide = $('[data-index="' + (hashGalleryValue - 1) + '"]').attr('data-slick-index');
      $('.js-slick-container').slick('slickGoTo', (hashGalleryValue - 1));
      window.location.hash = '';
    }

    // If gallery has length next arrow advances gallery until end then allows <a> link
    $('#single-next').on('click', function(e) {
      if ($('.js-slick-container').slick('slickCurrentSlide') !== ($('#single-next').data('gallery-length')-1)) {
        e.preventDefault();
        $('.js-slick-container').slick('slickNext');
      }
    });

    // Prev moves gallery until first slide then allows <a> link
    $('#single-prev').on('click', function(e) {
      if ($('.js-slick-container').slick('slickCurrentSlide') !== 0) {
        e.preventDefault();
        $('.js-slick-container').slick('slickPrev');
      }
    });

    $(window).resize( $.debounce( 250, function() {
        _this.resizeImages();
      })
    );
  },

  resizeImages: function() {
    $('#single-slider').css( 'height', windowHeight );
    $('.js-slick-item img').css({
      'max-height' : ( windowHeight - captionHeight - (margin * 2) ),
    });
  }
};


jQuery(document).ready(function () {
  'use strict';

  // CONDITIONAL INITS
  if ( $('.js-grid-img').length ) {
    lazyLoadImages('.js-grid-img');
  }

  if ( $('.js-packery-container').length ) {

    $('#loader').css('opacity', 1);
    $('.js-packery-container').packery({
      itemSelector: '.js-packery-item',
      columnWidth: '.js-packery-item',
      transitionDuration: '0s',
      percentPosition: true
    });

    $('.js-packery-container').imagesLoaded( function() {

      $('#loader').css('opacity', 0);
      lazyLoadImages('.js-grid-img-deferred');
    });
  }

  if ($('#single-slider').length) {
    $('#single-slider').css( 'height', windowHeight );
    $('#loader').css('opacity', 1);
    $('#single-slider').imagesLoaded( function() {
      $('#loader').css('opacity', 0);
    });
  }

  if ($('body').hasClass('home')) {
    Spreads.init();
  }

  if ( $('.js-slick-item').length ) {
    Slick.init();
  }

});