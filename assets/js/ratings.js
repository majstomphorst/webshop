$(document).ready(function () {

  // collecting the ids from the products on the page
  var productIds = [];
  $(".buyButton").each(function () {
    productIds.push($(this).val());
  })

  // checks if there are any products on the page
  if (productIds.length > 0) {
    getRatingInfo(productIds);
    setEventListener();
  }

});

function getRatingInfo(productIds) {
  $.post("index.php", {
      // POST Data
      page: "ajax",
      action: "getRatingInfo",
      productIds: productIds
    },
    function (data) {

      array = JSON.parse(data);

      // card where the product is displayed
      $(".card").each(function () {

        // get the productId that is displayed on the card
        let productId = $(this).find(".buyButton").val();

        array.forEach(ratingInfo => {
          if (ratingInfo.product_id == productId) {
            let avg = parseFloat(ratingInfo.avgRating).toFixed(2)
            $(this).find(".avgRating").text(avg);
            $(this).find(".yourRating").text(ratingInfo.userRating);
            
            let card = $(this);
            colorStars(card, ratingInfo.userRating);
          }
        });
      });
    });
      
};

function setEventListener() {
  $(".card").each(function () {

    let productId = $(this).find(".buyButton").val();

    $(this).find("#stars li").click(function () {

      // getting value from clicked star
      foundRating = $(this).data("value");

      $(this).addClass('selected');
      // Send to the server
      $.post("index.php", {
          // POST Data
          page: "ajax",
          action: "updateRating",
          productId: productId,
          rating: foundRating
        },
        function (data) {
          var productIds = [];
          $(".buyButton").each(function () {
            productIds.push($(this).val());
          })
          getRatingInfo(productIds);
        });
    });
  });
}

function colorStars(cardBody, $userRating) {
  cardBody.find("#stars li").each(function () {
    if ($(this).attr('data-value') <= $userRating) {
      $(this).addClass('selected');
    } else {
      $(this).removeClass('selected');
    }
  });
}


// FIXME: backup file::
// $(document).ready(function () {

//   productId = $("#buyButton").val();
//   // get avg rating
//   getAvgRating();
//   // get user rating
//   getUserRating();

//   $("#stars li").click(function () {
//     // getting value from clicked star
//     foundRating = $(this).data("value");

//     $(this).addClass('selected');
//     // Send to the server
//     $.post("index.php", {
//         // POST Data
//         page: "ajax",
//         action: "updateRating",
//         productId: productId,
//         rating: foundRating
//       },
//       function (data) {
//         // reload ajax info;
//         getAvgRating();
//         // get user rating
//         getUserRating();
//       });

//   });
// });

// function colorStars($userRating) {

//   $("#stars li").each(function () {
//     if ($(this).attr('data-value') <= $userRating) {
//       $(this).addClass('selected');
//     } else {
//       $(this).removeClass('selected');
//     }
//   });

// }

// function getAvgRating() {
//   $.post("index.php", {
//       // POST Data
//       page: "ajax",
//       action: "getAvgRating",
//       productId: productId
//     },
//     function (data) {
//       $(".avgRating").text(data);
//     });
// }

// function getUserRating() {
//   $.post("index.php", {
//       // POST Data
//       page: "ajax",
//       action: "getUserRating",
//       productId: productId
//     },
//     function (data) {
//       if (data) {
//         $(".yourRating").text(data);
//         colorStars(data);
//       }

//     });
// }

// /* 1. Visualizing things on Hover - See next part for action on click */
// $('#stars li').on('mouseover', function () {
//   var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

//   // Now highlight all the stars that's not after the current hovered star
//   $(this).parent().children('li.star').each(function (e) {
//     if (e < onStar) {
//       $(this).addClass('hover');
//     } else {
//       $(this).removeClass('hover');
//     }
//   });

// }).on('mouseout', function () {
//   $(this).parent().children('li.star').each(function (e) {
//     $(this).removeClass('hover');
//   });
// });


// /* 2. Action to perform on click */
// $('#stars li').on('click', function () {
//   var onStar = parseInt($(this).data('value'), 10); // The star currently selected
//   var stars = $(this).parent().children('li.star');

//   for (i = 0; i < stars.length; i++) {
//     $(stars[i]).removeClass('selected');
//   }

//   for (i = 0; i < onStar; i++) {
//     $(stars[i]).addClass('selected');
//   }

//   // JUST RESPONSE (Not needed)
//   var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
//   console.log('hai');
//   updateRating("1", ratingValue);

// });