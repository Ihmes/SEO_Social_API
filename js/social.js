var SOCIAL = {};

SOCIAL = {

  socialGetPinterest: function() {

    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://api.pinterest.com/v1/urls/count.json?callback=SOCIAL.socialPinterest&url=https://www.advertising.de/sitecheck/';
    $("body").append(script);

  },

  socialPinterest: function(payload) {

    $('.ag-social-pinterest span').html(payload.count);

  },

  socialButtons: function() {

    var socialButtons = $('.ag-social-button'),
        socialComp    = $('.sb-group');

    socialButtons.html('<span><img src="gfx/ajax-loader.gif" alt="loading" /></span>');

    SOCIAL.socialGetPinterest();

    $.getJSON( "php/socialCount.php", function(data) {

      $.each(data, function(key, val) {

        if (key == 'facebook') {
          $('.ag-social-facebook span').html(val);
        }
        if (key == 'twitter') {
          $('.ag-social-twitter span').html(val);
        }
        if (key == 'googleplus') {
          $('.ag-social-google span').html(val);
        }
        if (key == 'xing') {
          $('.ag-social-xing span').html(val);
        }
        if (key == 'linkedin') {
          $('.ag-social-linkedin span').html(val);
        }

      });
     
    });

    if (document.referrer) {

      $.each(socialComp, function() {

        var ref = document.referrer,
            resort = $(this).children();

        if (ref.indexOf("facebook.com") > -1) {
          $(resort[0]).insertBefore(resort[0]);
        }

        if (ref.indexOf("twitter.com") > -1 || ref.indexOf("t.co") > -1) {
          $(resort[1]).insertBefore(resort[0]);
        }

        if (ref.indexOf("plus.url.google.com") > -1) {
          $(resort[2]).insertBefore(resort[0]);
        }

        if (ref.indexOf("xing.com") > -1) {
          $(resort[3]).insertBefore(resort[0]);
        }

        if (ref.indexOf("linkedin.com") > -1) {
          $(resort[4]).insertBefore(resort[0]);
        }

      });

    }

    socialButtons.click(function() {
      var new_window =  window.open(this.href, "Teile diesen Inhalt", "height=400,width=600,status=yes,toolbar=no,menubar=no,location=no");
      new_window.moveTo((screen.width / 2) - 300, (screen.height / 2) - 450);
      new_window.focus();
      return false;
    });

  }

};


$(document).ready(function() {

  SOCIAL.socialButtons();

});