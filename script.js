jQuery(document).ready(function ($) {
  $('.banner-image-column').show();

  window.onload = function () {
    let searchinput = document.getElementsByClassName(
      'et_pb_menu__search-input'
    )[0];
    searchinput.placeholder = 'Type and press enter!';
  };
  $('input[type=text],input[type=email],select,input[type=tel],textarea').on(
    'focusin',
    function () {
      $(this).closest('label').css('color', '#892094');
    }
  );
  $('input[type=text],input[type=email],select,input[type=tel],textarea').on(
    'focusout',
    function () {
      $(this).closest('label').css('color', '#000');
    }
  );
  $('.oath')
    .mouseenter(function () {
      $('.oath-items').css('opacity', 0);
      $('.sarah').css('opacity', 1);
    })
    .mouseleave(function () {
      $('.oath-items').css('opacity', 1);
      $('.sarah').css('opacity', 0);
    });

  $(window).on('load', function () {});

  const aboutUsObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        console.log('Here!');
        $('.page-id-1275 .grid-item.first-name').addClass('first-name-box');
        $('.page-id-1275 .grid-item.last-name').addClass('last-name-box');
        $('.page-id-1275 .grid-item.email-id').addClass('email-name-box');
        $('.page-id-1275 .grid-item.submit-box').addClass('submit-button-box');
      }
      //          else {
      //             $(".active").removeClass("underlined");
      //         }
    });
  }, {});

  aboutUsObserver.observe(
    $(
      '.et_pb_section.et_pb_section_0_tb_footer.et_pb_with_background.et_section_regular'
    )[0]
  );
});
